<?php

require_once('include/MVC/View/SugarView.php');

class KOrgObjectsViewManageKOrgModules extends SugarView {
	
 	function __construct(){
 		parent::__construct();
 	}

 	function display(){
 		 echo '<div id="extgrid"></div>';
       	 echo '<link rel="stylesheet" type="text/css" href="custom/kinamu/extjs/resources/css/ext-all-gray.css" />';
     	 echo '<script type="text/javascript" src="custom/kinamu/extjs/bootstrap.js"></script>';
		 echo "<script type='text/javascript' src='modules/KOrgObjects/javascript/manageKOrgModules.js'>";
 	}	
}
?>