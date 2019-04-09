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

namespace SpiceCRM\modules\SystemUI;

class SpiceUIConfLoader
{
    public $sysuitables = array();

    public $loader;
    public $routebase = "";
    public $release = true;


    private $conftables = array(
        'sysmodules',
        'sysmodulefilters',
        'sysuiactionsetitems',
        'sysuiactionsets',
        'sysuiadmincomponents',
        'sysuicomponentdefaultconf',
        'sysuicomponentmoduleconf',
        'sysuicomponentsets',
        'sysuicomponentsetscomponents',
        'sysuicopyrules',
        'sysuidashboarddashlets',
        'sysuifieldsets',
        'sysuifieldsetsitems',
        'sysuifieldtypemapping',
        'sysuilibs',
        'sysuiloadtasks',
        'sysuiloadtaskitems',
        'sysuimodulerepository',
        'sysuiobjectrepository',
        'sysuirolemodules',
        'sysuiroles',
        'sysuiroutes',
        'syshooks'
    );

    /**
     * SpiceUIConfLoader constructor.
     * @param null $endpoint introduced with CR1000133
     */
    public function __construct($endpoint = null)
    {
        global $current_user;
        $this->loader = new SpiceUILoader($endpoint);

//BEGIN CR1000133 multiple packageloader sources:
// routebase not needed anymore but keep a while for BWC
        $this->routebase = $this->getRouteBase();

        if(empty($endpoint)){
            if (!preg_match('/release/', $this->routebase))
                $this->release = false;

        }else{
            if (!preg_match('/packages.spicecrm.io/', $this->endpoint.$this->routebase))
                $this->release = false;
        }
//END
    }


    /**
     * @deprecated since release 201902001
     * @return string
     */
    public function getRouteBase()
    {
        $routebase = $this->loader->getRouteBase();
        return $routebase . "config";
    }

    /**
     * Display load language form
     * in SpiceCRM Backend Administration
     * @return string
     */
    public function displayDefaultConfForm($params, $possibleparams, $obsoleteparams)
    {
        $sm = new \Sugar_Smarty();

        if (!empty($params['packages'])) $sm->assign('currentpackages', $params['packages']);
        if (!empty($params['versions'])) $sm->assign('currentversions', array_unique($params['versions']));

        if (!empty($possibleparams['packages'])) $sm->assign('possiblepackages', $possibleparams['packages']);
        if (!empty($possibleparams['versions'])) $sm->assign('possibleversions', $possibleparams['versions']);
        if (!empty($obsoleteparams)) $sm->assign('obsoletepackages', $obsoleteparams);

        //check on release
        $sm->assign("release", $this->release);

        //check on running change request
        $sm->assign("hasOpenChangeRequest", $this->loader->hasOpenChangeRequest());

        return $sm->display("modules/Administration/templates/UIDefault.tpl");
    }

    /**
     * retrieve table column names
     * @param $tb
     * @return array
     */
    public function getTableColumns($tb)
    {
        $columns = $GLOBALS['db']->get_columns($tb);
        $cols = array();
        foreach ($columns as $c => $col) {
            $cols[] = $col['name'];
        }
        return $cols;
    }

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function getPossibleConf()
    {
        //get data
        if (!$response = $this->loader->callMethod("GET", $this->routebase)) {
            die('<pre>' . print_r($response, true));
            throw new \Exception("REST Call error somewhere... Action aborted");
        }

        //check if release and force unique version number
        if ($this->release === true) {
            $response['versions'] = array();
            $response['versions'][0]['version'] = $GLOBALS['sugar_version'];
        }

        array_multisort($response['versions'], SORT_DESC, SORT_STRING);

        return $response;
    }

    public function loadPackage($package, $version = '*')
    {
        $endpoint = implode("/", array('config', $package, $version));
        return $this->loadDefaultConf($endpoint, array('route' => $this->routebase, 'packages' => [$package], 'version' => $version), false);
    }

    public function deletePackage($package)
    {
        global $db;
        foreach ($this->conftables as $conftable){
            $db->query("DELETE FROM $conftable WHERE package = '$package'");
        }
    }

    /**
     * load sysui config from reference database
     * get column name for each table
     * make a select passing the column names
     * create insert queries.
     * @param $route
     * @param $params
     */
    public function loadDefaultConf($routeparams, $params, $checkopen = true)
    {
        global $sugar_config;
        $tables = array();
        $truncates = array();
        $inserts = array();

        if ($checkopen && $this->loader->hasOpenChangeRequest()) {
            $errormsg = "Open Change Requests found! They would be erased...";
            throw new \Exception($errormsg);
        }
        //get data
        if (!$response = $this->loader->callMethod("GET", $routeparams)) {
            $errormsg = "REST Call error somewhere... Action aborted";
            throw new \Exception($errormsg);
        }

        $this->sysuitables = array_keys($response);

        if (!empty($response['nodata'])) {
            die($response['nodata']);
        }

        foreach ($response as $tb => $content) {
            //truncate command
            $tables[] = $tb;
            $thisCols = $this->getTableColumns($tb);
            switch ($tb) {
                case 'syslangs':
                case 'sysfts':
                    $delQ = "DELETE FROM $tb WHERE 1=1";//$GLOBALS['db']->truncateTableSQL($tb);
                    break;
                default:
                    if(array_search('package', $thisCols) !== false) {
                        $delQ = "DELETE FROM $tb WHERE package IN('" . implode("','", $params['packages']) . "') ";
                        //if (in_array($params['packages'][0], $params['packages']))
                        $delQ .= "OR package IS NULL OR package=''";
                    }
            }

            $truncates[] = $delQ;
            $tbColCheck = false;

            foreach ($content as $id => $encoded) {
                if (!$decodeData = json_decode(base64_decode($encoded), true))
                    die("Error decoding data: " . json_last_error_msg() .
                        "<br/>Reference table = $tb" .
                        "<br/>Action aborted");

                //compare table column names
                if (!$tbColCheck) {
                    $referenceCols = array_keys($decodeData);
                    if (!empty(array_diff($referenceCols, $thisCols))) {
                        die("Table structure for $tb is not up-to-date." .
                            "<br/>Reference table = " . implode(", ", $referenceCols) .
                            "<br/>Client table = " . implode(", ", $thisCols) .
                            "<br/>Action aborted");
                    }
                    $tbColCheck = true;
                }

                //prepare values for DB query
                foreach ($decodeData as $key => $value) {
                    $decodeData[$key] = (is_null($value) || $value === "" ? "NULL" : "'" . $GLOBALS['db']->quote($value) . "'");
                }
                //insert command
                $inserts[] = "INSERT INTO $tb (" . implode(",", $referenceCols) . ") " .
                    "VALUES(" . implode(",", array_values($decodeData)) . ")";
            }
        }

        //if no inserts where created => abort
        if (count($inserts) < 1) {
            throw new \Exception("No inserts found. Action aborted.");
        }

        $queries = array_merge($truncates, $inserts);
//        echo '<pre>'. print_r(implode(";\n", $queries), true);die();

        //process queries
        if (count($queries) > 2) {
            $errors = array();
            foreach ($queries as $q) {
                if (!$GLOBALS['db']->query($q))
                    $errors[] = "Error: " . $GLOBALS['db']->last_error. (preg_match("/Duplicate entry/", $GLOBALS['db']->last_error) ? " PACKAGE NAME might have changed. Delete duplicate entries manually and reload config." : "");
            }
        }

        if (count($errors) <= 0) $success = true;

        return array("success" => $success, "queries" => count($queries), "errors" => $errors, "tables" => $tables);

    }


    /**
     * Remove sysmodules entries for modules that are not present in backend
     * @return bool
     */
    public function cleanDefaultConf()
    {
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
        if (isset($GLOBALS['moduleList'])) {
            foreach ($sysmodules as $sysmodule) {
                if (!in_array($sysmodule, $GLOBALS['moduleList'])) {
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
    public function getCurrentConf()
    {
        global $db;
        $qArray = [];
        foreach($this->conftables as $conftable) $qArray[] = "(SELECT package, version FROM $conftable WHERE version is not null AND version <> '')";
        $q = implode(" UNION ", $qArray) . " ORDER BY package, version";
        $res = $db->query($q);
        $packages = array();
        $versions = array();

        while ($row = $db->fetchByAssoc($res)) {
            if (!empty($row['package']) && !in_array($row['package'], $packages)) {
                $packages[] = $row['package'];
            } elseif (!in_array('core', $packages) && !in_array($row['package'], $packages)) {
                $packages[] = 'core';
            }
            if (!empty($row['version']) && !in_array($row['version'], $versions))
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
    public function getObsoleteConf($currentconf, $possibleconf)
    {
        $currentpackages = $currentconf['packages'];
        $possiblepackages = array();
        $obsoletepackages = array();
        foreach ($possibleconf['packages'] as $package) {
            $possiblepackages[] = $package['package'];
        }
        foreach ($currentpackages as $package)
            if (!in_array($package, $possiblepackages))
                $obsoletepackages[] = $package;
        return $obsoletepackages;
    }
}
