<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$subpanel_layout = array(
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
//        array(
//            'widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'ProjectActivities'
//        ),
    ),
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '15%',
        ),
        'date_start' => array(
            'vname' => 'LBL_DATE_START',
            'width' => '10%',
        ),
        'date_end' => array(
            'vname' => 'LBL_DATE_END',
            'width' => '10%',
        ),
        'assigned_user_name' => array(
            'vname' => 'LBL_ASSIGNED_TO_USER',
            'width' => '10%',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'width' => '2%',
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'width' => '2%',
        ),
    ),
);

