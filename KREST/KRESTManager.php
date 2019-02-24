<?php

/*
 * This File is part of KREST is a Restful service extension for SugarCRM
 * 
 * Copyright (C) 2015 AAC SERVICES K.S., DOSTOJEVSKÉHO RAD 5, 811 09 BRATISLAVA, SLOVAKIA
 * 
 * you can contat us at info@spicecrm.io
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

require('include/entryPoint.php');

class KRESTManager {

    var $app = null;
    var $sessionId = null;
    var $tmpSessionId = null;
    var $requestParams = array();
    var $noAuthentication = false;
    var $adminOnly = false;
    var $extensions = array();

    public function __construct($theApp, $requestParams = array()) {
        // link the app and the request paramas
        $this->app = $theApp;
        $this->requestParams = $requestParams;

        // some general global settings
        // disable fixup format added to pürevent fixup format in sugarbean .. invalidates float based on user settings
        global $disable_date_format, $disable_fixup_format;
        $disable_date_format = true;
        $disable_fixup_format = true;
    }

    public function registerExtension($extension, $version, $config = []) {
        $this->extensions[$extension] = array(
            'version' => $version,
            'config' => $config
        );
    }

    public function excludeFromAuthentication($path) {
        // support for IIS
        $currentPath = $this->app->getContainer()['environment']->get("REDIRECT_URL") ?: $this->app->getContainer()['environment']->get("REQUEST_URI");
        $currentPath = explode('/KREST', $currentPath, 2)[1];
        //var_dump($currentPath);
        //$this->noAuthentication = true;
        if (substr($path, -1) === '*' && strpos($currentPath, substr($path, 0, -1)) === 0)
            $this->noAuthentication = true;
        else if ($currentPath === $path)
            $this->noAuthentication = true;
    }

    public function adminAccessOnly($path) {
        // support for IIS
        $currentPath = $this->app->getContainer()['environment']->get("REDIRECT_URL") ?: $this->app->getContainer()['environment']->get("REQUEST_URI");
        $currentPath = explode('/KREST', $currentPath, 2)[1];
        //var_dump($currentPath);
        //$this->noAuthentication = true;
        if (substr($path, -1) === '*' && strpos($currentPath, substr($path, 0, -1)) === 0)
            $this->adminOnly = true;
        else if ($currentPath === $path)
            $this->adminOnly = true;
    }

    public function authenticate() {
        //$environment = $this->app->getContainer()['environment'];
        if ($this->noAuthentication)
            return;

        // get the headers
        $headers = getallheaders();

        // handle the session start
        $sessionSuccess = false;
        if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
            $loginData = $this->login([
                'user_name' => $_SERVER['PHP_AUTH_USER'],
                'password' => $_SERVER['PHP_AUTH_PW'],
                'encryption' => 'PLAIN',
                'loginByDev' => isset($_GET['byDev']{0}) ? $_GET['byDev'] : null
            ]);
            if ($loginData !== false) {
                $this->sessionId = $loginData;
                $this->tmpSessionId = $loginData;
                $accessLog = BeanFactory::getBean('UserAccessLogs');
                $accessLog->addRecord();
            } else
                $this->authenticationError();
        } elseif (!empty($_GET['PHP_AUTH_DIGEST_RAW'])) {
            // handling for CLI
            $auth = explode(':', base64_decode($_GET['PHP_AUTH_DIGEST_RAW']));
            $loginData = $this->login([
                'user_name' => $auth[0],
                'password' => $auth[1],
                'encryption' => 'PLAIN',
                'loginByDev' => isset($_GET['byDev']{0}) ? $_GET['byDev'] : null
            ]);
            if ($loginData !== false) {
                $this->sessionId = $loginData;
                $this->tmpSessionId = $loginData;
                $accessLog = BeanFactory::getBean('UserAccessLogs');
                $accessLog->addRecord();
            } else
                $this->authenticationError();
        } elseif (!empty($this->requestParams['user_name']) && !empty($this->requestParams['password'])) {
            $loginData = $this->login([
                'user_name' => $this->requestParams['user_name'],
                'password' => $this->requestParams['password'],
                'encryption' => $this->requestParams['encryption'],
            ]);
            if ($loginData !== false) {
                $this->sessionId = $loginData;
                $this->tmpSessionId = $loginData;
            } else
                $this->authenticationError();

        } elseif (!empty($headers['OAuth-Token'])) {

            $startedSession = $this->startSession($headers['OAuth-Token']);
            if ($startedSession !== false)
                $this->sessionId = $startedSession;
            else
                $this->authenticationError('session invalid');
        } elseif (!empty($this->requestParams['session_id']) ||
            !empty($this->requestParams['sessionid']) ||
            !empty($_COOKIE[session_name()])) { //PHPSESSID

            $sessionId = $this->requestParams['session_id'] ?:
                ($this->requestParams['sessionid'] ?: $_COOKIE[session_name()]); //PHPSESSID
            $startedSession = $this->startSession($sessionId);

            if ($startedSession !== false)
                $this->sessionId = $startedSession;
            else
                $this->authenticationError('session invalid');
        } else {
            $this->authenticationError('auth data missing');
        }

        if($this->adminOnly && !$GLOBALS['current_user']->is_admin){
            $this->authenticationError('Admin Access only');
        }
    }

    public function cleanup() {
        // delete the session if it was created without login
        if (!empty($this->tmpSessionId)) {
            session_destroy();
        }
    }

    public function authenticationError($message = '') {
        $accessLog = BeanFactory::getBean('UserAccessLogs');
        $accessLog->addRecord('loginfail');

        // set for cors
        // header("Access-Control-Allow-Origin: *");
        throw new KREST\UnauthorizedException('Authentication failed' . (isset($message{0}) ? ': ' . $message : '.'));
    }

    // BEGMOD KORGOBJECTS change private to public..
    public function startSession($session_id = '') {
        if (empty($session_id)) {
            $requestparams = $_GET;
            if (isset($requestparams['session_id']))
                $session_id = $requestparams['session_id'];
        }

        if (!empty($session_id)) {
            if (!session_id()) {
                session_id($session_id);
                session_start();
            }

            if (!empty($_SESSION['authenticated_user_id'])) {

                global $current_user;
                require_once('modules/Users/User.php');
                $current_user = new User();
                $current_user->retrieve($_SESSION['authenticated_user_id']);

                return $session_id;
            }
        } elseif (!empty($_COOKIE[session_name()])) {
            if (!session_id()) {
                session_id($_COOKIE[session_name()]);
                session_start();
            }

            if (!empty($_SESSION['authenticated_user_id'])) {

                global $current_user;
                require_once('modules/Users/User.php');
                $current_user = new User();
                $current_user->retrieve($_SESSION['authenticated_user_id']);

                return $_COOKIE[session_name()]; //PHPSESSID
            }
        }

        return false;
    }

    public function validate_session($session_id) {
        if (!empty($session_id)) {

            // only initialize session once in case this method is called multiple times
            if (!session_id()) {
                session_id($session_id);
                session_start();
            }

            if (!empty($_SESSION['is_valid_session']) && $_SESSION['type'] == 'user') {

                global $current_user;
                require_once('modules/Users/User.php');
                $current_user = new User();
                $current_user->retrieve($_SESSION['user_id']);

                return true;
            }

            session_destroy();
        }
        LogicHook::initialize();
        return false;
    }

    private function login($user_auth) {
        global $sugar_config, $system_config;

        $user = BeanFactory::getBean('Users');
        $success = false;
        $error = '';
        //rrs
        $system_config = new Administration();
        $system_config->retrieveSettings('system');
        $authController = new AuthenticationController();
        $passwordEncrypted = true;
        //rrs
        //var_dump($user_auth);
        if (!empty($user_auth['encryption']) &&
            $user_auth['encryption'] === 'PLAIN' &&
            $authController->authController->userAuthenticateClass != "LDAPAuthenticateUser") {
            $user_auth['password'] = md5($user_auth['password']);
        }
        if (!empty($user_auth['encryption']) && $user_auth['encryption'] === 'SPICECRMMOBILE') {
            if ($authController->authController->userAuthenticateClass != "LDAPAuthenticateUser") {
                $user_auth['password'] = md5(base64_decode(str_rot13($user_auth['password'])));
            } else {
                $user_auth['password'] = base64_decode(str_rot13($user_auth['password']));
                $passwordEncrypted = false;
            }
        }
        $isLoginSuccess = $authController->login(
            $user_auth['user_name'],
            $user_auth['password'],
            [
                'passwordEncrypted' => $passwordEncrypted,
                'loginByDev' => (isset($user_auth['loginByDev']{0}) and @$GLOBALS['sugar_config']['masqueraded_developers_allowed'] === true) ? $user_auth['loginByDev'] : null
            ]
        );
        $usr_id = $user->retrieve_user_id($user_auth['user_name']);
        if ($usr_id)
            $user->retrieve($usr_id);

        if ($isLoginSuccess) {
            if ($_SESSION['hasExpiredPassword'] == '1') {
                $error = 'password_expired';
            }
            if (!empty($user) && !empty($user->id) && !$user->is_group) {
                $success = true;
                global $current_user;
                $current_user = $user;
            }
        } else if ($usr_id && isset($user->user_name) && ($user->getPreference('lockout') == '1')) {
            $error = 'lockout_reached';
        } /* else if (function_exists('mcrypt_cbc') && $authController->authController->userAuthenticateClass == "LDAPAuthenticateUser" && (empty($user_auth['encryption']) || $user_auth['encryption'] !== 'PLAIN' )) {
          $password = self::$helperObject->decrypt_string($user_auth['password']);
          $authController->loggedIn = false; // reset login attempt to try again with decrypted password
          if ($authController->login($user_auth['user_name'], $password) && isset($_SESSION['authenticated_user_id']))
          $success = true;
          } */ else if ($authController->authController->userAuthenticateClass == "LDAPAuthenticateUser" && (empty($user_auth['encryption']) || $user_auth['encryption'] == 'PLAIN')) {

            $authController->loggedIn = false; // reset login attempt to try again with md5 password
            if ($authController->login($user_auth['user_name'], md5($user_auth['password']), array('passwordEncrypted' => true)) && isset($_SESSION['authenticated_user_id'])) {
                $success = true;
            } else {

                $error = 'ldap_error';
            }
        }

        if ($success) {
            session_start();
            global $current_user;
            $current_user->loadPreferences();
            $_SESSION['is_valid_session'] = true;
            $_SESSION['ip_address'] = query_client_ip();
            $_SESSION['user_id'] = $current_user->id;
            $_SESSION['type'] = 'user';
            $_SESSION['KREST'] = true;

            $_SESSION['avail_modules'] = query_module_access_list($user);
            $GLOBALS['ACLController']->filterModuleList($_SESSION['avail_modules'], false);

            $_SESSION['authenticated_user_id'] = $current_user->id;
            $_SESSION['unique_key'] = $sugar_config['unique_key'];

            //$GLOBALS['log']->info('End: SugarWebServiceImpl->login - successful login');
            return session_id();
        } else {
            return false;
        }
    }

    public function getLoginData() {
        global $current_user;

        // clear the tem session ... seemingly we came via login so the session shoudl be kept
        $this->tmpSessionId = null;

        $loginData = [
            'access_token' => $_SESSION['google_oauth']['access_token'],
            'admin'        => $current_user->is_admin,
            'display_name' => $current_user->get_summary_text(),
            'email'        => $current_user->email1,
            'first_name'   => $current_user->first_name,
            'id'           => session_id(),
            'last_name'    => $current_user->last_name,
            'portal_only'  => $current_user->portal_only,
            'renewPass'    => $current_user->system_generated_password,
            'user_name'    => $current_user->user_name,
            'userid'       => $current_user->id,
            'user_image'   => $current_user->user_image,
            'dev'          => $current_user->is_dev
        ];

        return $loginData;
    }

    public function getProxyFiles() {
        $headers = getallheaders();

        if ($headers['proxyfiles']) {
            $files = json_decode(base64_decode($headers['proxyfiles']), true);
            $_FILES = $files;
        }

    }

}
