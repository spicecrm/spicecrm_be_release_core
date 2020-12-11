<?php
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
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
********************************************************************************/

/*
 * Created on Mar 21, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
//require_once('include/MVC/Controller/ControllerFactory.php');
//require_once('include/MVC/View/ViewFactory.php');

/**
 * SugarCRM application
 * @api
 */
class SugarApplication
{
    var $controller = null;
    var $headerDisplayed = false;
    var $default_module = 'Administration';
    var $default_action = 'index';

    /**
     * Perform execution of the application. This method is called from index2.php
     */
    function execute(){
        global $sugar_config;
// only Administration
//        if(!empty($sugar_config['default_module']))
//            $this->default_module = $sugar_config['default_module'];
        $module = $this->default_module;
        if(!empty($_REQUEST['module']))$module = $_REQUEST['module'];
        header('Content-Type: text/html; charset=UTF-8');
        $this->setupPrint();
        $this->controller = ControllerFactory::getController($module);
        // If the entry point is defined to not need auth, then don't authenticate.
        if( empty($_REQUEST['entryPoint'])
            || $this->controller->checkEntryPointRequiresAuth($_REQUEST['entryPoint']) ){
            $this->loadUser();

            $this->ACLFilter();
            $this->preProcess();
            $this->controller->preProcess();
            $this->checkHTTPReferer();
        }

        SugarThemeRegistry::buildRegistry();
        $this->loadLanguages();
        //begin deprecated in SpiceCRM
        //$this->checkDatabaseVersion();
        //end
        $this->loadDisplaySettings();
        $this->loadLicense();
        $this->loadGlobals();
//        $this->setupResourceManagement($module);
        $this->controller->execute();
        sugar_cleanup();
    }

    /**
     * Load the config
     */
    static function loadConfig(){
        global $db, $sugar_config;
        $configEntries = $db->query("SELECT * FROM config");
        while($configEntry = $db->fetchByAssoc($configEntries)){
            $sugar_config[$configEntry['category']][$configEntry['name']] = $configEntry['value'];
        }

    }

    /**
     * Load the authenticated user. If there is not an authenticated user then redirect to login screen.
     */
    function loadUser(){
        global $authController, $sugar_config;
        // Double check the server's unique key is in the session.  Make sure this is not an attempt to hijack a session
        $user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : '';
        $server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : '';
        $allowed_actions = (!empty($this->controller->allowed_actions)) ? $this->controller->allowed_actions : $allowed_actions = array('Authenticate', 'Login', 'LoggedOut');

        $authController = new AuthenticationController();

        if(($user_unique_key != $server_unique_key) && (!in_array($this->controller->action, $allowed_actions)) &&
            (!isset($_SESSION['login_error'])))
        {
            session_destroy();

            if(!empty($this->controller->action)){
                if(strtolower($this->controller->action) == 'delete')
                    $this->controller->action = 'DetailView';
                elseif(strtolower($this->controller->action) == 'save')
                    $this->controller->action = 'EditView';
                elseif(strtolower($this->controller->action) == 'quickcreate') {
                    $this->controller->action = 'index';
                    $this->controller->module = 'home';
                }
                elseif(isset($_REQUEST['massupdate'])|| isset($_GET['massupdate']) || isset($_POST['massupdate']))
                    $this->controller->action = 'index';
                elseif($this->isModifyAction())
                    $this->controller->action = 'index';
                elseif ($this->controller->action == $this->default_action
                    && $this->controller->module == $this->default_module) {
                    $this->controller->action = '';
                    $this->controller->module = '';
                }
            }

            $authController->authController->redirectToLogin($this);
        }

        $GLOBALS['current_user'] = new User();
        if(isset($_SESSION['authenticated_user_id'])){
            // set in modules/Users/Authenticate.php
            if(!$authController->sessionAuthenticate()){
                // if the object we get back is null for some reason, this will break - like user prefs are corrupted
                $GLOBALS['log']->fatal('User retrieval for ID: ('.$_SESSION['authenticated_user_id'].') does not exist in database or retrieval failed catastrophically.  Calling session_destroy() and sending user to Login page.');
                session_destroy();
                SugarApplication::redirect('index.php?action=Login&module=Users');
                die();
            }//fi
        }elseif(!($this->controller->module == 'Users' && in_array($this->controller->action, $allowed_actions))){
            session_destroy();
            SugarApplication::redirect('index.php?action=Login&module=Users');
            die();
        }
        $GLOBALS['log']->debug('Current user is: '.$GLOBALS['current_user']->user_name);

        //set cookies
        if(isset($_SESSION['authenticated_user_id'])){
            $GLOBALS['log']->debug("setting cookie ck_login_id_20 to ".$_SESSION['authenticated_user_id']);
            self::setCookie('ck_login_id_20', $_SESSION['authenticated_user_id'], time() + 86400 * 90);
        }
        if(isset($_SESSION['authenticated_user_theme'])){
            $GLOBALS['log']->debug("setting cookie ck_login_theme_20 to ".$_SESSION['authenticated_user_theme']);
            self::setCookie('ck_login_theme_20', $_SESSION['authenticated_user_theme'], time() + 86400 * 90);
        }
        if(isset($_SESSION['authenticated_user_theme_color'])){
            $GLOBALS['log']->debug("setting cookie ck_login_theme_color_20 to ".$_SESSION['authenticated_user_theme_color']);
            self::setCookie('ck_login_theme_color_20', $_SESSION['authenticated_user_theme_color'], time() + 86400 * 90);
        }
        if(isset($_SESSION['authenticated_user_theme_font'])){
            $GLOBALS['log']->debug("setting cookie ck_login_theme_font_20 to ".$_SESSION['authenticated_user_theme_font']);
            self::setCookie('ck_login_theme_font_20', $_SESSION['authenticated_user_theme_font'], time() + 86400 * 90);
        }
        if(isset($_SESSION['authenticated_user_language'])){
            $GLOBALS['log']->debug("setting cookie ck_login_language_20 to ".$_SESSION['authenticated_user_language']);
            self::setCookie('ck_login_language_20', $_SESSION['authenticated_user_language'], time() + 86400 * 90);
        }
        //check if user can access

    }

    function ACLFilter(){
        $GLOBALS['ACLController']->filterModuleList($GLOBALS['moduleList']);
    }

    /**
     * setupResourceManagement
     * This function initialize the ResourceManager and calls the setup method
     * on the ResourceManager instance.
     *
     */
//    function setupResourceManagement($module) {
//        require_once('include/resource/ResourceManager.php');
//        $resourceManager = ResourceManager::getInstance();
//        $resourceManager->setup($module);
//    }

    function setupPrint() {
        $GLOBALS['request_string'] = '';

        // merge _GET and _POST, but keep the results local
        // this handles the issues where values come in one way or the other
        // without affecting the main super globals
        $merged = array_merge($_GET, $_POST);
        foreach ($merged as $key => $val)
        {
            if(is_array($val))
            {
                foreach ($val as $k => $v)
                {
                    //If an array, then skip the urlencoding. This should be handled with stringify instead.
                    if(is_array($v))
                        continue;

                    $GLOBALS['request_string'] .= urlencode($key).'['.$k.']='.urlencode($v).'&';
                }
            }
            else
            {
                $GLOBALS['request_string'] .= urlencode($key).'='.urlencode($val).'&';
            }
        }
        $GLOBALS['request_string'] .= 'print=true';
    }

    function preProcess(){
        $config = new Administration;
        $config->retrieveSettings();
        if(!empty($_SESSION['authenticated_user_id'])){
            if(isset($_SESSION['hasExpiredPassword']) && $_SESSION['hasExpiredPassword'] == '1'){
                if( $this->controller->action!= 'Save' && $this->controller->action != 'Logout') {
                    $this->controller->module = 'Users';
                    $this->controller->action = 'ChangePassword';
                    $record = $GLOBALS['current_user']->id;
                }else{
                    $this->handleOfflineClient();
                }
            }else{
                $ut = $GLOBALS['current_user']->getPreference('ut');
                if(empty($ut)
                    && $this->controller->action != 'AdminWizard'
                    && $this->controller->action != 'EmailUIAjax'
                    && $this->controller->action != 'Wizard'
                    && $this->controller->action != 'SaveAdminWizard'
                    && $this->controller->action != 'SaveUserWizard'
                    && $this->controller->action != 'SaveTimezone'
                    && $this->controller->action != 'Logout') {
                    $this->controller->module = 'Users';
                    $this->controller->action = 'SetTimezone';
                    $record = $GLOBALS['current_user']->id;
                }else{
                    if($this->controller->action != 'AdminWizard'
                        && $this->controller->action != 'EmailUIAjax'
                        && $this->controller->action != 'Wizard'
                        && $this->controller->action != 'SaveAdminWizard'
                        && $this->controller->action != 'SaveUserWizard'){
                        $this->handleOfflineClient();
                    }
                }
            }
        }
        $this->handleAccessControl();
    }

    function handleOfflineClient(){
        if(isset($GLOBALS['sugar_config']['disc_client']) && $GLOBALS['sugar_config']['disc_client']){
            if(isset($_REQUEST['action']) && $_REQUEST['action'] != 'SaveTimezone'){
                if (!file_exists('modules/Sync/file_config.php')){
                    if($_REQUEST['action'] != 'InitialSync' && $_REQUEST['action'] != 'Logout' &&
                        ($_REQUEST['action'] != 'Popup' && $_REQUEST['module'] != 'Sync')){
                        //echo $_REQUEST['action'];
                        //die();
                        $this->controller->module = 'Sync';
                        $this->controller->action = 'InitialSync';
                    }
                }else{
                    require_once ('modules/Sync/file_config.php');
                    if(isset($file_sync_info['is_first_sync']) && $file_sync_info['is_first_sync']){
                        if($_REQUEST['action'] != 'InitialSync' && $_REQUEST['action'] != 'Logout' &&
                            ( $_REQUEST['action'] != 'Popup' && $_REQUEST['module'] != 'Sync')){
                            $this->controller->module = 'Sync';
                            $this->controller->action = 'InitialSync';
                        }
                    }
                }
            }
            global $moduleList, $sugar_config, $sync_modules;
            require_once('modules/Sync/SyncController.php');
            $GLOBALS['current_user']->is_admin = '0'; //No admins for disc client
        }
    }

    /**
     * Handles everything related to authorization.
     */
    function handleAccessControl(){
        if($GLOBALS['current_user']->isDeveloperForAnyModule())
            return;
        if(!empty($_REQUEST['action']) && $_REQUEST['action']=="RetrieveEmail")
            return;
        if(!is_admin($GLOBALS['current_user']) && !empty($GLOBALS['adminOnlyList'][$this->controller->module])
            && !empty($GLOBALS['adminOnlyList'][$this->controller->module]['all'])
            && (empty($GLOBALS['adminOnlyList'][$this->controller->module][$this->controller->action]) || $GLOBALS['adminOnlyList'][$this->controller->module][$this->controller->action] != 'allow')) {
            $this->controller->hasAccess = false;
            return;
        }

        // Bug 20916 - Special case for check ACL access rights for Subpanel QuickCreates
        if(isset($_POST['action']) && $_POST['action'] == 'SubpanelCreates') {
            $actual_module = $_POST['target_module'];
            if(!empty($GLOBALS['modListHeader']) && !in_array($actual_module,$GLOBALS['modListHeader'])) {
                $this->controller->hasAccess = false;
            }
            return;
        }


        if(!empty($GLOBALS['current_user']) && empty($GLOBALS['modListHeader']))
            $GLOBALS['modListHeader'] = query_module_access_list($GLOBALS['current_user']);

        if(in_array($this->controller->module, $GLOBALS['modInvisList']) &&
            ((in_array('Activities', $GLOBALS['moduleList'])              &&
                    in_array('Calendar',$GLOBALS['moduleList']))                 &&
                in_array($this->controller->module, $GLOBALS['modInvisListActivities']))
        ){
            $this->controller->hasAccess = false;
            return;
        }
    }

    /**
     * Load only bare minimum of language that can be done before user init and MVC stuff
     */
    static function preLoadLanguages()
    {
        if(!empty($_SESSION['authenticated_user_language'])) {
            $GLOBALS['current_language'] = $_SESSION['authenticated_user_language'];
        }
        else {
            $GLOBALS['current_language'] = $GLOBALS['sugar_config']['default_language'];
        }
        $GLOBALS['log']->debug('current_language is: '.$GLOBALS['current_language']);
        //set module and application string arrays based upon selected language
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
    }

    /**
     * Load application wide languages as well as module based languages so they are accessible
     * from the module.
     */
    function loadLanguages(){
        if(!empty($_SESSION['authenticated_user_language'])) {
            $GLOBALS['current_language'] = $_SESSION['authenticated_user_language'];
        }
        else {
            $GLOBALS['current_language'] = $GLOBALS['sugar_config']['default_language'];
        }
        $GLOBALS['log']->debug('current_language is: '.$GLOBALS['current_language']);
        //set module and application string arrays based upon selected language
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
        if(empty($GLOBALS['current_user']->id))$GLOBALS['app_strings']['NTC_WELCOME'] = '';
        if(!empty($GLOBALS['system_config']->settings['system_name']))$GLOBALS['app_strings']['LBL_BROWSER_TITLE'] = $GLOBALS['system_config']->settings['system_name'];
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
        $GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], $this->controller->module);
    }

    /**
     * Load the themes/images.
     */
    function loadDisplaySettings()
    {
        global $theme;

        // load the user's default theme
        $theme = $GLOBALS['current_user']->getPreference('user_theme');

        if (is_null($theme)) {
            $theme = $GLOBALS['sugar_config']['default_theme'];
            if(!empty($_SESSION['authenticated_user_theme'])){
                $theme = $_SESSION['authenticated_user_theme'];
            }
            else if(!empty($_COOKIE['sugar_user_theme'])){
                $theme = $_COOKIE['sugar_user_theme'];
            }

            if(isset($_SESSION['authenticated_user_theme']) && $_SESSION['authenticated_user_theme'] != '') {
                $_SESSION['theme_changed'] = false;
            }
        }

        if(!is_null($theme) && !headers_sent())
        {
            // setcookie('sugar_user_theme', $theme, time() + 31536000); // expires in a year
        }

        SugarThemeRegistry::set($theme);
        require_once('include/utils/layout_utils.php');
        $GLOBALS['image_path'] = SugarThemeRegistry::current()->getImagePath().'/';
        if ( defined('TEMPLATE_URL') )
            $GLOBALS['image_path'] = TEMPLATE_URL . '/'. $GLOBALS['image_path'];

        if ( isset($GLOBALS['current_user']) ) {
            $GLOBALS['gridline'] = (int) ($GLOBALS['current_user']->getPreference('gridline') == 'on');
            $GLOBALS['current_user']->setPreference('user_theme', $theme, 0, 'global');
        }
    }

    function loadLicense(){
        loadLicense();
        global $user_unique_key, $server_unique_key;
        $user_unique_key = (isset($_SESSION['unique_key'])) ? $_SESSION['unique_key'] : '';
        $server_unique_key = (isset($sugar_config['unique_key'])) ? $sugar_config['unique_key'] : '';
    }

    function loadGlobals(){
        global $currentModule;
        $currentModule = $this->controller->module;
        if($this->controller->module == $this->default_module){
            $_REQUEST['module'] = $this->controller->module;
            if(empty($_REQUEST['action']))
                $_REQUEST['action'] = $this->default_action;
        }
    }

    /**
     * Actions that modify data in this controller's instance and thus require referrers
     * @var array
     */
    protected $modifyActions = array();
    /**
     * Actions that always modify data and thus require referrers
     * save* and delete* hardcoded as modified
     * @var array
     */
    private $globalModifyActions = array(
        'massupdate', 'configuredashlet', 'import', 'importvcardsave', 'inlinefieldsave',
        'wlsave', 'quicksave'
    );

    /**
     * Modules that modify data and thus require referrers for all actions
     */
    private $modifyModules = array(
        'Administration' => true,
        'UpgradeWizard' => true,
        'Configurator' => true,
        'Studio' => true,
        'ModuleBuilder' => true,
        'Emails' => true,
        'DCETemplates' => true,
        'DCEInstances' => true,
        'DCEActions' => true,
        'Trackers' => array('trackersettings'),
        'SugarFavorites' => array('tag'),
        'Import' => array('last', 'undo'),
        'Users' => array('changepassword', "generatepassword"),
    );

    protected function isModifyAction()
    {
        $action = strtolower($this->controller->action);
        if(substr($action, 0, 4) == "save" || substr($action, 0, 6) == "delete") {
            return true;
        }
        if(isset($this->modifyModules[$this->controller->module])) {
            if($this->modifyModules[$this->controller->module] === true) {
                return true;
            }
            if(in_array($this->controller->action, $this->modifyModules[$this->controller->module])) {
                return true;

            }
        }
        if(in_array($this->controller->action, $this->globalModifyActions)) {
            return true;
        }
        if(in_array($this->controller->action, $this->modifyActions)) {
            return true;
        }
        return false;
    }

    /**
     * The list of the actions excepted from referer checks by default
     * @var array
     */
    protected $whiteListActions = array('index', 'ListView', 'DetailView', 'EditView', 'oauth', 'authorize', 'Authenticate', 'Login', 'SupportPortal', 'GoogleOauth2Redirect');

    /**
     *
     * Checks a request to ensure the request is coming from a valid source or it is for one of the white listed actions
     */
    protected function checkHTTPReferer($dieIfInvalid = true)
    {
        global $sugar_config;
        if(!empty($sugar_config['http_referer']['actions'])) {
            $this->whiteListActions = array_merge($sugar_config['http_referer']['actions'], $this->whiteListActions);
        }

        $strong = empty($sugar_config['http_referer']['weak']);

        // Bug 39691 - Make sure localhost and 127.0.0.1 are always valid HTTP referers
        $whiteListReferers = array('127.0.0.1','localhost');
        if(!empty($_SERVER['SERVER_ADDR']))$whiteListReferers[]  = $_SERVER['SERVER_ADDR'];
        if ( !empty($sugar_config['http_referer']['list']) ) {
            $whiteListReferers = array_merge($whiteListReferers,$sugar_config['http_referer']['list']);
        }

        if($strong && empty($_SERVER['HTTP_REFERER']) && !in_array($this->controller->action, $this->whiteListActions ) && $this->isModifyAction()) {
            $http_host = explode(':', $_SERVER['HTTP_HOST']);
            $whiteListActions = $this->whiteListActions;
            $whiteListActions[] = $this->controller->action;
            $whiteListString = "'" . implode("', '", $whiteListActions) . "'";
            if ( $dieIfInvalid ) {
                header("Cache-Control: no-cache, must-revalidate");
                $ss = new Sugar_Smarty;
                $ss->assign('host', $http_host[0]);
                $ss->assign('action',$this->controller->action);
                $ss->assign('whiteListString',$whiteListString);
                $ss->display('include/MVC/View/tpls/xsrf.tpl');
                sugar_cleanup(true);
            }
            return false;
        } else
            if(!empty($_SERVER['HTTP_REFERER']) && !empty($_SERVER['SERVER_NAME'])){
                $http_ref = parse_url($_SERVER['HTTP_REFERER']);
                if($http_ref['host'] !== $_SERVER['SERVER_NAME']  && !in_array($this->controller->action, $this->whiteListActions) &&

                    (empty($whiteListReferers) || !in_array($http_ref['host'], $whiteListReferers))){
                    if ( $dieIfInvalid ) {
                        header("Cache-Control: no-cache, must-revalidate");
                        $whiteListActions = $this->whiteListActions;
                        $whiteListActions[] = $this->controller->action;
                        $whiteListString = "'" . implode("', '", $whiteListActions) . "'";

                        $ss = new Sugar_Smarty;
                        $ss->assign('host',$http_ref['host']);
                        $ss->assign('action',$this->controller->action);
                        $ss->assign('whiteListString',$whiteListString);
                        $ss->display('include/MVC/View/tpls/xsrf.tpl');
                        sugar_cleanup(true);
                    }
                    return false;
                }
            }
        return true;
    }
    function startSession()
    {
        //2018-04-20: use session_anme instead of key 'PHPSESSID'
        $sessionIdCookie = isset($_COOKIE[session_name()]) ? $_COOKIE[session_name()] : null;
        if(isset($_REQUEST['MSID'])) {
            session_id($_REQUEST['MSID']);
            session_start();
            if(!isset($_SESSION['user_id'])){
                if(isset($_COOKIE[session_name()])){
                    self::setCookie(session_name(), '', time()-42000, '/');
                }
                sugar_cleanup(false);
                session_destroy();
                exit('Not a valid entry method');
            }
        }else{
            if(can_start_session()){
                session_start();
            }
        }

        //set the default module to either Home or specified default
        $default_module = !empty($GLOBALS['sugar_config']['default_module'])?  $GLOBALS['sugar_config']['default_module'] : 'Administration';

        //set session expired message if login module and action are set to a non login default
        //AND session id in cookie is set but super global session array is empty
        if ( isset($_REQUEST['login_module']) && isset($_REQUEST['login_action'])
            && !($_REQUEST['login_module'] == $default_module && $_REQUEST['login_action'] == 'index') ) {
            if ( !is_null($sessionIdCookie) && empty($_SESSION) ) {
                self::setCookie('loginErrorMessage', 'ERR_LOGGED_OUT_SESSION_EXPIRED', time()+30, '/');
            }
        }


        LogicHook::initialize()->call_custom_logic('', 'after_session_start');
    }




    function endSession(){
        session_destroy();
    }
    /**
     * Redirect to another URL
     *
     * @access	public
     * @param	string	$url	The URL to redirect to
     */
    function redirect(
        $url
    )
    {
        /*
         * If the headers have been sent, then we cannot send an additional location header
         * so we will output a javascript redirect statement.
         */
        if (!empty($_REQUEST['ajax_load']))
        {
            ob_get_clean();
            $ajax_ret = array(
                'content' => "<script>SUGAR.ajaxUI.loadContent('$url');</script>\n",
                'menu' => array(
                    'module' => $_REQUEST['module'],
                    'label' => translate($_REQUEST['module']),
                ),
            );
            echo json_encode($ajax_ret);
        } else {
            if (headers_sent()) {
                echo "<script>SUGAR.ajaxUI.loadContent('$url');</script>\n";
            } else {
                //@ob_end_clean(); // clear output buffer
                session_write_close();
                header( 'HTTP/1.1 301 Moved Permanently' );
                header( "Location: ". $url );
            }
        }
        exit();
    }

    /**
     * Redirect to another URL
     *
     * @access	public
     * @param	string	$url	The URL to redirect to
     */
    public static function appendErrorMessage($error_message)
    {
        if (empty($_SESSION['user_error_message']) || !is_array($_SESSION['user_error_message'])){
            $_SESSION['user_error_message'] = array();
        }
        $_SESSION['user_error_message'][] = $error_message;
    }

    public static function getErrorMessages()
    {
        if (isset($_SESSION['user_error_message']) && is_array($_SESSION['user_error_message']) ) {
            $msgs = $_SESSION['user_error_message'];
            unset($_SESSION['user_error_message']);
            return $msgs;
        }else{
            return array();
        }
    }

    /**
     * Wrapper for the PHP setcookie() function, to handle cases where headers have
     * already been sent
     */
    public static function setCookie(
        $name,
        $value,
        $expire = 0,
        $path = '/',
        $domain = null,
        $secure = false,
        $httponly = false
    )
    {
        if ( is_null($domain) )
            if ( isset($_SERVER["HTTP_HOST"]) )
                $domain = $_SERVER["HTTP_HOST"];
            else
                $domain = 'localhost';

    }

    protected $redirectVars = array('module', 'action', 'record', 'token', 'oauth_token', 'mobile');

    /**
     * Create string to attach to login URL with vars to preserve post-login
     * @return string URL part with login vars
     */
    public function createLoginVars()
    {
        $ret = array();
        foreach($this->redirectVars as $var) {
            if(!empty($this->controller->$var)) {
                $ret["login_".$var] = $this->controller->$var;
                continue;
            }
            if(!empty($_REQUEST[$var])) {
                $ret["login_".$var] = $_REQUEST[$var];
            }
        }
        if(isset($_REQUEST['mobile'])) {
            $ret['mobile'] = $_REQUEST['mobile'];
        }
        if(isset($_REQUEST['no_saml'])) {
            $ret['no_saml'] = $_REQUEST['no_saml'];
        }
        if(empty($ret)) return '';
        return "&".http_build_query($ret);
    }

    /**
     * Get the list of vars passed with login form
     * @param bool $add_empty Add empty vars to the result?
     * @return array List of vars passed with login
     */
    public function getLoginVars($add_empty = true)
    {
        $ret = array();
        foreach($this->redirectVars as $var) {
            if(!empty($_REQUEST['login_'.$var]) || $add_empty) {
                $ret["login_".$var] = isset($_REQUEST['login_'.$var])?$_REQUEST['login_'.$var]:'';
            }
        }
        return $ret;
    }

    /**
     * Get URL to redirect after the login
     * @return string the URL to redirect to
     */
    public function getLoginRedirect()
    {
        $vars = array();
        foreach($this->redirectVars as $var) {
            if(!empty($_REQUEST['login_'.$var])) $vars[$var] = $_REQUEST['login_'.$var];
        }
        if(isset($_REQUEST['mobile'])) {
            $vars['mobile'] = $_REQUEST['mobile'];
        }

        if(isset($_REQUEST['mobile']))
        {
            $vars['mobile'] = $_REQUEST['mobile'];
        }
        if(empty($vars)) {
            return "index.php?module=Administration&action=index";
        } else {
            return "index.php?".http_build_query($vars);
        }
    }
}
