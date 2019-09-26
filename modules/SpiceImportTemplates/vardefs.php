<?php 
 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['SpiceImportTemplate'] = array(
    'table' => 'spiceimporttemplates',
    'comment' => 'SpiceImportTemplates Module',
    'duplicate_merge' =>  false,
    'unified_search' =>  false,
    'audited' =>  false,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'mappings' => array(
            'name' => 'mappings',
            'type' => 'text'
        ),
        'fixed' => array(
            'name' => 'fixed',
            'type' => 'text'
        ),
        'checks' => array(
            'name' => 'checks',
            'type' => 'text'
        )
    ),
    'indices' => array(

    )
);

VardefManager::createVardef('SpiceImportTemplates', 'SpiceImportTemplate', array('default', 'assignable'));
