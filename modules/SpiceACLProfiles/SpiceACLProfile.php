<?php

require_once('data/SugarBean.php');

class SpiceACLProfile extends SugarBean {

   public $table_name = 'spiceaclprofiles';
   public $object_name = 'SpiceACLProfile';
   public $module_dir = 'SpiceACLProfiles';


   public function __construct() {
      parent::__construct();
   }

   /*
    * return an array with the Profiles IDs for a given UserId
    */

   public function getProfilesForUser($userId) {
      
   }

   public function deactivate() {
      $this->status = 'd';

      return true;
   }

   /*
    * activate the profile
    */

   public function activate() {
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
