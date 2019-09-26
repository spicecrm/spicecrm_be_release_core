<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
$module_name = 'ProjectWBSs';
$listViewDefs[$module_name] = array(
    'PROJECT_NAME' => array(
        'width' => '32',
        'label' => 'LBL_PROJECT_NAME',
        'default' => true,
        'link' => true,
        'id' => 'PROJECT_ID',
        'module' => 'Projects',
        'related_field' => array('project_id')
    ),
    'NAME' => array(
        'width' => '32',
        'label' => 'LBL_NAME',
        'default' => true,
        'link' => true
    ),
    'ASSIGNED_USER_NAME' => array(
        'width' => '9',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true
    ),
);