<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class KOrgObjectsViewEdit extends ViewEdit 
{   
 	public function __construct()
 	{
 		parent::__construct();
 		$this->useForSubpanel = true;
 		$this->useModuleQuickCreateTemplate = true;
 	}
 	
 	/**
 	 * @see SugarView::display()
	 * 
 	 * We are overridding the display method to manipulate the sectionPanels.
 	 * If portal is not enabled then don't show the Portal Information panel.
 	 */
 	public function display() 
 	{
 		 echo '<div id="extgrid"></div>';
       	 echo '<link rel="stylesheet" type="text/css" href="custom/kinamu/extjs/resources/css/ext-all-gray.css" />';
     	 echo '<script type="text/javascript" src="custom/kinamu/extjs/bootstrap.js"></script>';
		 echo "<script type='text/javascript' src='modules/KOrgObjects/javascript/editGrid.js'>";
 	}	
}