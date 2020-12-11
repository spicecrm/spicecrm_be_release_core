<?php
namespace SpiceCRM\modules\Users\authentication\OAuthAuthenticate;

class OAuthAuthenticate
{
    private $userAuthenticateClass = 'SpiceCRM\\modules\\Users\\authentication\\OAuthAuthenticate\\OAuthAuthenticateUser';
    private $authenticationDir = 'OAuthAuthenticate';


    /**
     * OAuthAuthenticate constructor.
     * This will load the user authentication class
     */
    public function __construct() {
        //todo change it to work with namespaces
        $filepath = 'modules/Users/authentication/' .
            $this->authenticationDir . '/' .
            $this->userAuthenticateClass . '.php';

        // check in custom dir first, in case someone wants to override an auth controller
        if (file_exists('custom/' . $filepath)) {
            require_once('custom/' . $filepath);
        } elseif (file_exists($filepath)) {
            require_once($filepath);
        }

        $this->userAuthenticate = new $this->userAuthenticateClass();
    }

    /**
     * Authenticates a user
     * returns true if the user was authenticated false otherwise
     * it also will load the user into current user if he was authenticated
     *
     * @param $username
     * @param string $password
     * @param bool $fallback
     * @param array $PARAMS
     * @return bool
     * @throws \Exception
     */
    public function loginAuthenticate($username, $password = '', $fallback = false, $PARAMS = []) {

        global $mod_strings;
        unset($_SESSION['login_error']);
        $usr = new \User();
        $usr_id = $usr->retrieve_user_id($username);
        if (!$usr_id) {
            throw new \Exception('User ' . $username . ' does not exist in SpiceCRM');
        }
        $usr->retrieve($usr_id);
        $_SESSION['login_error'] = '';
        $_SESSION['waiting_error'] = '';
        $_SESSION['hasExpiredPassword'] = '0';
        $PARAMS['userId'] = $usr_id;
        if ($this->userAuthenticate->loadUserOnLogin($username, $fallback, $PARAMS)) {

            // now that user is authenticated, reset loginfailed
            if ($usr->getPreference('loginfailed') != '' && $usr->getPreference('loginfailed') != 0) {
                $usr->setPreference('loginfailed', '0');
                $usr->savePreferencesToDB();
            }
            return $this->postLoginAuthenticate();
        } else {
            if (!empty($usr_id) && $res['lockoutexpiration'] > 0) {
                if (($logout = $usr->getPreference('loginfailed')) == '')
                    $usr->setPreference('loginfailed', '1');
                else
                    $usr->setPreference('loginfailed', $logout + 1);
                $usr->savePreferencesToDB();
            }
        }

        $_SESSION['login_user_name'] = $username;
        if (empty($_SESSION['login_error'])) {
            $_SESSION['login_error'] = translate('ERR_INVALID_PASSWORD', 'Users');
        }

        return false;
    }

    /**
     * Once a user is authenticated on login this function will be called.
     * Populate the session with what is needed and log anything that needs to be logged
     *
     * @return bool
     */
    public function postLoginAuthenticate() {

        global $reset_theme_on_default_user, $reset_language_on_default_user, $sugar_config;
        //THIS SECTION IS TO ENSURE VERSIONS ARE UPTODATE

                //just do a little house cleaning here
        unset($_SESSION['login_password']);
        unset($_SESSION['login_error']);
        unset($_SESSION['login_user_name']);
        unset($_SESSION['ACL']);

        //set the server unique key
        if (isset($sugar_config['unique_key']))
            $_SESSION['unique_key'] = $sugar_config['unique_key'];

        //set user language
        if (isset($reset_language_on_default_user) &&
            $reset_language_on_default_user &&
            $GLOBALS['current_user']->user_name == $sugar_config['default_user_name']
        ) {
            $authenticated_user_language = $sugar_config['default_language'];
        } else {
            $authenticated_user_language = isset($_REQUEST['login_language']) ?
                $_REQUEST['login_language'] :
                (isset($_REQUEST['ck_login_language_20']) ?
                    $_REQUEST['ck_login_language_20'] :
                    $sugar_config['default_language']
                );
        }

        $_SESSION['authenticated_user_language'] = $authenticated_user_language;

        $GLOBALS['log']->debug("authenticated_user_language is $authenticated_user_language");

        return true;
    }

}
