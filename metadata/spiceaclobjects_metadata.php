<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');


$dictionary ['SpiceACLModuleFields'] = array(
    'table' => 'spiceaclmodulefields',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'sysmodule_id' => array(
            'name' => 'sysmodule_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => 60
        ),
        'fieldname' => array(
            'name' => 'fieldname',
            'vname' => 'LBL_FIELDNAME',
            'type' => 'varchar',
            'len' => 60
        ),
        'relname' => array(
            'name' => 'relname',
            'vname' => 'LBL_RELNAME',
            'type' => 'varchar',
            'comment' => 'if we find throuhg a relationship',
            'len' => 60
        ),
        'addjoin' => array(
            'name' => 'addjoin',
            'vname' => 'LBL_ADDJOIN',
            'type' => 'text',
            'comment' => 'for custom coding iof we need to join fields'
        ),
        'addwhere' => array(
            'name' => 'addwhere',
            'vname' => 'LBL_ADDWHERE',
            'type' => 'text',
            'comment' => 'for custom coding iof we need a custom where statement'
        ),
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclmodulefields_pk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'spiceaclmodulefields_module',
            'type' => 'index',
            'fields' => array('sysmodule_id')
        )
    )
);


$dictionary ['SpiceACLStandardActions'] = array(
    'table' => 'spiceaclstandardactions',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
            'reportable' => false
        ),
        'action' => array(
            'name' => 'action',
            'vname' => 'LBL_ACTION',
            'type' => 'varchar',
            'len' => 50
        ),
        'displaysequence' => array(
            'name' => 'displaysequence',
            'vname' => 'LBL_DISPLAYSEQUENCE',
            'type' => 'int'
        ),
        'version' => array(
            'name' => 'version',
            'type' => 'varchar',
            'len' => 16
        ),
        'package' => array(
            'name' => 'package',
            'type' => 'varchar',
            'len' => 32
        )
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclstandardactions_pk',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary ['SpiceACLModuleActions'] = array(
    'table' => 'spiceaclmoduleactions',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
            'reportable' => false
        ),
        'sysmodule_id' => array(
            'name' => 'sysmodule_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false
        ),
        'action' => array(
            'name' => 'action',
            'vname' => 'LBL_ACTION',
            'type' => 'varchar',
            'len' => 50
        ),
        'shortcode' => array(
            'name' => 'shortcode',
            'vname' => 'LBL_SHORTCODE',
            'type' => 'varchar',
            'len' => 50
        ),
        'standardaction' => array(
            'name' => 'standardaction',
            'vname' => 'LBL_STANDARDACTION',
            'type' => 'varchar',
            'len' => 1
        )
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclmoduleactions_pk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'spiceaclmoduleactions_acltype_id',
            'type' => 'index',
            'fields' => array('sysmodule_id')
        )
    )
);

$dictionary ['SpiceACLObjectActions'] = array(
    'table' => 'spiceaclobjectactions',
    'fields' => array(
        'spiceaclobject_id' => array(
            'name' => 'spiceaclobject_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'spiceaclaction_id' => array(
            'name' => 'spiceaclaction_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclobjectactions_pk',
            'type' => 'unique',
            'fields' => array('spiceaclobject_id', 'spiceaclaction_id')
        )
    )
);

$dictionary ['SpiceACLObjectValues'] = array(
    'table' => 'spiceaclobjectvalues',
    'fields' => array(
        'spiceaclobject_id' => array(
            'name' => 'spiceaclobject_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'spiceaclmodulefield_id' => array(
            'name' => 'spiceaclmodulefield_id',
            'vname' => 'LBL_SPICEACLTYPE',
            'type' => 'varchar',
            'len' => 60),
        'operator' => array(
            'name' => 'operator',
            'vname' => 'LBL_OPERATOR',
            'type' => 'varchar',
            'len' => 2),
        'value1' => array(
            'name' => 'value1',
            'vname' => 'LBL_VALUE1',
            'type' => 'text'),
        'value2' => array(
            'name' => 'value2',
            'vname' => 'LBL_VALUE2',
            'type' => 'text'),
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclobjectvalues_pk',
            'type' => 'unique',
            'fields' => array('spiceaclobject_id', 'spiceaclmodulefield_id')
        )
    )
);

$dictionary ['SpiceACLObjectsTerritoryElementValues'] = array(
    'table' => 'spiceaclobjectsterritoryelementvalues',
    'fields' => array(
        'spiceaclobject_id' => array(
            'name' => 'spiceaclobject_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'spiceaclterritoryelement_id' => array(
            'name' => 'spiceaclterritoryelement_id',
            'required' => true,
            'type' => 'varchar',
            'len' => 60),
        'value' => array(
            'name' => 'value',
            'vname' => 'LBL_VALUE',
            'type' => 'text')
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclobjectsterritoryelementvalues_pk',
            'type' => 'unique',
            'fields' => array('spiceaclobject_id', 'spiceaclterritoryelement_id')
        )
    )
);

$dictionary ['SpiceACLObjectFields'] = array(
    'table' => 'spiceaclobjectfields',
    'fields' => array(
        'spiceaclobject_id' => array(
            'name' => 'spiceaclobject_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'field' => array(
            'name' => 'field',
            'vname' => 'LBL_FIELD',
            'type' => 'varchar',
            'len' => 60),
        'control' => array(
            'name' => 'control',
            'vname' => 'LBL_CONTROL',
            'type' => 'varchar',
            'len' => 1)
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclobjectfields_pk',
            'type' => 'unique',
            'fields' => array('spiceaclobject_id', 'field')
        )
    )
);

$dictionary['SpiceACLObjects_hash'] = array(
    'table' => 'spiceaclobjects_hash',
    'fields' => array(
        'hash_id' => array(
            'name' => 'hash_id',
            'type' => 'char',
            'required' => true,
            'isnull' => false,
            'len' => '36'),
        'spiceaclobject_id' => array(
            'name' => 'spiceaclobject_id',
            'type' => 'char',
            'required' => true,
            'isnull' => false,
            'len' => '36')
    ),
    'indices' => array(
        array(
            'name' => 'spiceaclobjects_hash_pk',
            'type' => 'unique',
            'fields' => array('hash_id', 'spiceaclobject_id')
        ),
        array(
            'name' => 'spiceaclobjects_hash_aclobject_id',
            'type' => 'index',
            'fields' => array('spiceaclobject_id')
        )
    )
);
