<?php
namespace SpiceCRM\modules\SpiceACL;

class SpiceACLUsers{
    function manageUsersHash($users){
        global $db;

        // sort the users array and build the hash
        sort($users);
        $spiceacl_users_hash = md5(implode('', $users));

        // check if the hash exists
        $result = $db->fetchByAssoc($db->query("SELECT count(*) hashcount FROM spiceaclusers_hash WHERE hash_id = '$spiceacl_users_hash' AND deleted = 0"));
        if($result['hashcount'] == 0){
            $values = [];
            foreach($users as $user){
                $values[] = "('$spiceacl_users_hash', '$user', '0')";
            }
            $db->query("INSERT INTO spiceaclusers_hash (hash_id, user_id, deleted) VALUES " . implode(',', $values));
        }

        return $spiceacl_users_hash;
    }

    function getHashUsers($hash_id){
        global $db;
        $usersArray = [];
        $usersObject = $db->query("SELECT user_id FROM spiceaclusers_hash WHERE hash_id = '$hash_id' AND deleted = 0");
        while($userId = $db->fetchByAssoc($usersObject)){
            $user = \BeanFactory::getBean('Users', $userId['user_id']);
            $usersArray[] = [
                'id' => $user->id,
                'summary_text' => $user->summary_text,
            ];
        }
        return $usersArray;
    }

    /**
     * adds the assigned users to the array
     *
     * @param $bean the bean
     * @return array the fields to be added to the account
     */
    static function addFTSData($bean){
        global $db;
        if(empty($bean->spiceacl_users_hash)) return [];

        $ftArray = [
            'assigned_user_ids' => []
        ];
        $usersObject = $db->query("SELECT user_id FROM spiceaclusers_hash WHERE hash_id = '$bean->spiceacl_users_hash' AND deleted = 0");
        while($userId = $db->fetchByAssoc($usersObject)){
            $ftArray[assigned_user_ids][] = $userId['user_id'];
        }
        return $ftArray;
    }

    static function generateCurrentUserWhereClause($table_name = '', $bean){
        global $current_user;

        if(empty($table_name)) $table_name = $bean->table_name;

        $whereClauses[] = "$table_name.assigned_user_id = '$current_user->id'";

        if ( isset($bean->field_name_map['spiceacl_users_hash'])) {
            $whereClauses[] = "$table_name.spiceacl_users_hash IN (SELECT hash_id FROM spiceaclusers_hash WHERE user_id = '$current_user->id')";
        }

        return implode(' OR ', $whereClauses);
    }


    /**
     * cheks if the passed in bean matches the user requirements
     *
     * @param $bean the bean to be checked
     * @return bool true if access is granted and the current user is consideren an owner
     */
    static function checkCurrentUserIsOwner($bean){
        global $db, $current_user;

        // check the assigned user first
        if($bean->assigned_user_id == $current_user->id) return true;

        // check if we have  user hash
        if(empty($bean->spiceacl_users_hash)) return false;

        // check the user hash
        return $db->fetchByAssoc($db->query("SELECT user_id FROM spiceaclusers_hash WHERE hash_id = '$bean->spiceacl_users_hash' AND user_id='$current_user->id' AND deleted = 0")) ? true : false;
    }
}
