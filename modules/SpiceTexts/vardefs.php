<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$dictionary['SpiceText'] = array(
    'table' => 'spicetexts',
    'comment' => 'SpiceTexts Module',
    'audited' =>  false,
    'duplicate_merge' =>  false,
    'unified_search' =>  false,

	'fields' => array(
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => '100',
            'required' => false
        ),
        'description' => array (
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
            'required' => true
        ),
        'parent_type' => [
            'name'     => 'parent_type',
            'vname'    => 'LBL_PARENT_TYPE',
            'type'     => 'parent_type',
            'dbType'   => 'varchar',
            'required' => false,
            'group'    => 'parent_name',
            'options'  => 'parent_type_display',
            'len'      => 255,
        ],
        'parent_name' => [
            'name'        => 'parent_name',
            'parent_type' => 'record_type_display',
            'type_name'   => 'parent_type',
            'id_name'     => 'parent_id',
            'vname'       => 'LBL_RELATED_TO',
            'type'        => 'parent',
            'group'       => 'parent_name',
            'source'      => 'non-db',
            'options'     => 'parent_type_display',
        ],
        'parent_id' => [
            'name'       => 'parent_id',
            'vname'      => 'LBL_LIST_RELATED_TO_ID',
            'type'       => 'id',
            'group'      => 'parent_name',
            'reportable' => false,
        ],
        'text_id' => array (
            'name' => 'text_id',
            'vname' => 'LBL_TEXT',
            'type' => 'id',
            'required' => true
        ),
        'text_language' => array (
            'name' => 'text_language',
            'vname' => 'LBL_TEXT_LANGUAGE',
            'type' => 'varchar',
            'len' => '55',
            'required' => true
        ),
        'products' => [
            'name'         => 'products',
            'type'         => 'link',
            'relationship' => 'product_spicetexts',
            'source'       => 'non-db',
            'vname'        => 'LBL_PRODUCTS',
            'module'       => 'Products',
            'default'      => true,
        ],
        'productvariants' => [
            'name'         => 'productvariants',
            'type'         => 'link',
            'relationship' => 'productvariant_spicetexts',
            'source'       => 'non-db',
            'vname'        => 'LBL_PRODUCT_VARIANTS',
            'module'       => 'ProductVariants',
            'default'      => true,
        ],
	),
	'relationships' => array(
	),
	'indices' => array(
        array('name' => 'idx_spicetexts_id_del', 'type' => 'index', 'fields' => array('id', 'deleted')),
        array('name' => 'idx_spicetexts_parentid_del', 'type' => 'index', 'fields' => array('parent_id', 'deleted'))
	)
);

VardefManager::createVardef('SpiceTexts', 'SpiceText', array('default', 'assignable'));
