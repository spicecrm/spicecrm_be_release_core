<?php
/*********************************************************************************
* This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
* and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
* You can contact us at info@spicecrm.io
* 
* SpiceCRM is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
* 
* SpiceCRM is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
********************************************************************************/

/**
 * Class SpiceUIConfLoader
 * load UI reference config
 */
require_once 'modules/SystemUI/SpiceUILoader.php';

class SpiceUIConfLoader{
    public $sysuitables = array();

    public $loader;


    public function __construct(){
        $this->loader = new SpiceUILoader();
    }



    /**
     * Display load language form
     * in SpiceCRM Backend Administration
     * @return string
     */
    public function displayDefaultConfForm($params, $possibleparams, $obsoleteparams){
        $sm = new Sugar_Smarty();

        if(!empty($params['packages'])) $sm->assign('currentpackages', $params['packages']);
        if(!empty($params['versions'])) $sm->assign('currentversions', array_unique($params['versions']));

        if(!empty($possibleparams['packages'])) $sm->assign('possiblepackages', $possibleparams['packages']);
        if(!empty($possibleparams['versions'])) $sm->assign('possibleversions', $possibleparams['versions']);
        if(!empty($obsoleteparams)) $sm->assign('obsoletepackages', $obsoleteparams);

        //check on running change request
        $sm->assign("hasOpenChangeRequest", $this->loader->hasOpenChangeRequest());

        return $sm->display("modules/Administration/templates/UIDefault.tpl");
    }

    /**
     * retrieve table column names
     * @param $tb
     * @return array
     */
    public function getTableColumns($tb){
        $columns = $GLOBALS['db']->get_columns($tb);
        $cols = array();
        foreach($columns as $c => $col){
            $cols[] = $col['name'];
        }
        return $cols;
    }

    public function getPossibleConf(){
        //get data
        $endpoint = "referenceconfig";
        if(!$response = $this->loader->callMethod("GET", $endpoint)) {
            throw new Exception("REST Call error somewhere... Action aborted");
        }
        return $response;
    }

    /**
     * load sysui config from reference database
     * get column name for each table
     * make a select passing the column names
     * create insert queries.
     * @param $route
     * @param $params
     */
    public function loadDefaultConf($endpoint, $params){
//        echo '<pre>'. print_r($params, true);die();
        global $sugar_config;
        $tables = array();
        $truncates = array();
        $inserts = array();

        if($this->loader->hasOpenChangeRequest())
            throw new Exception("Open Change Requests found! They would be erased...");

        //get data
        if(!$response = $this->loader->callMethod("GET", $endpoint)){
            throw new Exception("REST Call error somewhere... Action aborted");
        }

        $this->sysuitables = array_keys($response);

        foreach($response as $tb => $content) {
            //truncate command
            $tables[] = $tb;
            switch($tb){
                case 'syslangs':
                    $delQ = "DELETE FROM $tb WHERE 1=1";//$GLOBALS['db']->truncateTableSQL($tb);
                    break;
                default:
                    $delQ = "DELETE FROM $tb WHERE package IN('".implode("','", $params['packages'])."') ";
                    if(in_array('core', $params['packages']))
                        $delQ.= "OR package IS NULL OR package=''";
            }

            $truncates[] = $delQ;
            $tbColCheck = false;

            foreach ($content as $id => $encoded) {
                if(!$decodeData = json_decode(base64_decode($encoded), true))
                    die("Error decoding data: ".json_last_error_msg().
                            "<br/>Reference table = $tb".
                            "<br/>Action aborted");

                //prcess only selected packages and empty package values
                if(empty($decodeData['package']) && !in_array('core', $params['packages'])){
                    continue;
                }
                elseif(!empty($decodeData['package']) && !in_array($decodeData['package'], $params['packages'])){
                    continue;
                }

                //compare table column names
                if (!$tbColCheck) {
                    $referenceCols = array_keys($decodeData);
                    $thisCols = $this->getTableColumns($tb);
                    if (!empty(array_diff($referenceCols, $thisCols))) {
                        die("Table structure for $tb is not up-to-date.".
                            "<br/>Reference table = ".implode(", ", $referenceCols).
                            "<br/>Client table = ".implode(", ", $thisCols).
                            "<br/>Action aborted");
                    }
                    $tbColCheck = true;
                }

                //prepare values for DB query
                foreach($decodeData as $key => $value){
                    $decodeData[$key] = (is_null($value) || $value==="" ? "NULL" : "'".$GLOBALS['db']->quote($value)."'");
                }
                //insert command
                $inserts[] = "INSERT INTO $tb (" . implode(",", $referenceCols) . ") ".
                    "VALUES(" . implode(",", array_values($decodeData)) . ")";
            }
        }

        //if no inserts where created => abort
        if(count($inserts) < 1){
            throw new Exception("No inserts found. Action aborted.");
        }

        $queries = array_merge($truncates, $inserts);
//        echo '<pre>'. print_r(implode(";\n", $queries), true);die();

        //process queries
        if(count($queries) > 2) {
            $errors = array();
            foreach ($queries as $q) {
                if(!$GLOBALS['db']->query($q))
                    $errors[] = "Error: ".$GLOBALS['db']->last_error;
            }
        }

        if(count($errors) <= 0) $success = true;

        return array("success" => $success, "queries" => count($queries), "errors" => $errors, "tables" => $tables);

    }


    /**
     * Remove sysmodules entries for modules that are not present in backend
     * @return bool
     */
    public function cleanDefaultConf(){
        // load moduleList
        global $current_user, $db;

        $sysmodules = [];
        if ($current_user->is_admin) {
            $sysmodulesres = $db->query("SELECT * FROM sysmodules");
            while ($sysmodule = $db->fetchByAssoc($sysmodulesres)) {
                $sysmodules[] = $sysmodule['module'];
            }
        };

        // process
        if(isset($GLOBALS['beanList']) && !empty($GLOBALS['beanList'])) {
            foreach ($sysmodules as $sysmodule) {
                if (!isset($GLOBALS['beanList'][$sysmodule])) {
                    $db->query("DELETE FROM sysmodules WHERE module='" . $sysmodule . "'");
                }
            }
        }
        return true;
    }

    /**
     * Get main information about current config loaded in client
     * package, version....
     */
    public function getCurrentConf(){
        global $db;
        $q = "(SELECT package, version FROM sysmodules WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuiactionsetitems WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuiactionsets WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuiadmincomponents WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuicomponentdefaultconf WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuicomponentmoduleconf WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuicomponentsets WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuicomponentsetscomponents WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuicopyrules WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuidashboarddashlets WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuifieldsets WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuifieldsetsitems WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuifieldtypemapping WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuilibs WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuimodulerepository WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuiobjectrepository WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuimodulerepository WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuirolemodules WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuiroles WHERE version is not null AND version <> '') UNION 
        (SELECT package, version FROM sysuiroutes WHERE version is not null AND version <> '') 
        ORDER BY package, version";
        $res = $db->query($q);
        $packages = array();
        $versions = array();

        while($row = $db->fetchByAssoc($res)){
            if(!empty($row['package'])) {
                $packages[] = $row['package'];
            }elseif (!in_array('core', $packages)){
                $packages[] = 'core';
            }
            if(!empty($row['version']) && !in_array($row['version'], $versions))
                $versions[] = $row['version'];
        }
        return array('packages' => $packages, 'versions' => $versions);
    }

    /**
     * get package names which are in current conf but not in reference conf
     * @param $currentconf
     * @param $possibleconf
     * @return array
     */
    public function getObsoleteConf($currentconf, $possibleconf){
        $currentpackages = $currentconf['packages'];
        $possiblepackages = array();
        $obsoltepackages = array();
        foreach($possibleconf['packages'] as $package){
            $possiblepackages[] = $package['package'];
        }
        foreach($currentpackages as $package)
            if(!in_array($package,$possiblepackages))
                $obsoltepackages[] = $package;
        return $obsoltepackages;
    }
}
