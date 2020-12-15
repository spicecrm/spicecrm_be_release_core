<?php

namespace SpiceCRM\modules\Administration\KREST\controllers;

use LanguageManager;
use Localization;
use SpiceCRM\includes\ErrorHandlers\Exception;
use SugarBean;
use VardefManager;

require_once('include/utils/file_utils.php');

use SpiceCRM\includes\ErrorHandlers\ForbiddenException;

class adminController
{
    public function systemstats($req, $res, $args)
    {
        global $db, $current_user, $sugar_config;

        $statsArray = [];

        if (!$current_user->is_admin) {
            throw new ForbiddenException();
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
     * @throws Exception
     *
     */
    function getGeneralSettings($req, $res, $args)
    {
        global $current_user, $db, $sugar_config;

        if (!$current_user->is_admin) {
            throw (new ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
        }

        return $res->withJson(array(
            'system' => [
                'name' => $sugar_config['system']['name'],
                'site_url' => $sugar_config['site_url'],
                'unique_key' => $sugar_config['unique_key'],
            ],
            'advanced' => [
                'developerMode' => $sugar_config['developerMode'],
                'stack_trace_errors' => $sugar_config['stack_trace_errors'],
                'dump_slow_queries' => $sugar_config['dump_slow_queries'],
                'log_memory_usage' => $sugar_config['log_memory_usage'],
                'slow_query_time_msec' => $sugar_config['slow_query_time_msec'],
                'upload_maxsize' => $sugar_config['upload_maxsize'],
                'upload_dir' => $sugar_config['upload_dir']
            ],
            'logger' => $sugar_config['logger']
        ));

    }

    /**
     * writes the values of system default settings in the config table
     * @param $req
     * @param $res
     * @param $args
     * @throws Exception
     */
    function writeGeneralSettings($req, $res, $args)
    {
        global $current_user, $db, $sugar_config;

        if (!$current_user->is_admin) {
            throw (new ForbiddenException('No administration privileges.'))->setErrorCode('notAdmin');
        }

        $diffArray = [];

        $postBody = $req->getParsedBody();

        if (!empty($postBody)) {
            // handle sytem settings
            foreach ($postBody['system'] as $itemname => $itemvalue) {
                switch($itemname){
                    case 'name':
                        $sugar_config['system']['name'] = $itemvalue;
                        $query = "UPDATE config SET value = '$itemvalue' WHERE categroy = 'system' AND name = '$itemname'";
                        $db->query($query);
                        break;
                    default:
                        $sugar_config[$itemname] = $itemvalue;
                        $diffArray[$itemname] = $itemvalue;
                }

            }

            // handle advanced settings
            foreach ($postBody['advanced'] as $itemname => $itemvalue) {
                $sugar_config[$itemname] = $itemvalue;
                $diffArray[$itemname] = $itemvalue;
            }

            // handle logger settings
            $sugar_config['logger'] = $postBody['logger'];
            $diffArray['logger'] = $postBody['logger'];
        }

        $configurator = new \SpiceCRM\modules\Configurator\Configurator();
        $configurator->handleOverrideFromArray($diffArray);

        return $res->withJson(array(
            'status' => boolval($query)
        ));


    }

    public function buildSQLforRepair($req, $res, $args)
    {
        global $db, $moduleList;
        $execute = false;
        VardefManager::clearVardef();
        $repairedTables = array();
        $sql = '';
        foreach ($moduleList as $module) {
            $focus = \BeanFactory::getBean($module);
            if (($focus instanceof SugarBean) && !isset($repairedTables[$focus->table_name])) {
                $sql .= $db->repairTable($focus, $execute);
                $repairedTables[$focus->table_name] = true;
            }
            // check on audit tables
            if (($focus instanceof SugarBean) && $focus->is_AuditEnabled() && !isset($repairedTables[$focus->table_name . '_audit'])) {
                $sql .= $focus->update_audit_table(false);
                $repairedTables[$focus->table_name . '_audit'] = true;
            }
        }

        $dictionary = array();
        include('modules/TableDictionary.php');

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

        // rebuild relationships
        $this->rebuildRelationships();


// using $res->withJson will spoil the json results. Just json_encode.
//        if($res) {
//            return $res->withJson(array('sql' => $sql));
//        } else{
        return json_encode(array('sql' => $sql));
//        }
    }

    /**
     * repairs and rebuilds the database
     * @param $res
     */
    public function repairAndRebuild($req, $res, $args)
    {
        global $current_user, $db;
        $errors = [];
        if (is_admin($current_user)) {
            $sqlDecoded = json_decode($this->buildSQLforRepair($req, $res, $args));
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

            // rebuild relationships
            // $this->rebuildRelationships();

        }
        if ($res) {
            return $res->withJson(array('response' => $response,
                'synced' => $synced,
                'sql' => $sql,
                'error' => $errors));
        } else {
            return json_encode(array('response' => $response,
                'synced' => $synced,
                'sql' => $sql,
                'error' => $errors));
        }
    }

    /**
     * rebuilds relationships
     *
     * ToDo: remove the need to have this
     */
    public function rebuildRelationships()
    {
        global $current_user, $db, $dictionary;
        foreach ($GLOBALS['moduleList'] as $module) {
            $focus = \BeanFactory::getBean($module);
            if(!$focus) continue;
            SugarBean::createRelationshipMeta($focus->getObjectName(), $db, $focus->table_name, [$focus->object_name => $dictionary[$focus->object_name]], $focus->module_dir);
        }

        // rebuild the metadata relationships as well
        $this->rebuildMetadataRelationships();

        // rebuild relationship cache
        $rel = new \Relationship();
        $rel->build_relationship_cache();
    }

    /**
     * rebuilds the metadata relationships
     *
     * TODo: remove this in the next version with the vardef manager
     */
    private function rebuildMetadataRelationships()
    {
        global $db;

        $dictionary = array();
        require('modules/TableDictionary.php');

        $rel_dictionary = $dictionary;
        foreach ($rel_dictionary as $rel_name => $rel_data) {
            $table = isset($rel_data ['table']) ? $rel_data ['table'] : "";
            SugarBean::createRelationshipMeta($rel_name, $db, $table, $rel_dictionary, '');
        }
    }

    /**
     * clears language cache and repairs the language extensions
     * @return array
     */
    public function repairLanguage($req, $res, $args)
    {
        global $sugar_config;

        $appListStrings = [];
        $appLang = [];
        $languages = $sugar_config['languages'];
        $langs = \LanguageManager::getLanguages();
        foreach ($languages as $language => $value) {

            $this->merge_files('Ext/Language/', $language . '.lang.ext.php', $language);

            $appListStrings[$language][] = return_app_list_strings_language($language);
            $appLang[$language][] = $this->loadLanguage($language);
        }

        if (!empty($appListStrings) && !empty($appLang)) {
            $response = 'ok';
        } else {
            $response = 'e';
        }

        return $res->withJson(array('response' => $response,
            'appList' => $appListStrings,
            'appLang' => $appLang,
            'languages' => $langs));
        // sugar_cache_reset();
    }

    /**
     * loads the applang labels for a language
     * @param $lang
     * @return array
     */
    private function loadLanguage($lang)
    {
        $syslanguagelabels = \LanguageManager::loadDatabaseLanguage($lang);
        $syslanguages = array();
        if (is_array($syslanguagelabels)) {
            foreach ($syslanguagelabels as $syslanguagelbl => $syslanguagelblcfg) {
                $syslanguages[$syslanguagelbl] = array(
                    'default' => $syslanguagelblcfg['default'],
                    'short' => $syslanguagelblcfg['short'],
                    'long' => $syslanguagelblcfg['long'],
                );
            }
        }

        return $syslanguages;
    }

    /**
     * merges the extension files and generates the contents in the cache folder
     * (sugar code)
     * @param $path
     * @param $name
     * @param string $filter
     */
    private function merge_files($path, $name, $filter = '')
    {
        foreach ($this->modules as $module) {
            $extension = "<?php \n //WARNING: The contents of this file are auto-generated\n";
            $extpath = "modules/$module/$path";
            $module_install = 'custom/Extension/' . $extpath;
            $shouldSave = false;
            if (is_dir($module_install)) {
                $dir = dir($module_install);
                $shouldSave = true;
                $override = array();
                while ($entry = $dir->read()) {
                    if ((empty($filter) || substr_count($entry, $filter) > 0) && is_file($module_install . '/' . $entry)
                        && $entry != '.' && $entry != '..' && strtolower(substr($entry, -4)) == ".php") {
                        if (substr($entry, 0, 9) == '_override') {
                            $override[] = $entry;
                        } else {
                            $file = file_get_contents($module_install . '/' . $entry);
                            $GLOBALS['log']->debug(get_class($this) . "->merge_files(): found {$module_install}{$entry}");
                            $extension .= "\n" . str_replace(array('<?php', '?>', '<?PHP', '<?'), array('', '', '', ''), $file);
                        }
                    }
                }
                foreach ($override as $entry) {
                    $file = file_get_contents($module_install . '/' . $entry);
                    $extension .= "\n" . str_replace(array('<?php', '?>', '<?PHP', '<?'), array('', '', '', ''), $file);
                }
            }
            $extension .= "\n?>";

            if ($shouldSave) {
                if (!file_exists("custom/$extpath")) {
                    mkdir_recursive("custom/$extpath", true);
                }
                $out = sugar_fopen("custom/$extpath/$name", 'w');
                fwrite($out, $extension);
                fclose($out);
            } else {
                if (file_exists("custom/$extpath/$name")) {
                    unlink("custom/$extpath/$name");
                }
            }
        }


        $GLOBALS['log']->debug("Merging application files for $name in $path");
        //Now the application stuff
        $extension = "<?php \n //WARNING: The contents of this file are auto-generated\n";
        $extpath = "application/$path";
        $module_install = 'custom/Extension/' . $extpath;
        $shouldSave = false;
        if (is_dir($module_install)) {
            $dir = dir($module_install);
            while ($entry = $dir->read()) {
                $shouldSave = true;
                if ((empty($filter) || substr_count($entry, $filter) > 0) && is_file($module_install . '/' . $entry)
                    && $entry != '.' && $entry != '..' && strtolower(substr($entry, -4)) == ".php") {
                    $file = file_get_contents($module_install . '/' . $entry);
                    $extension .= "\n" . str_replace(array('<?php', '?>', '<?PHP', '<?'), array('', '', '', ''), $file);
                }
            }
        }
        $extension .= "\n?>";
        if ($shouldSave) {
            if (!file_exists("custom/$extpath")) {
                mkdir_recursive("custom/$extpath", true);
            }
            $out = sugar_fopen("custom/$extpath/$name", 'w');
            fwrite($out, $extension);
            fclose($out);
        } else {
            if (file_exists("custom/$extpath/$name")) {
                unlink("custom/$extpath/$name");
            }
        }

    }

    /**
     * repairs ACL Roles
     */
    public function repairACLRoles($req, $res, $args)
    {
        global $current_user, $beanList, $beanFiles;
        $repairedACLs = [];
        $ACLActions = ACLAction::getDefaultActions();
        if (is_admin($current_user)) {
            if (!empty($ACLActions)) {
                foreach ($ACLActions as $action) {
                    if (!isset($beanList[$action->category])) {
                        ACLAction::removeActions($action->category);
                    }

                }
            } else {
                foreach ($beanList as $module => $class) {
                    if (empty($repairedACLs[$class]) && isset($beanFiles[$class]) && file_exists($beanFiles[$class])) {
                        $current_module = \BeanFactory::getBean($module);
                        if ($current_module->bean_implements('ACL') && empty($current_module->acl_display_only)) {
                            if (!empty($current_module->acltype)) {
                                ACLAction::addActions($current_module->getACLCategory(), $current_module->acltype);
                            } else {
                                ACLAction::addActions($current_module->getACLCategory());
                            }

                            $repairedACLs[$class] = true;
                        }
                    }
                }

            }
        }
        if ($res) {
            return $res->withJson(['installed_classes' => $repairedACLs]);
        } else {
            return json_encode(['installed_classes' => $repairedACLs]);
        }

    }

    /**
     * rebuilds vardefs extensions
     */
    private function rebuildExtensions()
    {
        $extensions = [];

        if (is_dir('custom/Extension/modules')) {
            $handle = opendir('custom/Extension/modules');
            while (false !== ($entry = readdir($handle)))
                if ($entry != "." && $entry != "..") {
                    $extensions[$entry] = "";
                    $subHandle = opendir("custom/Extension/modules/{$entry}/Ext/Vardefs");
                    while (false !== ($subEntry = readdir(($subHandle)))) {
                        if ($subEntry != "." && $subEntry != "..") {
                            $extensions[$entry] = $subEntry;
                        }
                    }
                }

        }

        if (!empty($extensions) && !empty(array_values($extensions))) {
            foreach ($extensions as $extDir => $extFile) {
                $this->merge_files("Ext/{$extDir}/Vardefs", $extFile);
            }
        }
    }

    /**
     * clears the vardef cache, executes rebuilding of vardefs extensions and
     * @param $req
     * @param $res
     * @param $args
     */
    public function repairCache($req, $res, $args)
    {
        global $current_user;
        if (is_admin($current_user)) {
            VardefManager::clearVardef();
            $this->rebuildExtensions();
            $this->merge_files("Ext/TableDictionary/", 'tabledictionary.ext.php');
            $this->rebuildRelationships();
        }
        return $res->withJson(['status' => 'ok']);
    }

}
