<?php
/*********************************************************************************
* This file is part of SpiceCRM. SpiceCRM is an enhancement of SugarCRM Community Edition
* and is developed by aac services k.s.. All rights are (c) 2016 by aac services k.s.
* You can contact us at info@spicecrm.io
* 
* SpiceCRM is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
* 
* SpiceCRM is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
********************************************************************************/

require_once('modules/SpiceThemeController/SpiceSideBarWidgets/SpiceSideBarWidget.php');
class SpiceSideBarWidgetReminders extends SpiceSideBarWidget {
	var $widget_name = "Reminders";
	public function display($closed = true){
//		require_once('include/SpiceReminders/SpiceReminders.php');

                $ss = new Sugar_Smarty();
                $ss->assign('closed', $closed);
                $ss->assign('reminders', SpiceCRM\includes\SpiceReminders\SpiceReminders::getReminders(5));
                return $ss->fetch('modules/SpiceThemeController/SpiceSideBarWidgets/tpls/SpiceSideBarWidgetReminders.tpl');

	}

	public function refresh(){
		return $this->display(false);
	}

	public static function getCount(){
//		require_once('include/SpiceReminders/SpiceReminders.php');
		$remindersCount = \SpiceCRM\includes\SpiceReminders\SpiceReminders::getReminderCount(5);
		return $remindersCount;
	}

	public static function getJsAfterLoad(){
		if(self::getCount()>0){
			require_once('modules/SpiceThemeController/SpiceThemeController.php');
			$user_toggle = SpiceThemeController::getToggle('Reminders');
			//return "toggled['Reminders'] = ".$user_toggle.";\nspicetheme.getToggle('Reminders');";
                        return "toggled['Reminders'] = ".(empty($user_toggle)?'false':$user_toggle).";" . ($user_toggle == 'true' ? "spicetheme.collapsedStatic['Reminders']=true;": "");
		}else{
			return '';
		}
	}
}