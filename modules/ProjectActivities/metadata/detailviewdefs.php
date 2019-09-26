<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$viewdefs['ProjectActivities']['DetailView'] = array(
    'templateMeta' => array(
        'form' => array(
            'buttons' => array('EDIT', 'DUPLICATE', 'DELETE')
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
        // 'headerPanel' => 'modules/ProjectActivities/ProjectActivityGuide.php',
    ),
    'panels' => array(
        // 'helper' => 'modules/ProjectActivities/ProjectActivityGuide.php',
        'LBL_MAINDATA' => array(
            array(
                array('name' => 'name'),
                null,
            ),
            array(
                array('name' => 'date_start'),
                array('name' => 'date_end'),
            ),
            array(
                array('name' => 'project_name'),
                array('name' => 'projectwbs_name'),
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
