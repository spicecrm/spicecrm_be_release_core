<?php

/**
 * Class SpiceACLHooks
 *
 * handles the vardefs, retirvbeal and storage fo the users hash
 */
class SpiceACLHooks
{

    public function hook_after_retrieve(&$bean, $event, $arguments)
    {
        if ( isset($bean->field_name_map['spiceacl_users_hash']) && !empty($bean->spiceacl_users_hash)) {
            $userManager = new \SpiceCRM\modules\SpiceACL\SpiceACLUsers();
            $bean->spiceacl_additional_users = json_encode($userManager->getHashUsers($bean->spiceacl_users_hash));
        }
    }

    public function hook_before_save(&$bean, $event, $arguments)
    {
        if ( isset($bean->field_name_map['spiceacl_users_hash'])) {

            if($bean->spiceacl_additional_users){

                $additonalUsers = json_decode($bean->spiceacl_additional_users);

                $users = [];
                foreach($additonalUsers as $additonalUser){
                    $users[]= $additonalUser->id;
                }

                $userManager = new \SpiceCRM\modules\SpiceACL\SpiceACLUsers();
                $bean->spiceacl_users_hash = $userManager->manageUsersHash($users);
            } else {
                $bean->spiceacl_users_hash = '';
            }
        }
    }

    public function hook_create_vardefs(&$bean, $event, $arguments)
    {
        if(!isset($GLOBALS['dictionary'][$bean->object_name]['templates']['spiceaclusers'])) {
            $loader = new \SpiceCRM\modules\SystemUI\KREST\controllers\SystemUIModulesController();
            $modules = $loader->geUnfilteredModules();
            if ($modules[$bean->module_dir]['acl_multipleusers'] == 1){
                VardefManager::addTemplate($bean->module_dir, $bean->object_name, 'spiceaclusers');
            }
        }
    }
}