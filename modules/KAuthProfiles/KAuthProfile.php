<?php

require_once('data/SugarBean.php');
require_once('modules/KAuthProfiles/KAuthAccess.php');

class KAuthProfile extends SugarBean {

   public $table_name = 'kauthprofiles';
   public $object_name = 'KAuthProfile';
   public $module_dir = 'KAuthProfiles';
   public $created_by;
   public $id;
   public $deleted;
   public $date_entered;
   public $date_modified;
   public $modified_user_id;
   public $modified_by_name;

   public function __construct() {
      parent::__construct();
   }

   /*
    * return an array with the Profiles IDs for a given UserId
    */

   public function getProfilesForUser($userId) {
      
   }

   public function deactivate() {
      //skip the deactivation on the Object level for the profile
      /*
        global $db;
        // $db->query("DELETE FROM kauthprofiles_hash where kauthprofile_id='$this->id'");
        $objects = $db->query("SELECT * FROM kauthprofiles_kauthobjects WHERE kauthprofile_id='$this->id'");
        while($thisObject = $db->fetchByAssoc($objects))
        {
        $thisAuthObject = new KAuthObject($thisObject['kauthobject_id']);
        $thisAuthObject->deactivate($this->id);
        }

       */
      $this->status = 'd';

      return true;
   }

   /*
    * activate the profile
    */

   public function activate() {
      /*
        // do a hash matching between the profile and the hashes
        global $db;
        $queryArray = array();

        // remove all Hash entries
        $db->query("DELETE FROM kauthprofiles_hash where kauthprofile_id='$this->id'");

        // get the objects invloved
        $objects = $db->query("SELECT * FROM kauthprofiles_kauthobjects WHERE kauthprofile_id='$this->id'");
        while($thisObject = $db->fetchByAssoc($objects))
        {
        $thisAuthObject = new KAuthObject($thisObject['kauthobject_id']);
        $thisAuthObject->activate($this->id);
        }
       */
       $this->status = 'r';

       return true;
   }

   public function get_summary_text() {
      return $this->name;
   }

   public function bean_implements($interface) {
      switch ($interface) {
         case 'ACL':return false;
      }

      return false;
   }

}
