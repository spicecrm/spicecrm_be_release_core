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
 * Class SpiceLanguageLoader
 * Utility class for SpiceCRM backend
 * get language labels and translations from reference database
 * Database credentials are located in config.php
 */
require_once 'modules/SystemUI/SpiceUILoader.php';

class SpiceLanguageLoader{

    public $loader;
    public $routebase = "";
    public $release = true;

    public function __construct(){
        $this->loader = new SpiceUILoader();
        $this->routebase = $this->getRouteBase();

        if(!preg_match('/release/', $this->routebase))
            $this->release = false;
    }


    public function getRouteBase(){
        $routebase = $this->loader->getRouteBase();
        return $routebase."language";
    }
    public function getRouteBaseConfig(){
        $routebase = $this->loader->getRouteBase();
        return $routebase."config";
    }


    /**
     * Display load language form in SpiceCRM Backend Administration
     */
    public function displayDefaultConfForm($params, $possibleparams, $obsoleteprams){
        $sm = new Sugar_Smarty();
        //current config
        if(!empty($params['languages'])) $sm->assign('currentlanguages', $params['languages']);
        if(!empty($params['packages'])) $sm->assign('currentpackages', $params['packages']);
        if(!empty($params['versions'])) $sm->assign('currentversions', $params['versions']);

        //for settings
        if(!empty($possibleparams['languages'])) $sm->assign('possiblelanguages', $possibleparams['languages']);
        if(!empty($possibleparams['packages'])) $sm->assign('possiblepackages', $possibleparams['packages']);
        if(!empty($possibleparams['versions'])) $sm->assign('possibleversions', $possibleparams['versions']);
        if(!empty($obsoleteprams)) $sm->assign('obsoletepackages', $obsoleteprams);

        //check on release
        $sm->assign("release", $this->release);

        //check on running change request
        $sm->assign("hasOpenChangeRequest", $this->loader->hasOpenChangeRequest());

        return $sm->display("modules/Administration/templates/UILanguage.tpl");
    }

    /**
     * @param $params
     */
    public function deleteOldRecords($params){
        $queries = array();
        //get tables information
        $tb_labels = 'syslanguagelabels';
//        $tb_labels_cols = array();
//        foreach($GLOBALS['dictionary']['syslanguagelabels']['fields'] as $id => $field){
//            if($field['type'] != 'link' && $field['type'] != 'relate'){
//                $tb_labels_cols[] = $field['name'];
//            }
//        }

        $tb_trans = 'syslanguagetranslations';
//        $tb_trans_cols = array();
//        foreach($GLOBALS['dictionary']['syslanguagelabels']['fields'] as $id => $field){
//            if($field['type'] != 'link' && $field['type'] != 'relate'){
//                $tb_trans_cols[] = $field['name'];
//            }
//        }
        //delete old syslanguagelabels
        $delQ = "DELETE FROM $tb_labels WHERE package IN('".implode("','", $params['packages'])."') ";
        if(in_array('core', $params['packages']))
            $delQ.= "OR package IS NULL OR package=''";
        $queries[] = $delQ;
        //delete old translations for selected language: translations without the label since delete above
        $queries[] = "DELETE $tb_trans.* FROM $tb_trans LEFT JOIN $tb_labels ON $tb_labels.id = $tb_trans.syslanguagelabel_id 
        WHERE $tb_labels.id IS NULL AND $tb_trans.syslanguage IN('".implode("','", $params['languages'])."')";

//        echo '<pre>'.print_r($deletes, true);

        $errors = array();
        foreach ($queries as $q) {
            if(!$GLOBALS['db']->query($q)) {
                $errors[] = 'Error with query: ' . $q . " " . $GLOBALS['db']->last_error;
            }
        }

        if(count($errors) > 0)
            die(implode('<br>', $errors));
//        die(implode('<br>', $queries));

    }

    public function loadLanguage($language){

        global $db;

        // $route = "referencelanguage";
        $package = '*';

        $endpoint = implode("/", array($this->routebase, $language, $package, '*'));
        $results = $this->loadDefaultConf($endpoint, array('route' => $this->routebase, 'languages' => $language, 'package' => '*', 'version' => '*'), false);
        return $results;
    }

    public function deleteLanguage($language){

        global $db;

        // remove Translations
        $db->query("DELETE FROM syslanguagetranslations WHERE syslanguage='$language'");

        // set as non system language
        $db->query("UPDATE syslangs SET system_language = 0, is_default = 0 WHERE language_code = '$language'");

        return true;
    }

    /**
     * load language labels and translations from reference database
     * @param $route
     * @param $params
     */
    public function loadDefaultConf($route, $params, $checkopen = true){
        global $sugar_config;
        $truncates = array();
        $inserts = array();
        $success = false;

        if($checkopen && $this->loader->hasOpenChangeRequest())
            throw new Exception("Open Change Requests found! They would be erased...");

        if(!$response = $this->loader->callMethod("GET", $route)){
            //die("REST Call error somewhere... Action aborted");
            throw new Exception("REST Call error somewhere... Action aborted");
        }

        // reponse looks like
        //Array(
        //    [0] => {"id":"fc9701db-dacb-46b1-a10d-90e182783b39","name":"LBL_PRECONDITION","translation_default":"Voraussetzung","translation_short":"","translation_long":""}
        //    [1] => {"id":"f8ef0006-cea9-4c17-babe-38db5be5c99a","name":"MSG_INPUT_REQUIRED","translation_default":"Eingabe ist erforderlich","translation_short":"","translation_long":""}
        //)
        //id is label ID
        //name is label name
        //translation_default _short _long is tranlation for label
        //get tables information
        $tb_labels = 'syslanguagelabels';
        $tb_trans = 'syslanguagetranslations';

        //delete old syslanguagelabels
        $langs = explode(',', $params['languages']);
        $version = $params['version'];
        if(empty($version)) $version = '*';
        if($version == '*')
            $delQ = "TRUNCATE TABLE $tb_labels";
        else {
            $delQ = "DELETE FROM $tb_labels WHERE (`version` ='{$version}' OR `version` IS NULL OR `version` = '')";
        }
        $truncates[] = $delQ;

        //delete old translations for selected language: translations without the label since delete above
        $languages = explode(',', $params['languages']);
        $truncates[] = "DELETE $tb_trans.* FROM $tb_trans LEFT JOIN $tb_labels ON $tb_labels.id = $tb_trans.syslanguagelabel_id 
        WHERE $tb_labels.id IS NULL AND $tb_trans.syslanguage IN ('".implode("','", $languages)."')";

        $labelIDs = [];

        foreach($response as $index => $content) {
            $decodeData = json_decode($content, true);
            $tbColCheck = false;
            //insert command label
            if(!in_array($decodeData['id'], $labelIDs)) {
                $inserts[] = "INSERT INTO $tb_labels (id, name, version, package) " .
                    "VALUES ( '" . $decodeData['id'] . "', '" . $decodeData['name'] . "', '" . $decodeData['version'] . "', " . (!empty($decodeData['package']) && $decodeData['package'] != "*" ? "'" . $decodeData['package'] . "'" : "NULL") . ")";
                $labelIDs[] = $decodeData['id'];
            }
            //insert command translation
            $translabel_id = create_guid();
            $inserts[] = "INSERT INTO $tb_trans ".
                "(id, syslanguagelabel_id, syslanguage, translation_default, translation_short, translation_long) ".
                "VALUES ('".$translabel_id."', '".$decodeData['id']."', '".$decodeData['syslanguage']."', ".
                (!empty($decodeData['translation_default'])  ? "'".$GLOBALS['db']->quote($decodeData['translation_default'])."'" : "NULL").", ".
                (!empty($decodeData['translation_short'])  ? "'".$GLOBALS['db']->quote($decodeData['translation_short'])."'" : "NULL").", ".
                (!empty($decodeData['translation_long'])  ? "'".$GLOBALS['db']->quote($decodeData['translation_long'])."'" : "NULL").")";
        }

        //if no inserts where created => abort
        if(count($inserts) < 1){
            //die("No inserts found. Action aborted.");
            throw new Exception("REST Call error somewhere... Action aborted");
        }

        $queries = array_merge($truncates, $inserts);
//        echo '<pre>'. print_r(implode(";\n",$queries), true);

        //process queries
        if(count($queries) > 2) {
            $errors = array();
            foreach ($queries as $q) {
                if(!$GLOBALS['db']->query($q))
                    $errors[] = 'Error with query: '.$GLOBALS['db']->last_error;
            }
        }

        if(count($errors) > 0){
            $GLOBALS['log']->fatal(__CLASS__."::".__FUNCTION__."() Errors:".print_r($errors, true));
        }
        else {
            $success = true;
            //check if language is available
            $queriessyslang = array();
            $syslangstatus = $GLOBALS['db']->query("SELECT * from syslangs 
                  WHERE language_code IN ('".implode("','", $languages)."') 
                  ORDER BY sort_sequence ASC");
            while($syslangrow = $GLOBALS['db']->fetchByAssoc($syslangstatus)){
                $syslangs[$syslangrow['language_code']] = $syslangrow;
            }
            for($i = 0; $i < count($languages); $i++){


                if(in_array($languages[$i], array_keys($syslangs))){
                    $queriessyslang[] = "UPDATE syslangs SET system_language = 1 WHERE language_code='".$languages[$i]."'";
                    $syslangs[$languages[$i]]['system_language'] = 1;
                } else{
                    //try to find a language name
                    $appForLang = return_app_list_strings_language($languages[$i]);
                    $lang = array("id" => create_guid(),
                        "language_code" => $languages[$i],
                        "language_name" => (!empty($appForLang['language_pack_name']) ? $appForLang['language_pack_name'] : $languages[$i]),
                        "sort_sequence" => $i+1,
                        "is_default" => 0,
                        "system_language" => 1,
                        "communication_language" => 1
                    );
                    $values = "'".$lang['id']."','".$lang['language_code']."', '".$lang['language_name']."', ".$lang['sort_sequence'].", ".$lang['is_default'].", ".$lang['system_language'].", ".$lang['communication_language'];
                    $queriessyslang[] = "INSERT INTO syslangs (".implode(",", array_keys($lang)).") VALUES(".$values.")";

                    // add to syslangs array to return fromt he call
                    $syslangs[$languages[$i]] = $lang;
                }
            }

            // update syslanguages
            foreach($queriessyslang as $q) {
                if (!$GLOBALS['db']->query($q)) {
                    $success = false;
                    if(!_is_array($errors)) $errors = array();
                    $errors[] = 'Error with query: '.$GLOBALS['db']->last_error;
                }
            }
        }

        return array("success" => $success, "queries" => count($queries), "languages" => $syslangs,  "errors" => $errors);
    }

    public function getPossibleConf(){
        //get data
        $route = $this->getRouteBaseConfig();
        if(!$response = $this->loader->callMethod("GET", $route)) {
            throw new Exception("REST Call error somewhere... Action aborted");
        }

        //check if release and force unique version number
        if($this->release === true){
            $response['versions'][0]['version'] = $GLOBALS['sugar_version'];
        }

        return $response;
    }

    /**
     * Get main information about current languages loaded in client
     * package, version....
     */
    public function getCurrentConf(){
        global $db;
        $q = "SELECT trans.syslanguage, lbl.package, lbl.version 
        FROM syslanguagelabels lbl
        INNER JOIN syslanguagetranslations trans ON trans.syslanguagelabel_id = lbl.id
        ORDER BY trans.syslanguage, lbl.package, lbl.version";
        $res = $db->query($q);
        $languages = array();
        $packages = array();
        $versions = array();

        while($row = $db->fetchByAssoc($res)){
            if(!empty($row['syslanguage']) && !in_array( $row['syslanguage'], $languages)) $languages[] = $row['syslanguage'];
            if(!empty($row['package'])  && !in_array( $row['package'], $packages)) $packages[] = $row['package'];
            if(!empty($row['version'])  && !in_array( $row['version'], $versions)) $versions[] = $row['version'];
        }
        return array('languages' => $languages, 'packages' => $packages, 'versions' => $versions);
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
        $obsoletepackages = array();

        foreach($possibleconf['packages'] as $package){
            $possiblepackages[] = $package['package'];
        }

        foreach($currentpackages as $package)
            if(!in_array($package,$possiblepackages))
                $obsoletepackages[] = $package;

        return $obsoletepackages;
    }
}