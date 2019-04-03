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
 * Administration
 * display form <> load config
 */

if ($current_user->is_admin) {
    require_once 'modules/SystemUI/SpiceUIConfLoader.php';
//    $loader = new SpiceUIConfLoader();
    $loader = new \SpiceCRM\modules\SystemUI\SpiceUIConfLoader();
    if ($_POST['uidefaultconf_process'] > 0) {
        if(empty($_POST['packages']) || empty($_POST['versions'])){
            die('Missing Parameters. Please Check Package and version.');
        }
        //collect values for REST call
        //https://packages.spicecrm.io/referenceconfig/*/2018.02.001
        $packages = $_POST['packages'];
        $versions = $_POST['versions'];

        //BEGIN CR1000133 multiple packageloader sources
        global $sugar_config;
//        //ensure backward compatibility
//        $routebase = $loader->routebase;
//        if(!isset($sugar_config['packageloader']['sources']) || empty($sugar_config['packageloader']['sources'])) {
//            $routebase = $loader->routebase;
//            $routeparams = implode("/", array($routebase, implode(",", $packages), implode(",", $versions)));
//        }
//        else {
//            $routeparams = implode("/", array($routebase, implode(",", $packages), implode(",", $versions)));
//        }
        //UNDO CR1000133: route is caught somewhere else
        $routebase = $loader->routebase;
        $routeparams = implode("/", array($routebase, implode(",", $packages), implode(",", $versions)));

        //END

        $results = $loader->loadDefaultConf($routeparams, array('packages' => $packages, 'versions' => $versions));
        $loader->cleanDefaultConf();

        echo "<br>Modified tables: <br>".implode("<br>", $results['tables']);
        echo "<br>Success: ".(!$results['success'] ? "NO" : "yes");
        echo "<br>Processed " . ($results['queries'] - count($results['errors'])) . " queries out of " . ($results['queries']) . "\n";
        if(count($results['errors']) > 0)
            echo "<br>Encountered errors: <br>".implode("<br>", $results['errors']);

    } else {
        //get current config
        $currentconf = $loader->getCurrentConf();
        //get possible conf
        $possibleconf = $loader->getPossibleConf();
        //since release 2019.01.001: only core package shall be loadable from backend
        //this is to simplify UI installation. Additional config packages are loadable from UI admin interface
        foreach($possibleconf['packages'] as $idx => $package){
            if($package['package'] != "core") unset($possibleconf['packages'][$idx]);
        }
        //re-index
        $possibleconf['packages'] = array_values($possibleconf['packages']);
        array_multisort($possibleconf['versions'], SORT_DESC);

        //obsolete conf
        //removed for release 2019.01.001
        //$obsoleteconf = $loader->getObsoleteConf($currentconf, $possibleconf);
        $obsoleteconf = array();


        //display form
        $loader->displayDefaultConfForm($currentconf, $possibleconf, $obsoleteconf);
    }
}
else{
    die("Access denied");
}