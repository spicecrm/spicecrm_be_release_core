<?php
namespace SpiceCRM\modules\Users\authentication\OAuthAuthenticate;

class OAuthAuthenticateUser
{
    /**
     * this is called when a user logs in
     *
     * @param STRING $name
     * @param STRING $fallback - is this authentication a fallback from a failed authentication
     * @return boolean
     */
    public function loadUserOnLogin($name, $fallback = false, $PARAMS = []) {
        global $login_error;

        $GLOBALS['log']->debug("Starting user load for ". $name);
        if (empty($name)) {
            return false;
        }

        $user_id = $PARAMS['userId'];
        if (empty($user_id)) {
            $GLOBALS['log']->login('SECURITY: User authentication for '.$name.' failed.');
            return false;
        }
        $this->loadUserOnSession($user_id);
        return true;
    }

    /**
     * Loads the current user based on the given user_id
     *
     * @param STRING $user_id
     * @return boolean
     */
    private function loadUserOnSession($user_id='') {
        if (!empty($user_id)) {
            $_SESSION['authenticated_user_id'] = $user_id;
        }

        if (!empty($_SESSION['authenticated_user_id']) || !empty($user_id)) {
            $GLOBALS['current_user'] = new \User();
            if ($GLOBALS['current_user']->retrieve($_SESSION['authenticated_user_id'])) {

                return true;
            }
        }
        return false;
    }
}
