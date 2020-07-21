<?php

namespace SpiceCRM\modules\Administration\KREST\controllers;

use DynamicField;
use Localization;
use SugarBean;
use VardefManager;


class adminController
{
    public function systemstats($req, $res, $args)
    {
        global $db, $current_user, $sugar_config;

        $statsArray = [];

        if (!$current_user->is_admin) {
            throw new \SpiceCRM\KREST\ForbiddenException();
        }

        $stats = $db->query("SHOW TABLE STATUS");
        while ($stat = $db->fetchByAssoc($stats)) {

            $recordCount = $db->fetchByAssoc($db->query("SELECT count(*) records FROM {$stat['Name']}"));

            $statsArray['database'][] = [
                'name' => $stat['Name'],
                'records' => (int)$recordCount['records'],
                'size' => $stat['Data_length'] + $stat['Index_length']
            ];
        }

        // get the fts stats
        $ftsManager = new \SpiceCRM\includes\SpiceFTSManager\SpiceFTSHandler();
        $statsArray['elastic'] = $ftsManager->getStats();

        $statsArray['uploadfiles'] = $this->getDirectorySize($sugar_config['upload_dir']);

        return $res->write(json_encode($statsArray));
    }

    function getDirectorySize($directory)
    {
        $size = 0;
        $count = 0;
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory)) as $file) {
            $size += $file->getSize();
            $count++;
        }
        return ['size' => $size, 'count' => $count];
    }

    /**
     * get function to read the contents of system default locales in config table
     * @param $req
     * @param $res
     * @param $args
     * @throws \SpiceCRM\KREST\Exception
     *
     */
    function getGeneralSettings($req, $res, $args)
    {
        global $current_user, $db, $sugar_config;
        $locale = new Localization();
        $charsets = $locale->availableCharsets;
        $defaults = [
            'charsets' =>
                array(
                    'UTF-8' => 'UTF-8',
                    'CP1251' => 'MS Cyrillic',
                    'CP1252' => 'MS Western European & US',
                    'EUC-CN' => 'Simplified Chinese GB2312',
                    'EUC-JP' => 'Unix Japanese',
                    'EUC-KR' => 'Korean',
                    'EUC-TW' => 'Taiwanese'),
            'date_formats' =>
                array(
                    'Y-m-d' => '2010-12-23',
                    'm-d-Y' => '12-23-2010',
                    'd-m-Y' => '23-12-2010',
                    'Y/m/d' => '2010/12/23',
                    'm/d/Y' => '12/23/2010',
                    'd/m/Y' => '23/12/2010',
                    'Y.m.d' => '2010.12.23',
                    'd.m.Y' => '23.12.2010',
                    'm.d.Y' => '12.23.2010',
                ),
            'time_formats' =>
                array(
                    'H:i' => '23:00',
                    'h:ia' => '11:00pm',
                    'h:iA' => '11:00PM',
                    'h:i a' => '11:00 pm',
                    'h:i A' => '11:00 PM',
                    'H.i' => '23.00',
                    'h.ia' => '11.00pm',
                    'h.iA' => '11.00PM',
                    'h.i a' => '11.00 pm',
                    'h.i A' => '11.00 PM',
                ),
            'export_delimiter' =>
                array(';' => ';',
                    ',' => ',')
        ];
        if (!$current_user->is_admin) {
            throw (new \SpiceCRM\KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
        }


        $query = "SELECT * FROM config WHERE category = 'system' ORDER BY name DESC";
        $query = $db->query($query);
        while ($row = $db->fetchByAssoc($query)) {
            $settings[] = ['name' => $row['name'], 'value' => $row['value']];
        }


        return $res->withJson(array(
            'status' => boolval($query),
            'settings' => $settings,
            'defaults' => $defaults
        ));

    }

    /**
     * writes the values of system default settings in the config table
     * @param $req
     * @param $res
     * @param $args
     * @throws \SpiceCRM\KREST\Exception
     */
    function writeGeneralSettings($req, $res, $args)
    {
        global $current_user, $db, $sugar_config;

        if (!$current_user->is_admin) {
            throw (new \SpiceCRM\KREST\ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
        }

        $postBody = $req->getParsedBody();

        if (!empty($postBody)) {
            foreach ($postBody as $item) {
                $query = "UPDATE config SET value = '{$item['value']}' WHERE name = '{$item['name']}'";
                $db->query($query);
            }
        }
        return $res->withJson(array(
            'status' => boolval($query)
        ));


    }

    public function buildSQLforRepair()
    {
        global $db, $beanFiles;
        $execute = false;
        VardefManager::clearVardef();
        $repairedTables = array();
        $sql = '';
        include('include/modules.php');
        foreach ($beanFiles as $bean => $file) {
            if (file_exists($file)) {
                require_once($file);
                unset($GLOBALS['dictionary'][$bean]);
                $focus = new $bean ();
                if (($focus instanceof SugarBean) && !isset($repairedTables[$focus->table_name])) {
                    $sql .= $db->repairTable($focus, $execute);
                    $repairedTables[$focus->table_name] = true;
                }
                //Repair Custom Fields
                if (($focus instanceof SugarBean) && $focus->hasCustomFields() && !isset($repairedTables[$focus->table_name . '_cstm'])) {
                    $df = new DynamicField($focus->module_dir);
                    //Need to check if the method exists as during upgrade an old version of Dynamic Fields may be loaded.
                    if (method_exists($df, "repairCustomFields")) {
                        $df->bean = $focus;
                        $sql .= $df->repairCustomFields($execute);
                        $repairedTables[$focus->table_name . '_cstm'] = true;
                    }
                }
            }
        }

        $dictionary = array();
        include ('modules/TableDictionary.php');

        foreach ($dictionary as $meta) {

            if (!isset($meta['table']) || isset($repairedTables[$meta['table']]))
                continue;

            $tablename = $meta['table'];
            $fielddefs = $meta['fields'];
            $indices = $meta['indices'];
            $engine = isset($meta['engine']) ? $meta['engine'] : null;
            $sql .= $db->repairTableParams($tablename, $fielddefs, $indices, $execute, $engine);
            $repairedTables[$tablename] = true;
        }
        return json_encode(array('sql' => $sql));
    }

    /**
     * repairs and rebuilds the database
     * @param $res
     */
    function repairAndRebuild()
    {
        global $current_user, $db;
        $errors = [];
        if (is_admin($current_user)) {
            $sqlDecoded = json_decode($this->buildSQLforRepair());
            $sql = $sqlDecoded->sql;
            if (!empty($sql)) {
                $synced = false;
                foreach (explode("\n", $sql) as $line) {
                    if (!strpos($line, '*')) {
                        $queries[] = $line;
                    }
                }
                foreach ($queries as $query) {
                    if (!$db->query($query)) {
                        $errors[] = $db->lastDbError();
                        if ($errors[0] == false) {
                            unset($errors[0]);
                        }
                    }
                }
            } else {
                $synced = true;
            }


            if (!empty($errors)) {
                $response = false;
            } else {
                $response = true;
            }

        }
        return json_encode(array('response' => $response,
            'synced' => $synced,
            'sql' => $sql,
            'error' => $errors));
    }

}
