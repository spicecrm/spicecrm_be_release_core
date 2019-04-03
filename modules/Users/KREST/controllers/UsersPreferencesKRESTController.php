<?php

namespace SpiceCRM\modules\Users\KREST\controllers;

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
        return $this->get_all_user_preferences('global');
    }

    public function getPreferences($req, $res, $args)
    {
        $names = $req->getParam('names');
        if (!isset($names)) {
            return $res->withJson($this->get_all_user_preferences($args['category']));
        } else {
            return $res->withJson($this->get_user_preferences($args['category'], $names));
        }
    }

    public function getUserPreferences($req, $res, $args)
    {
        return $res->withJson($this->get_user_preferences($args['category'], $args['names']));
    }

    public function get_all_user_preferences($category)
    {
        global $current_user;
        require_once 'modules/UserPreferences/UserPreference.php';
        $userPreference = new \UserPreference($current_user);

        $prefArray = array();

        $userPreference->loadPreferences($category);

        return $_SESSION[$current_user->user_name . '_PREFERENCES'][$category];
    }

    public function get_user_preferences($category, $names)
    {
        global $current_user;
        require_once 'modules/UserPreferences/UserPreference.php';
        $userPreference = new \UserPreference($current_user);

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
        $preferences = $req->getParsedBody();

        require_once 'modules/UserPreferences/UserPreference.php';
        $userPreference = new \UserPreference($current_user);
        $retData = array();
        // do the magic
        foreach ($preferences as $name => $value) {
            if ($value === null) $userPreference->deletePreference($name, $category);
            else $userPreference->setPreference($name, $value, $category);
            if (($memmy = $userPreference->getPreference($name, $category)) !== null) $retData[$name] = $memmy;
        }

        return $res->withJson($retData);
    }
}