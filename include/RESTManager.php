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

namespace SpiceCRM\includes;

use Administration;
use AuthenticationController;
use BeanFactory;
use Contact;
use LogicHook;
use Slim\App;
use SpiceCRM\includes\ErrorHandlers\Exception;
use SpiceCRM\includes\ErrorHandlers\UnauthorizedException;
use SpiceCRM\includes\utils\SpiceUtils;
use SpiceCRM\includes\utils\RESTRateLimiter;

class RESTManager
{
    /**
     * the instance for the singleton pattern
     *
     * @var null
     */
    private static $_instance = null;

    /**
     * the SLIM app
     * @var null
     */
    public $app = null;
    private $sessionId = null;
    private $requestParams = [];
    private $noAuthentication = false;
    private $adminOnly = false;
    public $tmpSessionId = null;
    public $extensions = [];

    private function __construct() {}

    private function __clone() {}

    /**
     * Returns an instance of the RESTManager singleton.
     *
     * @return RESTManager|null
     */
    public static function getInstance() {
        if (!is_object(self::$_instance)) {
            self::$_instance = new RESTManager();
        }
        return self::$_instance;
    }

    /**
     * Initializes the RESTManager.
     *
     * @param App $app
     */
    public function intialize(App $app) {
        // link the app and the request paramas
        $this->app = $app;
        // some general global settings
        // disable fixup format added to pürevent fixup format in sugarbean .. invalidates float based on user settings
        global $disable_fixup_format;
        $disable_fixup_format = true;

        // set a global transaction id
        $GLOBALS['transactionID'] = SpiceUtils::createGuid();

        if (isset($GLOBALS['sugar_config']['sessionMaxLifetime'])) {
            ini_set('session.gc_maxlifetime', $GLOBALS['sugar_config']['sessionMaxLifetime']);
        }

        // handle the error reporting for the REST APOI accoridng to the Config Settings
        if (isset($GLOBALS['sugar_config']['krest']['error_reporting'])) {
            error_reporting($GLOBALS['sugar_config']['krest']['error_reporting']);
        }

        if (isset($GLOBALS['sugar_config']['krest']['display_errors'])) {
            ini_set('display_errors', $GLOBALS['sugar_config']['krest']['display_errors']);
        }

        // check if the rate Limiter is active
        if (@$GLOBALS['sugar_config']['krest']['rateLimiting']['active']) {
            $app->add(
                function ($request, $response, $next) {
                    RESTRateLimiter::check($request->getMethod());
                    return $response = $next($request, $response);
                }
            );
        }

        $app->add(function ($req, $res, $next) {
            $response = $next($req, $res);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        });

        $app->options('/{routes:.+}', function ($request, $response, $args) {
            return $response;
        });

        $this->initErrorHandling();

        // if we are still installing skip the restlogger
        if (!($_GET['installer'])) {
            $this->initLogging();
        }

        // check if we have extension in the local path
        $checkRootPaths = ['include', 'modules', 'custom/modules'];
        foreach ($checkRootPaths as $checkRootPath) {
            $KRestDirHandle = opendir("./$checkRootPath");
            if ($KRestDirHandle) {
                while (($KRestNextDir = readdir($KRestDirHandle)) !== false) {
                    if ($KRestNextDir != '.' && $KRestNextDir != '..' && is_dir("./$checkRootPath/$KRestNextDir") && file_exists("./$checkRootPath/$KRestNextDir/KREST/extensions")) {
                        $KRestSubDirHandle = opendir("./$checkRootPath/$KRestNextDir/KREST/extensions");
                        if ($KRestSubDirHandle) {
                            while (false !== ($KRestNextFile = readdir($KRestSubDirHandle))) {
                                if (preg_match('/.php$/', $KRestNextFile)) {
                                    require_once("./$checkRootPath/$KRestNextDir/KREST/extensions/$KRestNextFile");
                                }
                            }
                        }
                    }
                }
            }
        }

        if (file_exists('./custom/KREST/extensions')) {
            $KRestDirHandle = opendir('./custom/KREST/extensions');
            if ($KRestDirHandle) {
                while (false !== ($KRestNextFile = readdir($KRestDirHandle))) {
                    if (preg_match('/.php$/', $KRestNextFile)) {
                        require_once('./custom/KREST/extensions/' . $KRestNextFile);
                    }
                }
            }
        }

        $KRestDirHandle = opendir('./KREST/extensions');
        while (false !== ($KRestNextFile = readdir($KRestDirHandle))) {
            $statusInclude = 'NOP';
            if (preg_match('/.php$/', $KRestNextFile)) {
                $statusInclude = 'included';
                require_once('./KREST/extensions/' . $KRestNextFile);
            }
        }

        // authenticate
        try {
            if ($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
                $this->authenticate();
            }
        } catch (Exception $exception) {
            $this->outputError($exception);
        }

        // specific handler for the files
        $this->getProxyFiles();

        // Catch-all route to serve a 404 Not Found page if none of the routes match
        // NOTE: make sure this route is defined last
        $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($req, $res) {
            $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
            return $handler($req, $res);
        });
    }

    /**
     * Registers an extension.
     *
     * @param $extension
     * @param $version
     * @param array $config
     */
    public function registerExtension($extension, $version, $config = []) {
        $this->extensions[$extension] = [
            'version' => $version,
            'config' => $config,
        ];
    }

    /**
     * Excludes the given path from authentication.
     *
     * @param $path
     */
    public function excludeFromAuthentication($path) {
        global $sugar_config;

        // support for IIS
        $currentPath = $this->app->getContainer()['environment']->get("REDIRECT_URL") ?: $this->app->getContainer()['environment']->get("REQUEST_URI");
        if (!isset($sugar_config['krest']['url_prefix'])) {
            $pos = strpos($currentPath, '/sysinfo');
            if ($pos !== false) {
                $urlPrefix = substr($currentPath, 0, $pos);
            } else {
                // todo write the $urlPrefix value into the config file
            }
        } else {
            $urlPrefix = $sugar_config['krest']['url_prefix'];
        }
        $currentPath = explode($urlPrefix, $currentPath, 2)[1];

        if (substr($path, -1) === '*' && strpos($currentPath, substr($path, 0, -1)) === 0) {
            $this->noAuthentication = true;
        } elseif ($currentPath === $path) {
            $this->noAuthentication = true;
        }
    }

    /**
     * Makes a given path accessible only for admins.
     *
     * @param $path
     */
    public function adminAccessOnly($path)
    {
        // support for IIS
        $currentPath = $this->app->getContainer()['environment']->get("REDIRECT_URL") ?: $this->app->getContainer()['environment']->get("REQUEST_URI");
        $currentPath = explode('/KREST', $currentPath, 2)[1];

        if (substr($path, -1) === '*' && strpos($currentPath, substr($path, 0, -1)) === 0) {
            $this->adminOnly = true;
        } elseif ($currentPath === $path) {
            $this->adminOnly = true;
        }
    }

    /**
     * Returns all headers converted to lower case.
     *
     * @return array
     */
    private function getHeaders() {
        $retHeaders = [];
        $headers = getallheaders();
        foreach ($headers as $key => $value) {
            $retHeaders[strtolower($key)] = $value;
        }
        return $retHeaders;
    }

    /**
     * Authenticates the user based on the headers or post parameters.
     *
     * @throws UnauthorizedException
     */
    public function authenticate() {
        //$environment = $this->app->getContainer()['environment'];
        if ($this->noAuthentication) {
            return;
        }

        // get the headers
        $headers = $this->getHeaders();

        // handle the session start
        $sessionSuccess = false;
        if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
            $loginData = $this->login([
                'user_name'  => $_SERVER['PHP_AUTH_USER'],
                'password'   => $_SERVER['PHP_AUTH_PW'],
                'encryption' => 'PLAIN',
                'loginByDev' => isset($_GET['byDev']{0}) ? $_GET['byDev'] : null
            ]);
            if ($loginData !== false) {
                $this->sessionId = $loginData;
                $this->tmpSessionId = $loginData;
                $accessLog = BeanFactory::getBean('UserAccessLogs');
                $accessLog->addRecord();
            } else {
                $this->authenticationError('', $_SERVER['PHP_AUTH_USER']);
            }
        } elseif (!empty($_GET['PHP_AUTH_DIGEST_RAW'])) {
            // handling for CLI
            $auth = explode(':', base64_decode($_GET['PHP_AUTH_DIGEST_RAW']));
            $loginData = $this->login([
                'user_name'  => $auth[0],
                'password'   => $auth[1],
                'encryption' => 'PLAIN',
                'loginByDev' => isset($_GET['byDev']{0}) ? $_GET['byDev'] : null
            ]);
            if ($loginData !== false) {
                $this->sessionId = $loginData;
                $this->tmpSessionId = $loginData;
                $accessLog = BeanFactory::getBean('UserAccessLogs');
                $accessLog->addRecord();
            } else {
                $this->authenticationError('', $auth[0]);
            }
        } elseif (!empty($this->requestParams['user_name']) && !empty($this->requestParams['password'])) {
            $loginData = $this->login([
                'user_name'  => $this->requestParams['user_name'],
                'password'   => $this->requestParams['password'],
                'encryption' => $this->requestParams['encryption'],
            ]);
            echo $this->requestParams['user_name'];
            exit;
            if ($loginData !== false) {
                $this->sessionId = $loginData;
                $this->tmpSessionId = $loginData;
            } else {
                $this->authenticationError('', $this->requestParams['user_name']);
            }
        } elseif (!empty($headers['oauth-token'])) {
            $startedSession = $this->startSession($headers['oauth-token']);
            if ($startedSession !== false)
                $this->sessionId = $startedSession;
            else
                $this->authenticationError('session invalid');
        } elseif (!empty($this->requestParams['session_id']) ||
            !empty($this->requestParams['sessionid'])) {

            $sessionId = $this->requestParams['session_id'] ?: $this->requestParams['sessionid'];
            $startedSession = $this->startSession($sessionId);

            if ($startedSession !== false) {
                $this->sessionId = $startedSession;
            } else {
                $this->authenticationError('session invalid');
            }
        } else {
            $this->authenticationError('auth data missing');
        }

        if ($this->adminOnly && !$GLOBALS['current_user']->is_admin) {
            $this->authenticationError('Admin Access only');
        }
    }

    public function cleanup() {
        // delete the session if it was created without login
        if (!empty($this->tmpSessionId)) {
            session_destroy();
        }
    }

    /**
     * Handles an authentication error: writes it into log and throws an exception.
     *
     * @param string $message
     * @param null $loginName
     * @throws UnauthorizedException
     */
    public function authenticationError($message = '', $loginName = null) {
        $accessLog = BeanFactory::getBean('UserAccessLogs');
        $accessLog->addRecord('loginfail', $loginName);

        // set for cors
        // header("Access-Control-Allow-Origin: *");
        throw new UnauthorizedException('Authentication failed' . (isset($message{0}) ? ': ' . $message : '.'));
    }

    /**
     * Starts the session.
     *
     * @param string $session_id
     * @return false|mixed|string
     */
    public function startSession($session_id = '') {
        if (empty($session_id)) {
            $requestparams = $_GET;
            if (isset($requestparams['session_id'])) {
                $session_id = $requestparams['session_id'];
            }
        }

        if (!empty($session_id)) {
            if (!session_id()) {
                session_id($session_id);
                session_start();
            }

            if (!empty($_SESSION['authenticated_user_id'])) {
                global $current_user;
                $current_user = BeanFactory::getBean('Users', $_SESSION['authenticated_user_id']);
                return $session_id;
            }
        }
        return false;
    }

    /**
     * Validates the session.
     *
     * @param $session_id
     * @return bool
     */
    public function validate_session($session_id) {
        if (!empty($session_id)) {
            // only initialize session once in case this method is called multiple times
            if (!session_id()) {
                session_id($session_id);
                session_start();
            }

            if (!empty($_SESSION['is_valid_session']) && $_SESSION['type'] == 'user') {
                global $current_user;
                $current_user = BeanFactory::getBean('Users', $_SESSION['authenticated_user_id']);
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
        if ($usr_id) {
            $user->retrieve($usr_id);
        }

        if ($isLoginSuccess) {
            if ($_SESSION['hasExpiredPassword'] == '1') {
                $error = 'password_expired';
            }
            if (!empty($user) && !empty($user->id) && !$user->is_group) {
                $success = true;
                global $current_user;
                $current_user = $user;
            }
        } elseif ($usr_id && isset($user->user_name) && ($user->getPreference('lockout') == '1')) {
            $error = 'lockout_reached';
        } /* else if (function_exists('mcrypt_cbc') && $authController->authController->userAuthenticateClass == "LDAPAuthenticateUser" && (empty($user_auth['encryption']) || $user_auth['encryption'] !== 'PLAIN' )) {
          $password = self::$helperObject->decrypt_string($user_auth['password']);
          $authController->loggedIn = false; // reset login attempt to try again with decrypted password
          if ($authController->login($user_auth['user_name'], $password) && isset($_SESSION['authenticated_user_id']))
          $success = true;
          } */ elseif ($authController->authController->userAuthenticateClass == "LDAPAuthenticateUser" &&
                        (empty($user_auth['encryption']) || $user_auth['encryption'] == 'PLAIN')) {
            $authController->loggedIn = false; // reset login attempt to try again with md5 password
            if ($authController->login($user_auth['user_name'], md5($user_auth['password']), ['passwordEncrypted' => true]) &&
                isset($_SESSION['authenticated_user_id'])) {
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
            'admin' => $current_user->is_admin,
            'display_name' => $current_user->get_summary_text(),
            'email' => $current_user->email1,
            'first_name' => $current_user->first_name,
            'id' => session_id(),
            'last_name' => $current_user->last_name,
            'portal_only' => $current_user->portal_only,
            'renewPass' => $current_user->system_generated_password,
            'user_name' => $current_user->user_name,
            'userid' => $current_user->id,
            'user_image' => $current_user->user_image,
            'dev' => $current_user->is_dev,
            'companycode_id' => $current_user->companycode_id,
            'obtainGDPRconsent' => false
        ];

        // Is it a portal user? And the GDPR consent for portal users is configured?
        if ($current_user->portal_only and @$GLOBALS['sugar_config']['portal_gdpr']['obtain_consent']) {
            $contactOfPortalUser = new Contact();
            $contactOfPortalUser->retrieve_by_string_fields(['portal_user_id' => $GLOBALS['current_user']->id]);
            // gdpr_marketing_agreement not 'g' and not 'r' indicates that the user has not yet been asked for consent of GDPR in general (data AND marketing)
            if (($contactOfPortalUser->gdpr_marketing_agreement !== 'g' and $contactOfPortalUser->gdpr_marketing_agreement !== 'r') and !$contactOfPortalUser->gdpr_data_agreement) $loginData['obtainGDPRconsent'] = true;
        }

        return $loginData;
    }

    public function getProxyFiles() {
        $headers = getallheaders();

        if ($headers['proxyfiles']) {
            $files = json_decode(base64_decode($headers['proxyfiles']), true);
            $_FILES = $files;
        }

    }

    /**
     * Initialize Error Handling
     * Each thrown Exception is caught here and is available in $exception.
     */
    private function initErrorHandling() {
        if (isset($this->app)) {
            $c = $this->app->getContainer();
        }

        // Error handlers

        # errorHandler is for PHP < 7
        $c['errorHandler'] = $c['phpErrorHandler'] = function( $container ) {
            return function( $request, $response, $exceptionObjectOrMessage ) {
                return $this->handleErrorResponse($exceptionObjectOrMessage);
            };
        };

        $c['notFoundHandler'] = function ( $container ) {
            return function( $request, $response ) {
                return $this->handleErrorResponse(new \SpiceCRM\includes\ErrorHandlers\NotFoundException());
            };
        };

        $c['notAllowedHandler'] = function( $container ) {
            return function ( $request, $response, $allowedMethods ) use( $container ) {
                $responseData['error'] = [ 'message' => 'Method not allowed.', 'errorCode' => 'notAllowed', 'methodsAllowed' => implode(', ', $allowedMethods), 'httpCode' => 405 ];
                return $container['response']
                    ->withHeader('Allow', implode(', ', $allowedMethods )) # todo: header not appears in browser (response)
                    ->withJson( $responseData, 405 );
            };
        };

        $this->app->add( function( $request, $response, $next ) {
            try {
                $response = $next( $request, $response );
            }
            catch( \SpiceCRM\includes\ErrorHandlers\Exception $exception ) {
                return \SpiceCRM\includes\RESTManager::getInstance()->handleErrorResponse($exception);
            }
            catch( Exception $exception ) {
                return \SpiceCRM\includes\RESTManager::getInstance()->handleErrorResponse($exception);
            }
            return $response;
        });
    }

    /**
     * kind of deprecated... only use outside of slim
     * @param $exception
     * @return string
     */
    private function outputError( $exception ) {
        $inDevMode = ( isset( $GLOBALS['sugar_config']['developerMode'] ) and $GLOBALS['sugar_config']['developerMode'] );

        if ( is_object( $exception )) {

            if ( is_a( $exception, 'SpiceCRM\includes\ErrorHandlers\Exception' ) ) {
                if ( $exception->isFatal() ) $GLOBALS['log']->fatal( $exception->getMessageToLog() . ' in ' . $exception->getFile() . ':' . $exception->getLine() );
                $responseData = $exception->getResponseData();
                if ( get_class( $exception ) === 'SpiceCRM\includes\ErrorHandlers\Exception' ) {
                    $responseData['line'] = $exception->getLine();
                    $responseData['file'] = $exception->getFile();
                    $responseData['trace'] = $exception->getTrace();
                }
                $httpCode = $exception->getHttpCode();
            } else {
                if ( $inDevMode )
                    $responseData =  [ 'message' => $exception->getMessage(), 'line' => $exception->getLine(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace() ];
                else $responseData['error'] = ['message' => 'Application Error.'];
                $httpCode = $exception->getCode();
            }

        } else {

            $GLOBALS['log']->fatal( $exception );
            $responseData['error'] = [ 'message' => $inDevMode ? 'Application Error.' : $exception ];
            $httpCode = 500;

        }

        http_response_code( $httpCode ? $httpCode : 500 );
        $json = json_encode( [ 'error' => $responseData ], JSON_PARTIAL_OUTPUT_ON_ERROR);
        if(!$json)
            echo json_encode([ 'error' => 'Error while JSON encoding of an exception: '.json_last_error_msg().'... with exception message: '.$exception->getMessage()]);
        else
            echo $json;
        exit;

    }

    private function handleErrorResponse($exception) {
        $specialResponseHeaders = [];
        $inDevMode = ( isset( $GLOBALS['sugar_config']['developerMode'] ) and $GLOBALS['sugar_config']['developerMode'] );

        if ( is_object( $exception )) {

            if ( is_a( $exception, 'SpiceCRM\includes\ErrorHandlers\Exception' ) ) {
                if ( $exception->isFatal() ) $GLOBALS['log']->fatal( $exception->getMessageToLog() . ' in ' . $exception->getFile() . ':' . $exception->getLine() );
                $responseData = $exception->getResponseData();
                if ( get_class( $exception ) === 'SpiceCRM\includes\ErrorHandler\Exception' ) {
                    $responseData['line'] = $exception->getLine();
                    $responseData['file'] = $exception->getFile();
                    $responseData['trace'] = $exception->getTrace();
                }
                $httpCode = $exception->getHttpCode();
                $specialResponseHeaders = $exception->getHttpHeaders();
            } else {
                if ( $inDevMode )
                    $responseData =  [ 'code' => $exception->getCode(), 'message' => $exception->getMessage(), 'line' => $exception->getLine(), 'file' => $exception->getFile(), 'trace' => $exception->getTrace() ];
                else $responseData['error'] = ['message' => 'Application Error.'];
                $httpCode = 500;
            }

        } else {

            $GLOBALS['log']->fatal( $exception );
            $responseData['error'] = [ 'message' => $inDevMode ? 'Application Error.' : $exception ];
            $httpCode = 500;

        }

        $response = new \Slim\Http\Response();
        foreach ( $specialResponseHeaders as $k => $v ) $response = $response->withHeader( $k, $v );
        return $response->withJson(['error' => $responseData], $httpCode ? $httpCode : 500, JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    private function initLogging() {
        $mw = function ($request, $response, $next) {
            global $db, $current_user;
            $starting_time = microtime(true);

            $route = $request->getAttribute('route');
            $log = (object) [];
            // if no route was found... $route = null
            if ($route) {
                $log->route  = $route->getPattern();
                $log->method = $route->getMethods()[0];
                $log->args   = json_encode($route->getArguments());
            }

            $log->url = (string) $request->getUri();    // will be converted to the complete url when be used in text context, therefore it is cast to a string...

            $log->ip = $request->getServerParam('REMOTE_ADDR');
            $log->get_params = json_encode($_GET);
            $log->headers =  json_encode($request->getHeaders());
            $log->post_params = $request->getBody()->getContents();
            $log->requested_at = gmdate('Y-m-d H:i:s');
            // $current_user is an empty beansobject if the current route doesn't need any authentication...
            $log->user_id = $current_user->id;
            // and session is also missing!
            $log->session_id = session_id();
            //var_dump($request->getParsedBody(), $request->getParams());
            $log->transaction_id = $GLOBALS['transactionID'];

            // check if this request has to be logged by some rules...
            $sql = "SELECT COUNT(id) cnt FROM syskrestlogconfig WHERE 
              (route = '{$log->route}' OR route = '*' OR '{$log->route}' LIKE route) AND
              (method = '{$log->method}' OR method = '*') AND
              (user_id = '{$log->user_id}' OR user_id = '*') AND
              (ip = '{$log->ip}' OR ip = '*') AND
              is_active = 1";
            $res = $db->query($sql);
            $row = $db->fetchByAssoc($res);
            if ( $row['cnt'] > 0 ) {
                $logging = true;
                // write the log...
                $log->id = create_guid();
                $id = $db->insertQuery('syskrestlog', (array) $log);
                $log->id = $id;

                ob_start();
            } else {
                $logging = false;
            }

            // do the magic...
            $response = $next($request, $response);

            if ( $logging ) {
                $log->http_status_code = $response->getStatusCode();
                $log->runtime = (microtime(true) - $starting_time)*1000;
                $log->response = ob_get_contents();
                ob_end_flush();

                // if the endpoint didn't use echo... instead the response object ist correctly returned by the endpoint
                if(!$log->response)
                    $log->response = $response->getBody();
                // update the log...
                $result = $db->updateQuery('syskrestlog', ['id' => $log->id], (array) $log);
                //var_dump($result, $db->last_error);
            }
            return $response;
        };

        $this->app->add($mw);
    }
}
