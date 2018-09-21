<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$viewdefs['ProjectMilestones']['EditView'] = array(
    'templateMeta' => array(
        'form' => array(
			//'enctype'=> 'multipart/form-data',
            'buttons' => array('SAVE', 'CANCEL',)
        ),
        'maxColumns' => '2',
        'widths' => array(
            array('label' => '10', 'field' => '30'),
            array('label' => '10', 'field' => '30')
        ),
        'useTabs' => false,
        'tabDefs' => array(
            'LBL_MAINDATA' => array(
                'newTab' => true
            ),
            'LBL_PANEL_ASSIGNMENT' => array(
                'newTab' => true
            )
        ),
    ),
    'panels' => array(
        'LBL_MAINDATA' => array(
            array(
                array('name' => 'name'),
                array('name' => 'date_due'),
            ),
            array(
                array('name' => 'project_name'),
                null,
            ),
        ),
        'LBL_PANEL_ASSIGNMENT' => array(
            array(
                array('name' => 'assigned_user_name'),
                null,
            ),
        )
	)
);
