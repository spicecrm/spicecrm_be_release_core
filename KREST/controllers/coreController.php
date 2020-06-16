<?php
namespace SpiceCRM\KREST\controllers;

use function Composer\Autoload\includeFile;

class coreController{
    /**
     * Simple Test that echos a JSON for GET
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    function testGet($req, $res, $args){
        return $res->withJson(['test' => true, 'viaMethod' => 'GET']);
    }

    /**
     * Simple Test that echos a JSON for POST
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    function testPost($req, $res, $args){
        return $res->withJson(['test' => true, 'viaMethod' => 'POST']);
    }

    /**
     * helper to generate a GUID
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    function generateGuid($req, $res, $args){
        return $res->withJson(['id' => create_guid()]);
    }

    /**
     * returns a list of all loaded extensions
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    function getExtensions($req, $res, $args){
        require_once('KREST/KRESTManager.php');
        $KRESTManager = new \KRESTManager();
        return $res->withJson([
            'version' => '2.0',
            'extensions' => $KRESTManager->extensions
        ]);
    }

    /**
     * returns general system information
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    function getSysinfo($req, $res, $args){
        global $sugar_config, $KRESTManager;

        if (isset($GLOBALS['sugar_config']['syslanguages']['spiceuisource']) && $GLOBALS['sugar_config']['syslanguages']['spiceuisource'] == 'db') {
            if (!class_exists('LanguageManager')) require_once 'include/SugarObjects/LanguageManager.php';
            $languages = \LanguageManager::getLanguages(true);
        } else {

            foreach ($GLOBALS['sugar_config']['languages'] as $language_code => $language_name) {
                $languages['available'][] = [
                    'language_code' => $language_code,
                    'language_name' => $language_name,
                    'system_language' => true,
                    'communication_language' => true
                ];
            }
            $languages['default'] = $GLOBALS['sugar_config']['default_language'];
        }

        return $res->withJson(array(
            'version' => '2.0',
            'systemsettings' => [
                'upload_maxsize' => $sugar_config['upload_maxsize'],
                'enableSettingUserPrefsByAdmin' => isset( $sugar_config['enableSettingUserPrefsByAdmin'] ) ? (boolean)@$sugar_config['enableSettingUserPrefsByAdmin'] : false
            ],
            'extensions' => $KRESTManager->extensions,
            'languages' => $languages,
            'elastic' => \SpiceCRM\includes\SpiceFTSManager\SpiceFTSUtils::checkElastic(),
            'socket_frontend' => $sugar_config['core']['socket_frontend'],
            'loginSidebarUrl' => isset ($sugar_config['uiLoginSidebarUrl']{0}) ? $sugar_config['uiLoginSidebarUrl'] : false,
            'ChangeRequestRequired' => isset($GLOBALS['sugar_config']['change_request_required']) ? (boolean)$GLOBALS['sugar_config']['change_request_required'] : false,
            'sessionMaxLifetime' => (int)ini_get('session.gc_maxlifetime'),
            'unique_key' => $sugar_config['unique_key'],
            'name' => $sugar_config['system']['name']
        ));
    }

    /**
     * writes the http errors to the log .. this is called from teh UI when a http error occurs on the client .. the client will
     * after a certain time retry and call the logger for http errors
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    function postHttpErrors ($req, $res, $args){
        $errors = $req->getParsedBodyParam('errors');
        $logtext = '';
        $now = date('c');
        foreach ($errors as $error) $logtext .= $now . "\n" . var_export($error, true) . "\n------------------------------\n";
        $ret = file_put_contents('ui_http_network_errors.log', $logtext, FILE_APPEND);
        return $res->withJson(['success' => $ret !== false]);
    }

    /**
     * stores a tmp file for the proxy handling of the FILES in PHP
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    function storeTmpFile ($req, $res, $args){
        $postBody = file_get_contents('php://input');
        $temppath = sys_get_temp_dir();
        $filename = create_guid();
        file_put_contents($temppath . '/' . $filename, base64_decode($postBody));
        return $res->withJson(['filepath' => $temppath . '/' . $filename]);
    }

    /**
     * @deprecated
     *
     * gets the backend timezones
     *
     * @param $req
     * @param $res
     * @param $args
     * @return mixed
     */
    function getTimeZones ($req, $res, $args){
        return $res->withJson(\TimeDate::getTimezoneList());
    }


    function getLanguage($req, $res, $args){

        // get the requested language
        $language = $args['language'];

        // see if we have a language passed in .. if not use the default
        if (empty($language)) $language = $GLOBALS['sugar_config']['default_language'];

        $appStrings = return_app_list_strings_language($language);

        if (!class_exists('LanguageManager')) require_once 'include/SugarObjects/LanguageManager.php';

        $syslanguagelabels = \LanguageManager::loadDatabaseLanguage($language);
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

        $responseArray = array(
            'languages' => \LanguageManager::getLanguages(),
            'applang' => $syslanguages,
            'applist' => $appStrings
        );

        $responseArray['md5'] = md5(json_encode($responseArray));

        // if an md5 was sent in and matches the current one .. no change .. do not send the language to save bandwidth
        if ($_REQUEST['md5'] === $responseArray['md5']) {
            $responseArray = array('md5' => $_REQUEST['md5']);
        }

        return $res->withJson($responseArray);
    }

    function getPortalGDPRagreementText() {
        1;
    }

}
