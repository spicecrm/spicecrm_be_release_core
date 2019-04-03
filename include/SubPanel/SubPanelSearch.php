<?php
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
namespace SpiceCRM\includes\SubPanel;

/**
 * 20reasons CR1000163
 * introduced in spicecrm 201903001
 * util Class for SubPanel search functionality
 */
class SubPanelSearch {

    /**
     * main Function to check what to display and get searchfield and/or limit DropDown for SubPanel Head (left byside Pagination)
     * former name TR_Subpanel_Utils_check_display
     * @param Object $subpanel_defs
     * @return string html-Code
     */
    public static function check_display($subpanel_defs){
        $html_text = "";
        $return = "";
        $search_config = null;

        if(isset($subpanel_defs->panel_definition['search_config'])){
            $search_config = $subpanel_defs->panel_definition['search_config'];
        }

        if(isset($subpanel_defs->_instance_properties['search_config'])){
            $search_config = $subpanel_defs->_instance_properties['search_config'];
        }

        if(is_array($search_config)) {

            if($search_config['searchable']){
                $html_text .= self::get_search_field($search_config,$subpanel_defs);
            }

            if($search_config['limit_dd']){
                $html_text .= self::get_limit_dd($search_config,$subpanel_defs, $subpanel_defs->parent_bean->module_name);
            }
        }

        if(!empty($html_text)){
            //$return = '<table class="edit" style="bottom: -9px; display: inline-block; position: relative; right: 10px; background: transparent;padding: 0px !important" cellspacing="0" cellpadding="0" border="0"> <tr> '.$html_text.' </tr> </table>';
            $return = '<table class="edit" style="bottom: -0px; display: inline-block; position: relative; background: transparent;padding: 0px !important" cellspacing="0" cellpadding="0" border="0" align=""> <tr> '.$html_text.' </tr> </table>';
        }

        return $return;
    }

    /**
     * Function to get Limit-DropDown with it's JS-Action to reload the SubPanel
     * former name TR_Subpanel_Utils_get_limit_dd
     * @param array $search_config
     * @param string $subpanel_name
     * @param string $parent_module
     * @return string html-code
     */
    public static function get_limit_dd($search_config, $subpanel_defs,$parent_module){
        $subpanel_name = $subpanel_defs->name;
        $dd_array = (is_array($GLOBALS['sugar_config']['subpanelsearch']['limit_dd_values']) ? $GLOBALS['sugar_config']['subpanelsearch']['limit_dd_values'] : array( '5' => '5', '10' => '10','20' => '20','30' => '30','50' => '50', '75' => '75','100' => '100'));


        if($search_config['limit_all']){ $dd_array['1000'] = 'all'; }
        if($search_config['searchable']){
            $js = "showSubPanel('${subpanel_name}',window.location.pathname+'?sugar_body_only=1&module='+$('form#formDetailView input[name=module]').val()+'&subpanel=${subpanel_name}&action=SubPanelViewer&inline=1&record='+$('form#formDetailView input[name=record]').val()+'&layout_def_key='+$('form#formDetailView input[name=module]').val()+'&ajaxSubpanel=true&'+$('form#formDetailView input[name=module]').val()+'_${subpanel_name}_CELL_offset=0&${subpanel_name}_limit='+$('#${subpanel_name}_limit_dd option:selected').val()+'&'+$('form#formDetailView input[name=module]').val()+'_${subpanel_name}_CELL_ORDER_BY=&sort_order=".(!empty($subpanel_defs->_instance_properties['sort_order']) ? $subpanel_defs->_instance_properties['sort_order'] : 'asc')."&to_pdf=true&action=SubPanelViewer&subpanel=${subpanel_name}&layout_def_key='+$('form#formDetailView input[name=module]').val()+'&search_subpanel=${subpanel_name}&search_subpanel_query_${subpanel_name}='+$('#subpanel_search_${subpanel_name}').val(),true);";
        }else{
            $js = "showSubPanel('${subpanel_name}',window.location.pathname+'?sugar_body_only=1&module='+$('form#formDetailView input[name=module]').val()+'&subpanel=${subpanel_name}&action=SubPanelViewer&inline=1&record='+$('form#formDetailView input[name=record]').val()+'&layout_def_key='+$('form#formDetailView input[name=module]').val()+'&ajaxSubpanel=true&'+$('form#formDetailView input[name=module]').val()+'_${subpanel_name}_CELL_offset=0&${subpanel_name}_limit='+$('#${subpanel_name}_limit_dd option:selected').val()+'&'+$('form#formDetailView input[name=module]').val()+'_${subpanel_name}_CELL_ORDER_BY=&sort_order=".(!empty($subpanel_defs->_instance_properties['sort_order']) ? $subpanel_defs->_instance_properties['sort_order'] : 'asc')."&to_pdf=true&action=SubPanelViewer&subpanel=${subpanel_name}&layout_def_key='+$('form#formDetailView input[name=module]').val(),true);";
        }

        $dd = '<td style="padding:0px !important;background: transparent;"><select name="limit_dd" id="'.$subpanel_name.'_limit_dd" onchange = "'.$js.'">';
        $selected_limit = self::get_limit($search_config,$subpanel_name,$parent_module);

        foreach($dd_array as $value => $label){
            if($selected_limit == $value){
                $selected = ' selected="selected"';
            }else{
                $selected = '';
            }
            $dd.= '<option value="'.$value.'"'.$selected.' label="'.$label.'">'.$label.'</option>';
        }

        $dd.= '</select></td>';

        return $dd;
    }

    /**
     * Function to get Search-Field with it's JS-Action to reload the SubPanel
     * former name TR_Subpanel_Utils_get_search_field
     * @param array $search_config
     * @param string $subpanel_name
     * @return string html-code
     */
    public static function get_search_field($search_config, $subpanel_defs){
        $subpanel_name = $subpanel_defs->name;
        global $sugar_config;
        require_once('include/SugarTheme/SugarTheme.php');

        if($search_config['limit_dd']){
            $js = "showSubPanel('${subpanel_name}',window.location.pathname+'?sugar_body_only=1&module='+$('form#formDetailView input[name=module]').val()+'&subpanel=${subpanel_name}&action=SubPanelViewer&inline=1&record='+$('form#formDetailView input[name=record]').val()+'&layout_def_key='+$('form#formDetailView input[name=module]').val()+'&ajaxSubpanel=true&'+$('form#formDetailView input[name=module]').val()+'_${subpanel_name}_CELL_offset=0&${subpanel_name}_limit='+$('#${subpanel_name}_limit_dd option:selected').val()+'&'+$('form#formDetailView input[name=module]').val()+'_${subpanel_name}_CELL_ORDER_BY=&sort_order=".(!empty($subpanel_defs->_instance_properties['sort_order']) ? $subpanel_defs->_instance_properties['sort_order'] : 'asc')."&to_pdf=true&action=SubPanelViewer&subpanel=${subpanel_name}&layout_def_key='+$('form#formDetailView input[name=module]').val()+'&search_subpanel=${subpanel_name}&search_subpanel_query_${subpanel_name}='+$('#subpanel_search_${subpanel_name}').val(),true);";
        }else{
            $js = "showSubPanel('${subpanel_name}',window.location.pathname+'?sugar_body_only=1&module='+$('form#formDetailView input[name=module]').val()+'&subpanel=${subpanel_name}&action=SubPanelViewer&inline=1&record='+$('form#formDetailView input[name=record]').val()+'&layout_def_key='+$('form#formDetailView input[name=module]').val()+'&ajaxSubpanel=true&'+$('form#formDetailView input[name=module]').val()+'_${subpanel_name}_CELL_offset=0&${subpanel_name}_limit=${sugar_config['list_max_entries_per_subpanel']}&'+$('form#formDetailView input[name=module]').val()+'_${subpanel_name}_CELL_ORDER_BY=&sort_order=".(!empty($subpanel_defs->_instance_properties['sort_order']) ? $subpanel_defs->_instance_properties['sort_order'] : 'asc')."&to_pdf=true&action=SubPanelViewer&subpanel=${subpanel_name}&layout_def_key='+$('form#formDetailView input[name=module]').val()+'&search_subpanel=${subpanel_name}&search_subpanel_query_${subpanel_name}='+$('#subpanel_search_${subpanel_name}').val(),true);";
        }

        if(isset($_REQUEST['search_subpanel_query_'.$subpanel_name]) && !empty($_REQUEST['search_subpanel_query_'.$subpanel_name])){
            $search_string = $_REQUEST['search_subpanel_query_'.$subpanel_name];
        }else{
            $search_string ="";
        }

        $search_field = '<td style="padding:0px !important; background: transparent;"> <input onkeypress="SubPanelSearchCheckEnter(event)" name="subpanel_search_'.$subpanel_name.'" id="subpanel_search_'.$subpanel_name.'" value="'.$search_string.'" size="20" type="text" /> &nbsp; <button class="button" onclick="'.$js.'" title="Search" name="subpanel_search_button_'.$subpanel_name.'" type="button">'.\SugarThemeRegistry::current()->getImage("searchMore","border='0' align='absmiddle'",null,null,'.gif','Search').'</button></td>';
        $search_field.= '<script type="text/javascript">
	SubPanelSearchCheckEnter = function(event) {
		  if (event===undefined) event= window.event; // for IE
		  if (event.keyCode===13) {
		  	'.$js.'
		  }
		};
	</script>';

        return $search_field;
    }

    /**
     * Function to get the actual Limit and handle saving of the Limit to user-config
     * former name TR_Subpanel_Utils_get_limit
     * @param array $search_config
     * @param string $subpanel_name
     * @param string $parent_module
     * @return number
     */
    public static function get_limit($search_config, $subpanel_name = "", $parent_module = ""){
        $limit = -1;
        $user_limit = "";

        if($search_config['limit_save']){
            global $current_user;
            if(!empty($current_user)) {
                $user_limit = $current_user->getPreference($parent_module."_".$subpanel_name.'_limit_save');
            }
        }

        if(isset($_REQUEST[$subpanel_name.'_limit']) && !empty($_REQUEST[$subpanel_name.'_limit'])){
            $limit = $_REQUEST[$subpanel_name.'_limit'];

            if($search_config['limit_save']){
                global $current_user;
                if(!empty($current_user)) {
                    $current_user->setPreference($parent_module."_".$subpanel_name.'_limit_save', $_REQUEST[$subpanel_name.'_limit']);
                    $current_user->savePreferencesToDB();
                }
            }
        }elseif(!empty($user_limit)){
            $limit = $user_limit;
        }elseif(isset($search_config['limit'])){
            $limit = $search_config['limit'];
        }

        return $limit;
    }

    /**
     * Function to generate the Where-Condition for the search-field
     * former name TR_Subpanel_Utils_get_searchfield_where
     * @param array $search_config
     * @param string $subpanel_name
     * @return string
     */
    public static function get_searchfield_where($search_config, $subpanel_name){
        $where = "";

        if(!empty($_REQUEST['search_subpanel_query_'.$subpanel_name])){
            $where .= "(";

            foreach($search_config['search_fields'] as $field_name){
                $custom_search_where_array[] = " ".$field_name." LIKE '%".$_REQUEST['search_subpanel_query_'.$subpanel_name]."%' ";
            }

            $custom_search_where = implode(' OR ', $custom_search_where_array);
            $where .= $custom_search_where.")";
        }
        return $where;
    }

}