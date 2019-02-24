<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
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
********************************************************************************/




require_once('include/ListView/ListViewData.php');
require_once('include/MassUpdate.php');

class ListViewDisplay
{
    static $listViewCounter = 0;

	var $show_mass_update_form = false;
	var $show_action_dropdown = true;
	var $rowCount;
	var $mass = null;
	var $seed;
	var $multi_select_popup;
	var $lvd;
	var $moduleString;
	var $export = true;
	var $multiSelect = true;
	var $mailMerge = true;
	var $should_process = true;
	var $show_plus = false;
	/*
	 * Used in view.popup.php. Sometimes there are fields on the search form that are not referenced in the listviewdefs. If this
	 * is the case, then the filterFields will be set and the related fields will not be referenced when calling create_new_list_query.
	 */
	var $mergeDisplayColumns = false;
    public $actionsMenuExtraItems = array();

	/**
	 * Constructor
	 * @return null
	 */
	public function __construct()
    {
		$this->lvd = new ListViewData();
		$this->searchColumns = array () ;
	}

	function shouldProcess($moduleDir){
		$searching = false;
		$sessionSearchQuery = "{$moduleDir}2_QUERY_QUERY";
		if (!empty($_SESSION[$sessionSearchQuery])) {
			$searching = true;
		}
		if(!empty($GLOBALS['sugar_config']['save_query']) && $GLOBALS['sugar_config']['save_query'] == 'populate_only'){
		    if(empty($GLOBALS['displayListView']) 
		            && (!empty($_REQUEST['clear_query']) 
		                || $_REQUEST['module'] == $moduleDir 
		                    && ((empty($_REQUEST['query']) || $_REQUEST['query'] == 'MSI' )
		                        && (!$searching)))) {
				$_SESSION['last_search_mod'] = $_REQUEST['module'] ;
				$this->should_process = false;
				return false;
			}
		}
		$this->should_process = true;
		return true;
	}

	/**
	 * Setup the class
	 * @param seed SugarBean Seed SugarBean to use
	 * @param file File Template file to use
	 * @param string $where
	 * @param offset:0 int offset to start at
	 * @param int:-1 $limit
	 * @param string[]:array() $filter_fields
	 * @param array:array() $params
	 * 	Potential $params are
		$params['distinct'] = use distinct key word
		$params['include_custom_fields'] = (on by default)
		$params['massupdate'] = true by default;
        $params['handleMassupdate'] = true by default, have massupdate.php handle massupdates?
	 * @param string:'id' $id_field
	 */
	function setup($seed, $file, $where, $params = array(), $offset = 0, $limit = -1,  $filter_fields = array(), $id_field = 'id') {
        $this->should_process = true;
        if(isset($seed->module_dir) && !$this->shouldProcess($seed->module_dir)){
        		return false;
        }
        if(isset($params['export'])) {
          $this->export = $params['export'];
        }
        if(!empty($params['multiSelectPopup'])) {
		  $this->multi_select_popup = $params['multiSelectPopup'];
        }
		if(!empty($params['massupdate']) && $params['massupdate'] != false) {
			$this->show_mass_update_form = true;
			$this->mass = $this->getMassUpdate();
			$this->mass->setSugarBean($seed);
			if(!empty($params['handleMassupdate']) || !isset($params['handleMassupdate'])) {
                $this->mass->handleMassUpdate();
            }
		}
		$this->seed = $seed;

        $filter_fields = $this->setupFilterFields($filter_fields);

        $data = $this->lvd->getListViewData($seed, $where, $offset, $limit, $filter_fields, $params, $id_field);

        $this->fillDisplayColumnsWithVardefs();

		$this->process($file, $data, $seed->object_name);
		return true;
	}

	function setupFilterFields($filter_fields = array())
	{
		// create filter fields based off of display columns
        if(empty($filter_fields) || $this->mergeDisplayColumns) {
            foreach($this->displayColumns as $columnName => $def) {

               $filter_fields[strtolower($columnName)] = true;

            if(isset($this->seed->field_defs[strtolower($columnName)]['type']) &&
               strtolower($this->seed->field_defs[strtolower($columnName)]['type']) == 'currency' &&
               isset($this->seed->field_defs['currency_id'])) {
                    $filter_fields['currency_id'] = true;
            }

               if(!empty($def['related_fields'])) {
                    foreach($def['related_fields'] as $field) {
                        //id column is added by query construction function. This addition creates duplicates
                        //and causes issues in oracle. #10165
                        if ($field != 'id') {
                            $filter_fields[$field] = true;
                        }
                    }
                }
                if (!empty($this->seed->field_defs[strtolower($columnName)]['db_concat_fields'])) {
                    foreach($this->seed->field_defs[strtolower($columnName)]['db_concat_fields'] as $index=>$field){
                        if(!isset($filter_fields[strtolower($field)]) || !$filter_fields[strtolower($field)])
                        {
                            $filter_fields[strtolower($field)] = true;
                        }
                    }
                }
            }
            foreach ($this->searchColumns as $columnName => $def )
            {
                $filter_fields[strtolower($columnName)] = true;
            }
        }


        return $filter_fields;
	}


	/**
	 * Any additional processing
	 * @param file File template file to use
	 * @param data array row data
	 * @param html_var string html string to be passed back and forth
	 */
	function process($file, $data, $htmlVar) {
		$this->rowCount = count($data['data']);
		$this->moduleString = $data['pageData']['bean']['moduleDir'] . '2_' . strtoupper($htmlVar) . '_offset';
	}

	/**
	 * Display the listview
	 * @return string ListView contents
	 */
	public function display() 
	{
		if (!$this->should_process) {
		    return '';
		}
		
		$str = '';
		if ($this->show_mass_update_form) {
			$str = $this->mass->getDisplayMassUpdateForm(true, $this->multi_select_popup).$this->mass->getMassUpdateFormHeader($this->multi_select_popup);
		}
        
		return $str;
	}
	/**
	 * Display the select link
     * @return string select link html
	 * @param echo Bool set true if you want it echo'd, set false to have contents returned
	 */
	function buildSelectLink($id = 'select_link', $total=0, $pageTotal=0, $location="top") {
		global $app_strings;
		if ($pageTotal < 0)
			$pageTotal = $total;


        $total_label = "";
        if (!empty($GLOBALS['sugar_config']['disable_count_query']) && $GLOBALS['sugar_config']['disable_count_query'] === true && $total > $pageTotal) {
            $this->show_plus = true;
            $total_label =  $pageTotal.'+';
            $total = $pageTotal;
        } else {
            $total_label = $total;
        }

		$close_inline_img = SugarThemeRegistry::current()->getImage('close_inline', 'border=0', null, null, ".gif", $app_strings['LBL_CLOSEINLINE']);
		$menuItems = array(
            "<input title=\"".$app_strings['LBL_SELECT_ALL_TITLE']."\" type='checkbox' class='checkbox massall' name='massall' id='massall_".$location."' value='' onclick='sListView.check_all(document.MassUpdate, \"mass[]\", this.checked);' /><a id='$id'  href='javascript: void(0);'></a>",
            "<a  name='thispage' id='button_select_this_page_".$location."' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' onclick='if (document.MassUpdate.select_entire_list.value==1){document.MassUpdate.select_entire_list.value=0;sListView.check_all(document.MassUpdate, \"mass[]\", true, $pageTotal)}else {sListView.check_all(document.MassUpdate, \"mass[]\", true)};' href='#'>{$app_strings['LBL_LISTVIEW_OPTION_CURRENT']}&nbsp;&#x28;{$pageTotal}&#x29;&#x200E;</a>",
            "<a  name='selectall' id='button_select_all_".$location."' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' onclick='sListView.check_entire_list(document.MassUpdate, \"mass[]\",true,{$total});' href='#'>{$app_strings['LBL_LISTVIEW_OPTION_ENTIRE']}&nbsp;&#x28;{$total_label}&#x29;&#x200E;</a>",
            "<a name='deselect' id='button_deselect_".$location."' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' onclick='sListView.clear_all(document.MassUpdate, \"mass[]\", false);' href='#'>{$app_strings['LBL_LISTVIEW_NONE']}</a>",
        );

        $link = array(
            'class' => 'clickMenu selectmenu',
            'id' => 'selectLink',
            'buttons' => $menuItems,
            'flat' => false,
        );
        return $link;
	}

	/**
	 * Display the actions link
	 *
	 * @param  string $id link id attribute, defaults to 'actions_link'
	 * @return string HTML source
	 */
	protected function buildActionsLink($id = 'actions_link', $location = 'top')
	{
	    global $app_strings;
		$closeText = SugarThemeRegistry::current()->getImage('close_inline', 'border=0', null, null, ".gif", $app_strings['LBL_CLOSEINLINE']);
		$moreDetailImage = SugarThemeRegistry::current()->getImageURL('MoreDetail.png');
		$menuItems = array();

		// delete
		if ( $GLOBALS['ACLController']->checkAccess($this->seed->module_dir,'delete',true) && $this->delete )
			$menuItems[] = $this->buildDeleteLink($location);
		// compose email
        if ( $this->email )
			$menuItems[] = $this->buildComposeEmailLink($this->data['pageData']['offsets']['total'], $location);
		// mass update
		$mass = $this->getMassUpdate();
		$mass->setSugarBean($this->seed);
		if ( ( $GLOBALS['ACLController']->checkAccess($this->seed->module_dir,'edit',true) && $GLOBALS['ACLController']->checkAccess($this->seed->module_dir,'massupdate',true) ) && $this->showMassupdateFields && $mass->doMassUpdateFieldsExistForFocus() )
            $menuItems[] = $this->buildMassUpdateLink($location);
		// merge
		if ( $this->mailMerge )
		    $menuItems[] = $this->buildMergeLink(null, $location);
		if ( $this->mergeduplicates )
		    $menuItems[] = $this->buildMergeDuplicatesLink($location);
		// add to target list
		if ( $this->targetList && $GLOBALS['ACLController']->checkAccess('ProspectLists','edit',true) )
		    $menuItems[] = $this->buildTargetList($location);
		// export
                //BEGIN CORE MOFIFICATION maretval 2016-06-22: action still appears when disable_export ist true
                //ORIGINAL: if ( $GLOBALS['ACLController']->checkAccess($this->seed->module_dir,'export',true) && $this->export )
                if ( $GLOBALS['ACLController']->checkAccess($this->seed->module_dir,'export',true) && $this->export && !$GLOBALS['sugar_config']['disable_export'])
                //END
                    $menuItems[] = $this->buildExportLink($location);

		foreach ( $this->actionsMenuExtraItems as $item )
		    $menuItems[] = $item;

        $link = array(
            'class' => 'clickMenu selectActions fancymenu',
            'id' => 'selectActions',
            'name' => 'selectActions',
            'buttons' => $menuItems,
            'flat' => false,
        );
        return $link;

}
	/**
	 * Builds the export link
	 *
	 * @return string HTML
	 */
	protected function buildExportLink($loc = 'top')
	{
		global $app_strings;
		return "<a href='javascript:void(0)' id=\"export_listview_". $loc ." \" onclick=\"return sListView.send_form(true, '{$this->seed->module_dir}', 'index.php?entryPoint=export','{$app_strings['LBL_LISTVIEW_NO_SELECTED']}')\">{$app_strings['LBL_EXPORT']}</a>";
    }

	/**
	 * Builds the massupdate link
	 *
	 * @return string HTML
	 */
	protected function buildMassUpdateLink($loc = 'top')
	{
		global $app_strings;

        $onClick = "document.getElementById('massupdate_form').style.display = ''; var yLoc = YAHOO.util.Dom.getY('massupdate_form'); scroll(0,yLoc);";
		return "<a href='javascript:void(0)' id=\"massupdate_listview_". $loc ."\" onclick=\"$onClick\">{$app_strings['LBL_MASS_UPDATE']}</a>";

	}

	/**
	 * Builds the compose email link
	 *
	 * @return string HTML
	 */
	protected function buildComposeEmailLink($totalCount, $loc = 'top')
	{
		global $app_strings,$dictionary;

        if (!is_array($this->seed->field_defs)) {
            return '';
        }
        $foundEmailField = false;
        // Search for fields that look like an email address
        foreach ($this->seed->field_defs as $field) {
            if(isset($field['type'])&&$field['type']=='link'
               &&isset($field['relationship'])&&isset($dictionary[$this->seed->object_name]['relationships'][$field['relationship']])
               &&$dictionary[$this->seed->object_name]['relationships'][$field['relationship']]['rhs_module']=='EmailAddresses') {
                $foundEmailField = true;
                break;
            }
        }
        if (!$foundEmailField) {
            return '';
        }


		$userPref = $GLOBALS['current_user']->getPreference('email_link_type');
		$defaultPref = $GLOBALS['sugar_config']['email_default_client'];
		if($userPref != '')
			$client = $userPref;
		else
			$client = $defaultPref;

		if($client == 'sugar')
			$script = "<a href='javascript:void(0)' " .
                    "id=\"composeemail_listview_". $loc ."\"".
					'onclick="return sListView.send_form_for_emails(true, \''."Emails".'\', \'index.php?module=Emails&action=Compose&ListView=true\',\''.$app_strings['LBL_LISTVIEW_NO_SELECTED'].'\', \''.$this->seed->module_dir.'\', \''.$totalCount.'\', \''.$app_strings['LBL_LISTVIEW_LESS_THAN_TEN_SELECT'].'\')">' .
					$app_strings['LBL_EMAIL_COMPOSE'] . '</a>';
		else
			$script = "<a href='javascript:void(0)' " .
                    "id=\"composeemail_listview_". $loc ."\"".
					"onclick=\"return sListView.use_external_mail_client('{$app_strings['LBL_LISTVIEW_NO_SELECTED']}', '{$_REQUEST['module']}');\">" .
					$app_strings['LBL_EMAIL_COMPOSE'] . '</a>';

        return $script;
	} // fn
	/**
	 * Builds the delete link
	 *
	 * @return string HTML
	 */
	protected function buildDeleteLink($loc = 'top')
	{
		global $app_strings;
        return "<a href='javascript:void(0)' id=\"delete_listview_". $loc ."\" onclick=\"return sListView.send_mass_update('selected', '{$app_strings['LBL_LISTVIEW_NO_SELECTED']}', 1)\">{$app_strings['LBL_DELETE_BUTTON_LABEL']}</a>";
	}
	/**
	 * Display the selected object span object
	 *
     * @return string select object span
	 */
	function buildSelectedObjectsSpan($echo = true, $total=0) {
		global $app_strings;

        $displayStyle = $total > 0 ? "" : "display: none;";
		$selectedObjectSpan = "<span style='$displayStyle' id='selectedRecordsTop'>{$app_strings['LBL_LISTVIEW_SELECTED_OBJECTS']}<input  style='border: 0px; background: transparent; font-size: inherit; color: inherit' type='text' id='selectCountTop' readonly name='selectCount[]' value='{$total}' /></span>";

        return $selectedObjectSpan;
	}
    /**
	 * Builds the mail merge link
	 * The link can be disabled by setting module level duplicate_merge property to false
	 * in the moudle's vardef file.
	 *
	 * @return string HTML
	 */
	protected function buildMergeDuplicatesLink($loc = 'top')
	{
        global $app_strings, $dictionary;

        $return_string='';
        $return_string.= isset($_REQUEST['module']) ? "&return_module={$_REQUEST['module']}" : "";
        $return_string.= isset($_REQUEST['action']) ? "&return_action={$_REQUEST['action']}" : "";
        $return_string.= isset($_REQUEST['record']) ? "&return_id={$_REQUEST['record']}" : "";
        //need delete and edit access.
		if (!($GLOBALS['ACLController']->checkAccess($this->seed->module_dir, 'edit', true)) or !($GLOBALS['ACLController']->checkAccess($this->seed->module_dir, 'delete', true))) {
			return "";
		}

        if (isset($dictionary[$this->seed->object_name]['duplicate_merge']) && $dictionary[$this->seed->object_name]['duplicate_merge']==true ) {
            return "<a href='javascript:void(0)' ".
                            "id='mergeduplicates_listview_". $loc ."'".
                            "onclick='if (sugarListView.get_checks_count()> 1) {sListView.send_form(true, \"MergeRecords\", \"index.php\", \"{$app_strings['LBL_LISTVIEW_NO_SELECTED']}\", \"{$this->seed->module_dir}\",\"$return_string\");} else {alert(\"{$app_strings['LBL_LISTVIEW_TWO_REQUIRED']}\");return false;}'>".
                            $app_strings['LBL_MERGE_DUPLICATES'].'</a>';
        }

        return "";
     }
    /**
	 * Builds the mail merge link
	 *
	 * @return string HTML
	 */
	protected function buildMergeLink(array $modules_array = null, $loc = 'top')
	{
        if ( empty($modules_array) ) {
            require('modules/MailMerge/modules_array.php');
        }
        global $current_user, $app_strings;

        $admin = new Administration();
        $admin->retrieveSettings('system');
        $user_merge = $current_user->getPreference('mailmerge_on');
        $module_dir = (!empty($this->seed->module_dir) ? $this->seed->module_dir : '');
        $str = '';
        
        if ($user_merge == 'on' && isset($admin->settings['system_mailmerge_on']) && $admin->settings['system_mailmerge_on'] && !empty($modules_array[$module_dir])) {
            return "<a href='javascript:void(0)'  " .
                    "id='merge_listview_". $loc ."'"  .
					'onclick="if (document.MassUpdate.select_entire_list.value==1){document.location.href=\'index.php?action=index&module=MailMerge&entire=true\'} else {return sListView.send_form(true, \'MailMerge\',\'index.php\',\''.$app_strings['LBL_LISTVIEW_NO_SELECTED'].'\');}">' .
					$app_strings['LBL_MAILMERGE'].'</a>';
        }
        return $str;
	}

	/**
	 * Builds the add to target list link
	 *
     * @return string HTML
	 */
	protected function buildTargetList($loc = 'top')
	{
        global $app_strings;
		unset($_REQUEST[session_name()]);
		unset($_REQUEST['PHPSESSID']);
        $current_query_by_page = base64_encode(serialize($_REQUEST));

		$js = <<<EOF
            if(sugarListView.get_checks_count() < 1) {
                alert('{$app_strings['LBL_LISTVIEW_NO_SELECTED']}');
                return false;
            }
			if ( document.forms['targetlist_form'] ) {
				var form = document.forms['targetlist_form'];
				form.reset;
			} else
				var form = document.createElement ( 'form' ) ;
			form.setAttribute ( 'name' , 'targetlist_form' );
			form.setAttribute ( 'method' , 'post' ) ;
			form.setAttribute ( 'action' , 'index.php' );
			document.body.appendChild ( form ) ;
			if ( !form.module ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'module' );
			    input.setAttribute ( 'value' , '{$this->seed->module_dir}' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'action' );
			    input.setAttribute ( 'value' , 'TargetListUpdate' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.uids ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'uids' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.prospect_list ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'prospect_list' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.return_module ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'return_module' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.return_action ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'return_action' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.select_entire_list ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'select_entire_list' );
			    input.setAttribute ( 'value', document.MassUpdate.select_entire_list.value);
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			if ( !form.current_query_by_page ) {
			    var input = document.createElement('input');
			    input.setAttribute ( 'name' , 'current_query_by_page' );
			    input.setAttribute ( 'value', '{$current_query_by_page}' );
			    input.setAttribute ( 'type' , 'hidden' );
			    form.appendChild ( input ) ;
			}
			open_popup('ProspectLists','600','400','',true,false,{ 'call_back_function':'set_return_and_save_targetlist','form_name':'targetlist_form','field_to_name_array':{'id':'prospect_list'} } );
EOF;
        $js = str_replace(array("\r","\n"),'',$js);
        return "<a href='javascript:void(0)' id=\"targetlist_listview_". $loc ." \" onclick=\"$js\">{$app_strings['LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL']}</a>";
	}
	/**
	 * Display the bottom of the ListView (ie MassUpdate
	 * @return string contents
	 */
	public function displayEnd() 
	{
		$str = '';
		if($this->show_mass_update_form) {
			$str .= $this->mass->getMassUpdateForm(true);
			$str .= $this->mass->endMassUpdateForm();
		}

		return $str;
	}

    /**
     * Display the multi select data box etc.
     * @return string contents
     */
	public function getMultiSelectData() 
	{
		$str = "<script>YAHOO.util.Event.addListener(window, \"load\", sListView.check_boxes);</script>\n";

		$massUpdateRun = isset($_REQUEST['massupdate']) && $_REQUEST['massupdate'] == 'true';
		$uids = empty($_REQUEST['uid']) || $massUpdateRun ? '' : $_REQUEST['uid'];
        $select_entire_list = ($massUpdateRun) ? 0 : (isset($_POST['select_entire_list']) ? $_POST['select_entire_list'] : (isset($_REQUEST['select_entire_list']) ? $_REQUEST['select_entire_list'] : 0));

		$str .= "<textarea style='display: none' name='uid'>{$uids}</textarea>\n" .
				"<input type='hidden' name='select_entire_list' value='{$select_entire_list}'>\n".
				"<input type='hidden' name='{$this->moduleString}' value='0'>\n".
		        "<input type='hidden' name='show_plus' value='{$this->show_plus}'>\n";
		return $str;
	}

     /**
     * @return MassUpdate instance
     */
    protected function getMassUpdate()
    {
        return new MassUpdate();
    }

    /**
     * Fill displayColumns with additional field values from vardefs of the current bean seed.
     * We need vardefs to be in displayColumns for a further processing (e.g. in SugarField)
     * Similar vardef field values do not override field values from displayColumns, only necessary and missing ones are added
     */
    protected function fillDisplayColumnsWithVardefs()
    {
        foreach ($this->displayColumns as $columnName => $def) {
            $seedName =  strtolower($columnName);
            if (!empty($this->lvd->seed->field_defs[$seedName])) {
                $seedDef = $this->lvd->seed->field_defs[$seedName];
            }

            if (empty($this->displayColumns[$columnName]['type'])) {
                if (!empty($seedDef['type'])) {
                    $this->displayColumns[$columnName]['type'] = (!empty($seedDef['custom_type']))?$seedDef['custom_type']:$seedDef['type'];
                } else {
                    $this->displayColumns[$columnName]['type'] = '';
                }
            }//fi empty(...)

            if (!empty($seedDef['options'])) {
                $this->displayColumns[$columnName]['options'] = $seedDef['options'];
            }

            //C.L. Fix for 11177
            if ($this->displayColumns[$columnName]['type'] == 'html') {
                $cField = $this->seed->custom_fields;
                if (isset($cField) && isset($cField->bean->$seedName)) {
                    $seedName2 = strtoupper($columnName);
                    $htmlDisplay = html_entity_decode($cField->bean->$seedName);
                    $count = 0;
                    while ($count < count($data['data'])) {
                        $data['data'][$count][$seedName2] = &$htmlDisplay;
                        $count++;
                    }
                }
            }//fi == 'html'

            //Bug 40511, make sure relate fields have the correct module defined
            if ($this->displayColumns[$columnName]['type'] == "relate" && !empty($seedDef['link']) && empty( $this->displayColumns[$columnName]['module'])) {
                $link = $seedDef['link'];
                if (!empty($this->lvd->seed->field_defs[$link]) && !empty($this->lvd->seed->field_defs[$seedDef['link']]['module'])) {
                    $this->displayColumns[$columnName]['module'] = $this->lvd->seed->field_defs[$seedDef['link']]['module'];
                }
            }

            if (!empty($seedDef['sort_on'])) {
                $this->displayColumns[$columnName]['orderBy'] = $seedDef['sort_on'];
            }

            if (isset($seedDef)) {
                // Merge the two arrays together, making sure the seedDef doesn't override anything explicitly set in the displayColumns array.
                $this->displayColumns[$columnName] = $this->displayColumns[$columnName] + $seedDef;
            }

            //C.L. Bug 38388 - ensure that ['id'] is set for related fields
            if (!isset($this->displayColumns[$columnName]['id']) && isset($this->displayColumns[$columnName]['id_name'])) {
                $this->displayColumns[$columnName]['id'] = strtoupper($this->displayColumns[$columnName]['id_name']);
            }
        }
    }
}