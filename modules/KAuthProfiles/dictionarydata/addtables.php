<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

$dictionary ['KAuthTypes'] = array(
    'table' => 'kauthtypes',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'bean' => array(
            'name' => 'bean',
            'vname' => 'LBL_BEAN',
            'type' => 'varchar',
            'len' => 60),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'len' => 1,
            'options' => 'kauthprofiles_status'),
    ),
    'indices' => array(
        array(
            'name' => 'id',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary ['KAuthTypeFields'] = array(
    'table' => 'kauthtypefields',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'kauthtype_id' => array(
            'name' => 'kauthtype_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => 60),
        'fieldname' => array(
            'name' => 'fieldname',
            'vname' => 'LBL_FIELDNAME',
            'type' => 'varchar',
            'len' => 60),
        'relname' => array(
            'name' => 'relname',
            'vname' => 'LBL_RELNAME',
            'type' => 'varchar',
            'comment' => 'if we find throuhg a relationship',
            'len' => 60),
        'addjoin' => array(
            'name' => 'addjoin',
            'vname' => 'LBL_ADDJOIN',
            'type' => 'text',
            'comment' => 'for custom coding iof we need to join fields'),
        'addwhere' => array(
            'name' => 'addwhere',
            'vname' => 'LBL_ADDWHERE',
            'type' => 'text',
            'comment' => 'for custom coding iof we need a custom where statement'),
    ),
    'indices' => array(
        array(
            'name' => 'id',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'kauthtype_id',
            'type' => 'index',
            'fields' => array('kauthtype_id')
        )
    )
);

$dictionary ['KAuthTypeActions'] = array(
    'table' => 'kauthtypeactions',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'kauthtype_id' => array(
            'name' => 'kauthtype_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'action' => array(
            'name' => 'action',
            'vname' => 'LBL_ACTION',
            'type' => 'varchar',
            'len' => 10),
        'shortcode' => array(
            'name' => 'shortcode',
            'vname' => 'LBL_SHORTCODE',
            'type' => 'varchar',
            'len' => 10),
        'standardaction' => array(
            'name' => 'standardaction',
            'vname' => 'LBL_STANDARDACTION',
            'type' => 'varchar',
            'len' => 1)
    ),
    'indices' => array(
        array(
            'name' => 'id',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'kauthtype_id',
            'type' => 'index',
            'fields' => array('kauthtype_id')
        )
    )
);

$dictionary ['KAuthObjects'] = array(
    'table' => 'kauthobjects',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'kauthtype_id' => array(
            'name' => 'kauthtype_id',
            'vname' => 'LBL_KAUTHTYPE',
            'required' => true,
            'type' => 'varchar',
            'len' => 60),
        'kauthobjecttype' => array(
            'name' => 'kauthobjecttype',
            'vname' => 'LBL_KAUTHOBJECTTYPE',
            'type' => 'varchar',
            'len' => 1),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'len' => 150),
        'description' => array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text'),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'len' => 1,
            'options' => 'kauthprofiles_status'),
        'kauthorgassignment' => array(
            'name' => 'kauthorgassignment',
            'vname' => 'LBL_ORGASSIGNMENT',
            'type' => 'varchar',
            'len' => 2
        ),
        'kauthowner' => array(
            'name' => 'kauthowner',
            'vname' => 'LBL_KAUTHOWNER',
            'type' => 'bool',
            'default' => false
        ),
        'allorgobjects' => array(
            'name' => 'allorgobjects',
            'vname' => 'LBL_ALLORGOBJECTS',
            'type' => 'bool',
            'default' => false
        ),
        'activity' => array(
            'name' => 'activity',
            'vname' => 'LBL_ACTIVITY',
            'type' => 'varchar',
            'len' => 36),
        /*
          'customactivity' => array(
          'name' => 'customactivity',
          'vname' => 'LBL_CUSTOMACTIVITY',
          'type' => 'text'), */
        'customSQL' => array(
            'name' => 'customSQL',
            'vname' => 'LBL_CUSTOMSQL',
            'type' => 'text'
        )
    ),
    'indices' => array(
        array(
            'name' => 'id',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary ['KAuthTypeActionsKauthObjects'] = array(
    'table' => 'kauthtypeactions_kauthobjects',
    'fields' => array(
        'kauthobject_id' => array(
            'name' => 'kauthobject_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'kauthaction_id' => array(
            'name' => 'kauthaction_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
    ),
    'indices' => array(
        array(
            'name' => 'kauthtypeactionskauthobjects',
            'type' => 'unique',
            'fields' => array('kauthobject_id', 'kauthaction_id')
        )
    )
);

$dictionary ['KAuthObjectValues'] = array(
    'table' => 'kauthobjectvalues',
    'fields' => array(
        'kauthobject_id' => array(
            'name' => 'kauthobject_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'kauthtypefield_id' => array(
            'name' => 'kauthtypefield_id',
            'vname' => 'LBL_KAUTHTYPE',
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
            'name' => 'id',
            'type' => 'unique',
            'fields' => array('kauthobject_id', 'kauthtypefield_id')
        )
    )
);

$dictionary ['KAuthObjectOrgElementValues'] = array(
    'table' => 'kauthobjectorgelementvalues',
    'fields' => array(
        'kauthobject_id' => array(
            'name' => 'kauthobject_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'korgobjectelement_id' => array(
            'name' => 'korgobjectelement_id',
            'vname' => 'LBL_ORGOBJECTELEMENTID',
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
            'name' => 'id',
            'type' => 'unique',
            'fields' => array('kauthobject_id', 'korgobjectelement_id')
        )
    )
);

$dictionary ['KAuthObjectFields'] = array(
    'table' => 'kauthobjectfields',
    'fields' => array(
        'kauthobject_id' => array(
            'name' => 'kauthobject_id',
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
            'name' => 'id',
            'type' => 'unique',
            'fields' => array('kauthobject_id', 'field')
        )
    )
);

$dictionary ['KAuthProfilesKAuthObjects'] = array(
    'table' => 'kauthprofiles_kauthobjects',
    'fields' => array(
        'kauthobject_id' => array(
            'name' => 'kauthobject_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
        'kauthprofile_id' => array(
            'name' => 'kauthprofile_id',
            'type' => 'id',
            'required' => true,
            'reportable' => false),
    ),
    'indices' => array(
        array(
            'name' => 'id',
            'type' => 'unique',
            'fields' => array('kauthobject_id', 'kauthprofile_id')
        )
    )
);

$dictionary['kauthobjects_hash'] = array(
    'table' => 'kauthobjects_hash',
    'fields' => array(
        'hash_id' => array(
            'name' => 'hash_id',
            'type' => 'char',
            'required' => true,
            'isnull' => false,
            'len' => '36'),
        'kauthobject_id' => array(
            'name' => 'kauthobject_id',
            'type' => 'char',
            'required' => true,
            'isnull' => false,
            'len' => '36'),
        'korgobjecttype_id' => array(
            'name' => 'korgobjecttype_id',
            'type' => 'char',
            'required' => true,
            'len' => '36')
    ),
    'indices' => array(
        array(
            'name' => 'kauthobjects_hashpk',
            'type' => 'primary',
            'fields' => array('hash_id', 'kauthobject_id')
        ),
        array(
            'name' => 'kauthprofiles_pk',
            'type' => 'index',
            'fields' => array('kauthobject_id')
        )
    )
);

$dictionary['kauthprofiles_users'] = array(
    'table' => 'kauthprofiles_users',
    'fields' => array(
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'varchar',
            'required' => true,
            'isnull' => false,
            'len' => '36'),
        'kauthprofile_id' => array(
            'name' => 'kauthprofile_id',
            'type' => 'varchar',#
            'required' => true,
            'isnull' => false,
            'len' => '36'),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => '0')
    ),
    'indices' => array(
        array(
            'name' => 'kauthprofiles_userid',
            'type' => 'index',
            'fields' => array('user_id')
        ),
        array(
            'name' => 'kauthprofiles_userspk',
            'type' => 'primary',
            'fields' => array('user_id', 'kauthprofile_id')
        )
    )
);
?>
