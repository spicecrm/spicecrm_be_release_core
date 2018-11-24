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

class KRESTUserHandler
{

    public function getPwdCheckRegex()
    {
        $pwdCheck = '';
        if (@$GLOBALS['sugar_config']['passwordsetting']['oneupper'])
            $pwdCheck .= '(?=.*[A-Z])';
        if (@$GLOBALS['sugar_config']['passwordsetting']['onelower'])
            $pwdCheck .= '(?=.*[a-z])';
        if (@$GLOBALS['sugar_config']['passwordsetting']['onenumber'])
            $pwdCheck .= '(?=.*\d)';
        if (@$GLOBALS['sugar_config']['passwordsetting']['minpwdlength'])
            $pwdCheck .= '.{' . $GLOBALS['sugar_config']['passwordsetting']['minpwdlength'] . ',}';
        else
            $pwdCheck .= '.+';
        return $pwdCheck;
    }

    public function getPwdGuideline($lang)
    {
        global $app_strings, $sugar_config;
        $app_strings = return_application_language($lang);

        $guideline = '';

        if($sugar_config['passwordsetting']['oneupper']) {
            $guideline .= $app_strings['MSG_PASSWORD_ONEUPPER'].', ';
        }
        if($sugar_config['passwordsetting']['onelower']) {
            $guideline .= $app_strings['MSG_PASSWORD_ONELOWER'].', ';
        }
        if($sugar_config['passwordsetting']['onenumber']) {
            $guideline .= $app_strings['MSG_PASSWORD_ONENUMBER'].', ';
        }
        if($sugar_config['passwordsetting']['minpwdlength']) {
            $guideline .= $sugar_config['passwordsetting']['minpwdlength'];
            $guideline .= ' '.$app_strings['LBL_CHARACTERS'].', ';
        }
        $guideline = substr( $guideline, 0,-2);
        $guideline = ucfirst( $guideline );

        $guideline = $app_strings['LBL_AT_LEAST'].': '.$guideline.'.';

        return $guideline;
    }


    public function get_modules_acl()
    {
        global $moduleList;

        $actions = array('list', 'view', 'edit');

        $retModules = array();

        foreach (ACLController::disabledModuleList($moduleList) as $disabledModule)
            unset($moduleList[$disabledModule]);

        foreach ($moduleList as $module) {
            $retModules[$module]['acl']['enabled'] = ACLController::moduleSupportsACL($module);
            if ($retModules[$module]['acl']['enabled']) {
                foreach ($actions as $action)
                    $retModules[$module]['acl'][$action] = ACLController::checkAccess($module, $action);
            }
        }

        return $retModules;
    }

    public function set_new_password($data)
    {

        global $sugar_config;

        $newUser = BeanFactory::getBean('Users', $data['userId']);
        $newUser->setNewPassword($data['newpwd'], $data['SystemGeneratedPassword'] ? '1' : '0');
        if ($data['sendByEmail']) {
            $emailTemp_id = $sugar_config['passwordsetting']['generatepasswordtmpl'];
            $res = $newUser->sendPasswordToUser($emailTemp_id, ['password' => $data['newpwd']]);
            return ['status' => $res['status'], 'message' => $res['message']];
        }
        return ['status' => true];
    }

    public function change_password($data)
    {
        global $db, $current_user;

        $authController = new AuthenticationController();
        $isLoginSuccess = $authController->login($current_user->user_name, $data['currentpwd'], array('passwordEncrypted' => false));

        if ($isLoginSuccess) {
            $current_user->setNewPassword($data['newpwd']);
            return array(
                'status' => 'success',
                'msg' => 'new password set'
            );
        } else {
            return array(
                'status' => 'error',
                'msg' => 'current password not OK',
                'lbl' => 'MSG_CURRENT_PWD_NOT_OK'
            );
        }
    }


    public function get_all_user_preferences( $category)
    {
        global $current_user;
        require_once 'modules/UserPreferences/UserPreference.php';
        $userPreference = new UserPreference($current_user);

        $prefArray = array();

        $userPreference->loadPreferences($category);

        return $_SESSION[$current_user->user_name . '_PREFERENCES'][$category];
    }

    public function get_user_preferences( $category, $names)
    {
        global $current_user;
        require_once 'modules/UserPreferences/UserPreference.php';
        $userPreference = new UserPreference($current_user);

        $prefArray = array();

        $namesArray = json_decode($names);
        if (!is_array($namesArray))
            $namesArray = [$names];

        foreach ($namesArray as $name)
            $prefArray[$name] = $userPreference->getPreference($name, $category);

        return $prefArray;
    }

    public function set_user_preferences($category, $preferences)
    {
        global $current_user;
        require_once 'modules/UserPreferences/UserPreference.php';
        $userPreference = new UserPreference($current_user);
        $retData = array();
        // do the magic
        foreach ($preferences as $name => $value) {
            if ( $value === null ) $userPreference->deletePreference( $name, $category );
            else $userPreference->setPreference($name, $value, $category);
            if (( $memmy = $userPreference->getPreference( $name, $category )) !== null ) $retData[$name] = $memmy;
        }
        return $retData;
    }

    public function sendTokenToUser($email)
    {
        global $db, $timedate, $sugar_config;
        $result = array();

        $user_id = $this->getUserIdByEmail($email);

        if (!empty($user_id)) {
            $token = User::generatePassword();
            $db->query( sprintf('INSERT INTO users_password_tokens ( id, user_id, date_generated ) VALUES ( "%s", "%s", NOW() )', $db->quote( $token ), $db->quote( $user_id )));

            $emailTemp = new EmailTemplate();
            $emailTemp->retrieve($sugar_config['passwordsetting']['tokentmpl']);
            $emailTemp->disable_row_level_security = true;

            //replace instance variables in email templates
            $memmy = $emailTemp->parse( null, [ 'token' => $token ] );
            $emailTemp->body_html = $memmy['body_html'];
            $emailTemp->body = $memmy['body'];
            $emailTemp->subject = $memmy['subject'];

            $emailObj = new Email();

            $emailObj->name       = from_html($emailTemp->subject);
            $emailObj->body       = from_html($emailTemp->body_html);
            $emailObj->to_addrs   = $email;
            $emailObj->to_be_sent = true;
            $result = $emailObj->save();

            if ($result['result'] == true) {
                $emailObj->to_be_sent = false;
                $emailObj->team_id = 1;
                $emailObj->to_addrs = '';
                $emailObj->type = 'archived';
                $emailObj->deleted = '0';
                $emailObj->parent_type = 'User';
                $emailObj->mailbox_id = $sugar_config['passwordsetting']['mailbox'];
                $emailObj->date_sent = TimeDate::getInstance()->nowDb();
                $emailObj->modified_user_id = '1';
                $emailObj->created_by = '1';
                $emailObj->status = 'sent';
                $emailObj->save();
            }

            return $result;
        } else {
            return false;
        }
    }

    public function checkToken($email, $token)
    {
        global $db, $sugar_config;
        $user_id = $this->getUserIdByEmail($email);
        $token_valid = false;

        if ($user_id) {
            $res = $db->query( sprintf('SELECT * FROM users_password_tokens WHERE user_id = "%s" AND id = "%s" AND date_generated >= CURRENT_TIMESTAMP - INTERVAL ' . ( @$sugar_config['passwordsetting']['linkexpirationtime']*1 ) . ' MINUTE', $db->quote($user_id), $db->quote($token) ));
            while ($row = $db->fetchByAssoc($res)) $token_valid = true;
        }

        return $token_valid;
    }
    public function resetPass($data)
    {

        $user_id = $this->getUserIdByEmail($data['email']);
        $token_valid = $this->checkToken($data['email'], $data['token']);

        if ($token_valid) {
            $user = BeanFactory::getBean("Users", $user_id);
            $user->setNewPassword($data['password']);
            $accessLog = BeanFactory::getBean('UserAccessLogs');
            $accessLog->addRecord('pwdreset');
            return true;
        } else {
            return false;
        }
    }


    public function resetTempPass($data)
    {
        //for set new password for users with system generated passwords
        global $current_user;

        if (!empty($current_user->id)) {
            $current_user->setNewPassword($data['password'], 0);
            $accessLog = BeanFactory::getBean('UserAccessLogs');
            $accessLog->addRecord('pwdreset');
        }

        return true;
    }

    public function getUserIdByEmail($email)
    {
        global $db;
        $user_id = "";
        $res = $db->query( sprintf( 'SELECT u.id FROM users u INNER JOIN email_addr_bean_rel rel ON rel.bean_id = u.id AND rel.bean_module = "Users" AND rel.primary_address = 1 INNER JOIN email_addresses ea ON ea.id = rel.email_address_id AND ea.email_address_caps = "%s" WHERE u.deleted = 0 AND rel.deleted = 0 AND ea.deleted = 0', $db->quote( strtoupper( $email ))));
        while ($row = $db->fetchByAssoc($res)) $user_id = $row['id'];
        return $user_id;
    }
}
