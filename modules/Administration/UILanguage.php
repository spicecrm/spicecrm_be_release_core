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
if (! defined ( 'sugarEntry' ) || ! sugarEntry)
    die ( 'Not A Valid Entry Point' ) ;
/**
 * delete current content in syslanguage* tables
 * insert default Languages
 */
if ($current_user->is_admin) {
    require_once 'modules/SystemLanguages/SpiceLanguageLoader.php';
    $loader = new \SpiceCRM\modules\SystemLanguages\SpiceLanguageLoader();

    if ($_POST['uilanguageconf_process'] > 0) {

        if(empty($_POST['packages']) || empty($_POST['version']) || empty($_POST['languages'])){
            die('Missing Parameters. Please Check package, version and language parameters.');
        }
        //collect values for REST call
        //https://packages.spicecrm.io/referencelanguage/en_us/*/2018.02.001
        $packages = $_POST['packages'];
        $version = $_POST['version'];
        $languages = $_POST['languages'];

        //delete Old stuff
        //$loader->deleteOldRecords(array('packages' => $packages, 'languages' => $languages));

        //load fresh stuff
        //foreach($languages as $language) {
//            $routeparams = implode("/", array($loader->routebase, implode(",", $languages), implode(",", $packages), '*'));
//            $results = $loader->loadDefaultConf($routeparams, array('languages' => implode(',', $languages), 'packages' => $packages, 'version' => implode("','", $version)));
            $results = $loader->loadLanguage(implode(',', $languages));
            echo "<br>Loaded language labels for " . implode(", ",$languages);
            echo "<br>Packages: " . implode(", ",$packages);
            echo "<br>Version: " . implode(", ",$version);
            echo "<br>Success: " . (!$results['success'] ? "NO" : "yes");
            echo "<br>Processed " . ($results['queries'] - count($results['errors'])) . " queries out of " . ($results['queries']);
            if (count($results['errors']) > 0)
                echo "<br>Encountered errors: <br>" . implode("<br>", $results['errors']);
            echo "<br>---------------";
        //}
    } else {
        //get current config
        $currentconf = $loader->getCurrentConf();
        //get possible conf
        $possibleconf = $loader->getPossibleConf();
        //get obsolete conf
        $obsoleteconf = $loader->getObsoleteConf($currentconf, $possibleconf);

        //display form
        $loader->displayDefaultConfForm($currentconf, $possibleconf, $obsoleteconf);
    }
}
else{
    die("Access denied");
}
