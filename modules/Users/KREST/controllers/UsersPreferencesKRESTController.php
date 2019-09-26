<?php

namespace SpiceCRM\modules\Users\KREST\controllers;

use KREST\ForbiddenException;
use KREST\NotFoundException;

class UsersPreferencesKRESTController
{

    /**
     * called from the settings in the taskloaderitems. So no paramaters are expected and the reonse is an array.
     * The rest is handled by the REST extension there.
     *
     * @return mixed
     */
    public function getGlobalPreferences()
    {
        global $current_user;
        return $this->get_all_user_preferences('global', $current_user->id );
    }

    public function getPreferences($req, $res, $args)
    {
        $names = $req->getParam('names');
        if (!isset($names)) {
            return $res->withJson($this->get_all_user_preferences( $args['category'], $args['userId'] ));
        } else {
            return $res->withJson($this->get_user_preferences($args['category'], $names, $args['userId'] ));
        }
    }

    public function getUserPreferences($req, $res, $args)
    {
        return $res->withJson($this->get_user_preferences($args['category'], $args['names'], $args['userId']));
    }

    public function get_all_user_preferences( $category, $userId )
    {
        global $current_user;

        if ( $current_user->id === $userId ) $user = $current_user;
        else {
            if ( $current_user->is_admin and $GLOBALS['sugar_config']['enableSettingUserPrefsByAdmin'] ) {
                $user = new \User();
                $user->retrieve( $userId );
                if ( empty( $user->id )) throw ( new NotFoundException('User not found.'))->setLookedFor([ 'id'=>$userId, 'module'=>'Users' ]);
            } else {
                throw new ForbiddenException('Forbidden to access user preferences of foreign user.');
            }
        }

        require_once 'modules/UserPreferences/UserPreference.php';
        $userPreference = new \UserPreference( $user );

        $prefArray = array();

        $userPreference->loadPreferences($category);

        return $_SESSION[$user->user_name . '_PREFERENCES'][$category];
    }

    public function get_user_preferences( $category, $names, $userId ) {

        global $current_user;

        if ( $current_user->id === $userId ) $user = $current_user;
        else {
            if ( $current_user->is_admin and $GLOBALS['sugar_config']['enableSettingUserPrefsByAdmin'] ) {
                $user = new \User();
                $user->retrieve( $userId );
                if ( empty( $user->id )) throw ( new NotFoundException( 'User not found.' ) )->setLookedFor( [ 'id' => $userId, 'module' => 'Users' ] );
            } else {
                throw new ForbiddenException('Forbidden to access user preferences of foreign user.');
            }
        }

        require_once 'modules/UserPreferences/UserPreference.php';
        $userPreference = new \UserPreference( $user );

        $prefArray = array();

        $namesArray = json_decode($names);
        if (!is_array($namesArray))
            $namesArray = [$names];

        foreach ($namesArray as $name)
            $prefArray[$name] = $userPreference->getPreference($name, $category);

        return $prefArray;
    }

    public function set_user_preferences($req, $res, $args)
    {
        global $current_user;

        $category = $args['category'];
        $userId = $args['userId'];
        $preferences = $req->getParsedBody();

        if ( $current_user->id === $userId ) $user = $current_user;
        else {
            if ( $current_user->is_admin and $GLOBALS['sugar_config']['enableSettingUserPrefsByAdmin'] ) {
                $user = new \User();
                $user->retrieve( $userId );
                if ( empty( $user->id )) throw ( new NotFoundException('User not found.'))->setLookedFor([ 'id'=>$userId, 'module'=>'Users' ]);
            } else {
                throw new ForbiddenException('Forbidden to change user preferences of foreign user.');
            }
        }

        require_once 'modules/UserPreferences/UserPreference.php';
        $userPreference = new \UserPreference( $user );
        $retData = array();
        // do the magic
        foreach ($preferences as $name => $value) {
            if ($value === null) $userPreference->deletePreference($name, $category);
            else $userPreference->setPreference($name, $value, $category);
            if (($memmy = $userPreference->getPreference($name, $category)) !== null) $retData[$name] = $memmy;
        }

        return $res->withJson($retData);
    }

    public function getDefaultPreferences()
    {
        $prefs = [];
        $prefNames = [ 'currency', 'datef', 'num_grp_sep', 'timef', 'timezone', 'default_currency_significant_digits', 'default_locale_name_format', 'week_day_start' ];
        foreach ( $prefNames as $name ) {
            if ( isset( $GLOBALS['sugar_config'][$name]{0} )) $prefs[$name] = $GLOBALS['sugar_config'][$name];
        }
        return $prefs;
    }

}
