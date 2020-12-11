<?php
/**
 * CR1000108
 * Tables for Spice variable definitions
 */
$dictionary['sysdomaindefinitions'] = [
    'table' => 'sysdomaindefinitions',
    'comment' => 'something like sugar var types',
    'audited' => false,
    'fields' =>
        [
            'id' => [
                'name' => 'id',
                'type' => 'id'
            ],
            'name' => [
                'name' => 'name',
                'type' => 'varchar',
                'len' => 100
            ],
//            'sysdomainvalidation_id' => [
//                'name' => 'sysdomainvalidation_id',
//                'type' => 'id',
//            ],
            'fieldtype' => [
                'name' => 'fieldtype',
                'type' => 'varchar',
                'len' => 50
            ],
            'description' => [
                'name' => 'description',
                'type' => 'text'
            ],
            'version' => [
                'name' => 'version',
                'type' => 'varchar',
                'len' => 16
            ],
            'package' => [
                'name' => 'package',
                'type' => 'varchar',
                'len' => 32
            ],
            'status' => [
                'name' => 'status',
                'type' => 'varchar',
                'len' => 1,
                'default' => 'd',
                'comment' => 'the status of the item, d for draft, a for active, i for inactive'
            ],
            'deleted' => [
                'name' => 'deleted',
                'type' => 'bool',
                'default' => 0
            ]
        ],
    'indices' => [
        [
            'name' => 'sysdomaindefinitionspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['syscustomdomaindefinitions'] = [
    'table' => 'syscustomdomaindefinitions',
    'comment' => 'something like sugar var types',
    'audited' => false,
    'fields' => $dictionary['sysdomaindefinitions']['fields'],
    'indices' => [
        [
            'name' => 'syscustomdomaindefinitionspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['sysdomainfields'] = [
    'table' => 'sysdomainfields',
    'audited' => false,
    'comment' => 'something like database var types',
    'fields' =>
        [
            'id' => [
                'name' => 'id',
                'type' => 'id',
                'comment' => 'ID for this row'
            ],
            'name' => [
                'name' => 'name',
                'type' => 'varchar',
                'comment' => 'abstract definition for field name to apply when creating field'
            ],
            'sequence' => [
                'name' => 'sequence',
                'type' => 'int',
                'len' => 4
            ],
            'dbtype' => [
                'name' => 'dbtype',
                'type' => 'varchar',
                'len' => 50,
                'comment' => 'field type in database table'
            ],
            'fieldtype' => [
                'name' => 'fieldtype',
                'type' => 'varchar',
                'len' => 50,
                'comment' => 'field type in crm display'
            ],
            'len' => [
                'name' => 'len',
                'type' => 'varchar',
                'len' => 32,
                'comment' => 'field length in database table'
            ],
            'required' => [
                'name' => 'required',
                'type' => 'bool',
                'default' => 0,
                'comment' => 'field is required'
            ],
            'sysdomaindefinition_id' => [
                'name' => 'sysdomaindefinition_id',
                'type' => 'id',
                'comment' => 'id of related sysdomaindefinition'
            ],
            'sysdomainfieldvalidation_id' => [
                'name' => 'sysdomainfieldvalidation_id',
                'type' => 'id',
                'comment' => 'id of related sysdomainfieldvalidation'
            ],
            'description' => [
                'name' => 'description',
                'type' => 'text'
            ],
            'version' => [
                'name' => 'version',
                'type' => 'varchar',
                'len' => 16
            ],
            'package' => [
                'name' => 'package',
                'type' => 'varchar',
                'len' => 32
            ],
            'status' => [
                'name' => 'status',
                'type' => 'varchar',
                'len' => 1,
                'default' => 'd',
                'comment' => 'the status of the item, d for draft, a for active, i for inactive'
            ],
            'deleted' => [
                'name' => 'deleted',
                'type' => 'bool',
                'default' => 0
            ]
        ],
    'indices' => [
        ['name' => 'sysdomainfieldspk', 'type' => 'primary', 'fields' => ['id']],
        ['name' => 'idx_sysdomainfields_sysdodefid', 'type' => 'index', 'fields' => ['sysdomaindefinition_id']],
        ['name' => 'idx_sysdomainfields_sysdofldvalid', 'type' => 'index', 'fields' => ['sysdomainfieldvalidation_id']],
    ]
];
$dictionary['syscustomdomainfields'] = [
    'table' => 'syscustomdomainfields',
    'audited' => false,
    'comment' => 'something like database var types',
    'fields' => $dictionary['sysdomainfields']['fields'],
    'indices' => [
        ['name' => 'syscustomdomainfieldspk', 'type' => 'primary', 'fields' => ['id']],
        ['name' => 'idx_syscustomdomainfields_sysdodefid', 'type' => 'index', 'fields' => ['sysdomaindefinition_id']],
        ['name' => 'idx_syscustomdomainfields_sysdofldvalid', 'type' => 'index', 'fields' => ['sysdomainfieldvalidation_id']],
    ]
];


$dictionary['sysdomainfieldvalidations'] = [
    'table' => 'sysdomainfieldvalidations',
    'comment' => 'holding enum values',
    'audited' => false,
    'fields' =>
        [
            'id' => [
                'name' => 'id',
                'type' => 'id',
                'comment' => 'ID for this row'
            ],
            'name' => [
                'name' => 'name',
                'type' => 'varchar',
                'len' => 100
            ],
            'validation_type' => [
                'name' => 'validation_type',
                'type' => 'varchar',
                'len' => 32,
                'comment' => 'possible values: options|range'
            ],
            'operator' => [
                'name' => 'operator',
                'type' => 'varchar',
                'len' => 32
            ],
//            'function_name' => [
//                'name' => 'function_name',
//                'type' => 'varchar',
//                'len' => '100'
//            ],
            'order_by' => [
                'name' => 'order_by',
                'type' => 'varchar'
            ],
            'sort_flag' => [
                'name' => 'sort_flag',
                'type' => 'varchar',
                'len' => 5,
            ],
            'description' => [
                'name' => 'description',
                'type' => 'text'
            ],
            'version' => [
                'name' => 'version',
                'type' => 'varchar',
                'len' => 16
            ],
            'package' => [
                'name' => 'package',
                'type' => 'varchar',
                'len' => 32
            ],
            'status' => [
                'name' => 'status',
                'type' => 'varchar',
                'len' => 1,
                'default' => 'd',
                'comment' => 'the status of the item, d for draft, a for active, i for inactive'
            ],
            'deleted' => [
                'name' => 'deleted',
                'type' => 'bool',
                'default' => 0
            ]
        ],
    'indices' => [
        [
            'name' => 'sysdomainfieldvalidationspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['syscustomdomainfieldvalidations'] = [
    'table' => 'syscustomdomainfieldvalidations',
    'audited' => false,
    'fields' => $dictionary['sysdomainfieldvalidations']['fields'],
    'indices' => [
        [
            'name' => 'syscustomdomainfieldvalidationspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['sysdomainfieldvalidationvalues'] = [
    'table' => 'sysdomainfieldvalidationvalues',
    'audited' => false,
    'fields' =>
        [
            'id' => [
                'name' => 'id',
                'type' => 'id'
            ],
            'sysdomainfieldvalidation_id' => [
                'name' => 'sysdomainfieldvalidation_id',
                'type' => 'id',
            ],
            'minvalue' => [
                'name' => 'minvalue',
                'type' => 'varchar',
                'len' => 160
            ],
            'maxvalue' => [
                'name' => 'maxvalue',
                'type' => 'varchar',
                'len' => 160
            ],
            'sequence' => [
                'name' => 'sequence',
                'type' => 'int',
                'len' => 4
            ],
            'label' => [
                'name' => 'label',
                'type' => 'varchar',
            ],
            'description' => [
                'name' => 'description',
                'type' => 'text'
            ],
            'version' => [
                'name' => 'version',
                'type' => 'varchar',
                'len' => 16
            ],
            'package' => [
                'name' => 'package',
                'type' => 'varchar',
                'len' => 32
            ],
            'status' => [
                'name' => 'status',
                'type' => 'varchar',
                'len' => 1,
                'default' => 'd',
                'comment' => 'the status of the item, d for draft, a for active, i for inactive'
            ],
            'deleted' => [
                'name' => 'deleted',
                'type' => 'bool',
                'default' => 0
            ]
        ],
    'indices' => [
        [
            'name' => 'sysdomainfieldvalidationvaluespk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['syscustomdomainfieldvalidationvalues'] = [
    'table' => 'syscustomdomainfieldvalidationvalues',
    'audited' => false,
    'fields' => $dictionary['sysdomainfieldvalidationvalues']['fields'],
    'indices' => [
        [
            'name' => 'syscustomdomainfieldvalidationvaluespk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];


$dictionary['sysdictionarydefinitions'] = [
    'table' => 'sysdictionarydefinitions',
    'audited' => false,
    'fields' =>
        [
            'id' => [
                'name' => 'id',
                'type' => 'id'
            ],
            'name' => [
                'name' => 'name',
                'type' => 'varchar',
                'len' => 100
            ],
            'tablename' => [
                'name' => 'tablename',
                'type' => 'varchar',
                'len' => 200
            ],
            'audited' => [
                'name' => 'audited',
                'type' => 'bool',
                'default' => '1'
            ],
//            'sysdictionarydefinition_id' => [
//                'name' => 'sysdictionarydefinition_id',
//                'type' => 'id',
//            ],
            'sysdictionary_type' => [
                'name' => 'sysdictionary_type',
                'type' => 'enum',
            ],
            'description' => [
                'name' => 'description',
                'type' => 'text'
            ],
            'version' => [
                'name' => 'version',
                'type' => 'varchar',
                'len' => 16
            ],
            'package' => [
                'name' => 'package',
                'type' => 'varchar',
                'len' => 32
            ],
            'status' => [
                'name' => 'status',
                'type' => 'varchar',
                'len' => 1,
                'default' => 'd',
                'comment' => 'the status of the item, d for draft, a for active, i for inactive'
            ],
            'deleted' => [
                'name' => 'deleted',
                'type' => 'bool',
                'default' => 0
            ]
    ],
    'indices' => [
        [
            'name' => 'sysdictionarydefinitionspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['syscustomdictionarydefinitions'] = [
    'table' => 'syscustomdictionarydefinitions',
    'audited' => false,
    'fields' => $dictionary['sysdictionarydefinitions']['fields'],
    'indices' => [
        [
            'name' => 'syscustomdictionarydefinitionspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['sysdictionaryitems'] = [
    'table' => 'sysdictionaryitems',
    'audited' => false,
    'fields' =>
        [
            'id' => [
                'name' => 'id',
                'type' => 'id'
            ],
            'name' => [
                'name' => 'name',
                'type' => 'varchar',
                'len' => 50,
                'comment' => 'field name'
            ],
            'sysdictionarydefinition_id' => [
                'name' => 'sysdictionarydefinition_id',
                'type' => 'id',
                'comment' => 'parent dictionary'
            ],
            'sysdictionary_ref_id' => [
                'name' => 'sysdictionary_ref_id',
                'type' => 'id',
                'comment' => 'include other dictionary definition'
            ],
            'sysdomaindefinition_id' => [
                'name' => 'sysdomaindefinition_id',
                'type' => 'id',
            ],
            'label' => [
                'name' => 'label',
                'type' => 'varchar',
                'len' => 50
            ],
            'non_db' => [
                'name' => 'non_db',
                'type' => 'bool',
            ],
            'exclude_from_audited' => [
                'name' => 'exclude_from_audited',
                'type' => 'bool',
            ],
            'required' => [
                'name' => 'required',
                'type' => 'bool',
                'default' => '0'
            ],
            'default_value' => [
                'name' => 'default_value',
                'type' => 'varchar',
                'comment' => 'The default value to set when field is empty'
            ],
            'field_comment' => [
                'name' => 'field_comment',
                'type' => 'varchar',
            ],
            'sequence' => [
                'name' => 'sequence',
                'type' => 'int',
                'comment' => 'the sequence to sort on for a readable table struture'
            ],
            'description' => [
                'name' => 'description',
                'type' => 'text'
            ],
            'version' => [
                'name' => 'version',
                'type' => 'varchar',
                'len' => 16
            ],
            'package' => [
                'name' => 'package',
                'type' => 'varchar',
                'len' => 32
            ],
            'status' => [
                'name' => 'status',
                'type' => 'varchar',
                'len' => 1,
                'default' => 'd',
                'comment' => 'the status of the item, d for draft, a for active, i for inactive'
            ],
            'deleted' => [
                'name' => 'deleted',
                'type' => 'bool',
                'default' => 0
            ]
        ],
    'indices' => [
        [
            'name' => 'sysdictionaryitemspk',
            'type' => 'primary',
            'fields' => ['id']
        ],

    ]
];
$dictionary['syscustomdictionaryitems'] = [
    'table' => 'syscustomdictionaryitems',
    'audited' => false,
    'fields' => $dictionary['sysdictionaryitems']['fields'],
    'indices' => [
        [
            'name' => 'syscustomdictionaryitemspk',
            'type' => 'primary',
            'fields' => ['id']
        ],

    ]
];

$dictionary['sysdictionaryindexes'] = [
    'table' => 'sysdictionaryindexes',
    'audited' => false,
    'fields' =>
        [
            'id' => [
                'name' => 'id',
                'type' => 'id'
            ],
            'name' => [
                'name' => 'name',
                'type' => 'varchar',
                'len' => 100
            ],
            'sysdictionarydefinition_id' => [
                'name' => 'sysdictionarydefinition_id',
                'type' => 'id',
            ],
            'indextype' => [
                'name' => 'indextype',
                'type' => 'varchar',
                'len' => 32
            ],
            'description' => [
                'name' => 'description',
                'type' => 'text'
            ],
            'version' => [
                'name' => 'version',
                'type' => 'varchar',
                'len' => 16
            ],
            'package' => [
                'name' => 'package',
                'type' => 'varchar',
                'len' => 32
            ],
            'status' => [
                'name' => 'status',
                'type' => 'varchar',
                'len' => 1,
                'default' => 'd',
                'comment' => 'the status of the item, d for draft, a for active, i for inactive'
            ],
            'deleted' => [
                'name' => 'deleted',
                'type' => 'bool',
                'default' => 0
            ]
        ],
    'indices' => [
        [
            'name' => 'sysdictionaryindexespk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['syscustomdictionaryindexes'] = [
    'table' => 'syscustomdictionaryindexes',
    'audited' => false,
    'fields' => $dictionary['sysdictionaryindexes']['fields'],
    'indices' => [
        [
            'name' => 'syscustomdictionaryindexespk',
            'type' => 'primary',
            'fields' => ['id']
        ],

    ]
];


$dictionary['sysdictionaryindexitems'] = [
    'table' => 'sysdictionaryindexitems',
    'audited' => false,
    'fields' =>
        [
            'id' => [
                'name' => 'id',
                'type' => 'id'
            ],
            'sysdictionaryindex_id' => [
                'name' => 'sysdictionaryindex_id',
                'type' => 'varchar',
                'len' => 36
            ],
            'sysdictionaryitem_id' => [
                'name' => 'sysdictionaryitem_id',
                'type' => 'varchar',
                'len' => 36
            ],
            'sequence' => [
                'name' => 'sequence',
                'type' => 'int',
                'len' => 4
            ],
            'description' => [
                'name' => 'description',
                'type' => 'text'
            ],
            'version' => [
                'name' => 'version',
                'type' => 'varchar',
                'len' => 16
            ],
            'package' => [
                'name' => 'package',
                'type' => 'varchar',
                'len' => 32
            ],
            'status' => [
                'name' => 'status',
                'type' => 'varchar',
                'len' => 1,
                'default' => 'd',
                'comment' => 'the status of the item, d for draft, a for active, i for inactive'
            ],
            'deleted' => [
                'name' => 'deleted',
                'type' => 'bool',
                'default' => 0
            ]
        ],
    'indices' => [
        [
            'name' => 'sysdictionaryindexitemspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['syscustomdictionaryindexitems'] = [
    'table' => 'syscustomdictionaryindexitems',
    'audited' => false,
    'fields' => $dictionary['sysdictionaryindexitems']['fields'],
    'indices' => [
        [
            'name' => 'syscustomdictionaryindexitemspk',
            'type' => 'primary',
            'fields' => ['id']
        ],
    ]
];

$dictionary['sysdictionaryrelationships'] = [
    'table' => 'sysdictionaryrelationships',
    'fields' => [
        'id' => [
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
        ],
        'name' => [
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'required'=>true,
            'len' => 150,
            'comment' => 'the logical name for the relationship'
        ],
        'relationship_name' => [
            'name' => 'relationship_name',
            'vname' => 'LBL_RELATIONSHIP_NAME',
            'type' => 'varchar',
            'required'=>true,
            'len' => 150,
            'comment' => 'the technical name for the relationship'
        ],
//        'sysdictionarydefinition_id' => [
//            'name' => 'sysdictionarydefinition_id',
//            'vname' => 'LBL_SYSDICTIONARY_ID',
//            'type' => 'id',
//            'required' => true,
//            'comment' => 'Dictionary ID'
//        ],
//        'lhs_module' => [
//            'name' => 'lhs_module',
//            'vname' => 'LBL_LHS_MODULE',
//            'type' => 'varchar',
//            'required'=>true,
//            'len' => 100,
//            'comment' => 'deprecated Replaced by rhs_sysdictionarydefinition_id'
//        ],
//        'lhs_table' => [
//            'name' => 'lhs_table',
//            'vname' => 'LBL_LHS_TABLE',
//            'type' => 'varchar',
//            'required'=>true,
//            'len' => 64,
//            'comment' => 'deprecated. Replaced by lhs_sysdictionarydefinition_id'
//        ],
        'lhs_sysdictionarydefinition_id' => [
            'name' => 'lhs_sysdictionarydefinition_id',
            'vname' => 'LBL_LHS_TABLE',
            'type' => 'id',
            'required' => true,
            'comment' => 'Dictionary reference for left side'
        ],
//        'lhs_key' => [
//            'name' => 'lhs_key',
//            'vname' => 'LBL_LHS_KEY',
//            'type' => 'varchar',
//            'required'=>true,
//            'len' => 64,
//            'comment' => '@deprecated. Will be replaced by lhs_sysdictionaryitem_id'
//        ],
        'lhs_sysdictionaryitem_id' => [
            'name' => 'lhs_sysdictionaryitem_id',
            'vname' => 'LBL_LHS_KEY',
            'type' => 'id',
            'required' => true,
            'comment' => 'dictionary item id corresponding to key in table'
        ],
        'lhs_linkname' => [
            'name' => 'lhs_linkname',
            'vname' => 'LBL_LHS_LINKNAME',
            'type' => 'varchar',
            'required' => false,
            'len' => 100
        ],
//        'rhs_module' => [
//            'name' => 'rhs_module',
//            'vname' => 'LBL_RHS_MODULE',
//            'type' => 'varchar',
//            'required'=>true,
//            'len' => 100,
//            'comment' => 'deprecated Replaced by rhs_sysdictionarydefinition_id'
//        ],
//        'rhs_table' => [
//            'name' => 'rhs_table',
//            'vname' => 'LBL_RHS_TABLE',
//            'type' => 'varchar',
//            'required'=>true,
//            'len' => 64,
//            'comment' => 'deprecated. Replaced by rhs_sysdictionarydefinition_id'
//        ],
        'rhs_sysdictionarydefinition_id' => [
            'name' => 'rhs_sysdictionarydefinition_id',
            'vname' => 'LBL_RHS_TABLE',
            'type' => 'id',
            'required' => true,
            'comment' => 'Dictionary reference for right side'
        ],
//        'rhs_key' => [
//            'name' => 'rhs_key',
//            'vname' => 'LBL_RHS_KEY',
//            'type' => 'varchar',
//            'required'=>true,
//            'len' => 64,
//            'comment' => '@deprecated. Replaced by rhs_sysdictionaryitem_id'
//        ],
        'rhs_sysdictionaryitem_id' => [
            'name' => 'rhs_sysdictionaryitem_id',
            'vname' => 'LBL_RHS_KEY',
            'type' => 'id',
            'required' => false,
            'comment' => 'dictionary item id corresponding to key in table'
        ],
        'rhs_sysdictionaryitem_name' => [
            'name' => 'rhs_sysdictionaryitem_name',
            'vname' => 'LBL_RHS_KEY_NAME',
            'type' => 'varchar',
            'required' => false,
            'comment' => 'dictionary item name corresponding to field name of the key in table'
        ],
        'rhs_linkname' => [
            'name' => 'rhs_linkname',
            'vname' => 'LBL_RHS_LINKNAME',
            'type' => 'varchar',
            'required' => false,
            'len' => 100
        ],
        'rhs_relatename' => [
            'name' => 'rhs_relatename',
            'vname' => 'LBL_RHS_RELATENAME',
            'type' => 'varchar',
            'comment' => 'name of non db field for relate'
        ],
//        'join_table' => [
//            'name' => 'join_table',
//            'vname' => 'LBL_JOIN_TABLE',
//            'type' => 'varchar',
//            'len' => 64,
//            'comment' => '@deprecated.Replaced by join_sysdictionary_id'
//		],
		'join_sysdictionarydefinition_id' => [
            'name' => 'join_sysdictionarydefinition_id',
            'vname' => 'LBL_JOIN_TABLE',
            'type' => 'id',
            'comment' => 'metadata definition is now to be found in a dictionary'
        ],
//        'join_key_lhs' => [
//            'name' => 'join_key_lhs',
//            'vname' => 'LBL_JOIN_KEY_LHS',
//            'type' => 'varchar',
//            'len' => 64,
//            'comment' => '@deprecated. Will be replaced by join_lhs_sysdictionary_id'
//        ],
        'join_lhs_sysdictionaryitem_id' => [
            'name' => 'join_lhs_sysdictionaryitem_id',
            'vname' => 'LBL_DICTIONARYITEM_ID',
            'type' => 'id',
            'comment' => 'dictionary item id corresponding join key in join table'
        ],
//        'join_key_rhs' => [
//            'name' => 'join_key_rhs',
//            'vname' => 'LBL_JOIN_KEY_RHS',
//            'type' => 'varchar',
//            'len' => 64,
//            'comment' => '@deprecated. Will be replaced by join_rhs_sysdictionary_id'
//        ],
        'join_rhs_sysdictionaryitem_id' => [
            'name' => 'join_rhs_sysdictionaryitem_id',
            'vname' => 'LBL_DICTIONARYITEM_ID',
            'type' => 'id',
            'comment' => 'dictionary item id corresponding join key in join table'
        ],
        'relationship_type' => [
            'name' => 'relationship_type',
            'vname' => 'LBL_RELATIONSHIP_TYPE',
            'type' => 'enum',
            'options' => 'relationship_type_dom',
            'len' => 32
        ],
        'relationship_role_column' => [
            'name' => 'relationship_role_column',
            'vname' => 'LBL_RELATIONSHIP_ROLE_COLUMN',
            'type' => 'varchar',
            'len' => 64
        ],
        'relationship_role_column_value' => [
            'name' => 'relationship_role_column_value',
            'vname' => 'LBL_RELATIONSHIP_ROLE_COLUMN_VALUE',
            'type' => 'varchar',
            'len' => 50
        ],
        'reverse' => [
            'name' => 'reverse',
            'vname' => 'LBL_REVERSE',
            'type' => 'bool',
            'default' => '0'
        ],
        'status' => [
            'name' => 'status',
            'type' => 'varchar',
            'len' => 1,
            'default' => 'd',
            'comment' => 'the status of the item, d for draft, a for active, i for inactive'
        ],
        'deleted' => [
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'reportable'=>false,
            'default' => '0'
        ],
        'description' => [
            'name' => 'description',
            'type' => 'text'
        ],
        'version' => [
            'name' => 'version',
            'type' => 'varchar',
            'len' => 16
        ],
        'package' => [
            'name' => 'package',
            'type' => 'varchar',
            'len' => 32
        ]
    ],
    'indices' => [
        ['name' =>'sysdictionaryrelationshipspk', 'type' =>'primary', 'fields'=>['id']],
        ['name' =>'idx_sysdictionaryrelationship_name', 'type' =>'index', 'fields'=>['relationship_name']],
    ]
];


$dictionary['syscustomdictionaryrelationships'] = [
    'table' => 'syscustomdictionaryrelationships',
    'fields' => $dictionary['sysdictionaryrelationships']['fields'],
    'indices' => [
        ['name' =>'syscustomdictionaryrelationshipspk', 'type' =>'primary', 'fields'=>['id']],
        ['name' =>'idx_syscustomdictionaryrelationships_name', 'type' =>'index', 'fields'=>['relationship_name']],
    ]
];


$dictionary['sysdictionaryrelationshipfields'] = [
    'table' => 'sysdictionaryrelationshipfields',
    'comment' => 'represents former rel_fields attribute in link',
    'fields' => [
        'id' => [
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
        ],
        'sysdictionaryrelationship_id' => [
            'name' => 'sysdictionaryrelationship_id',
            'vname' => 'LBL_RELATIONSHIP_ID',
            'type' => 'char',
            'len' => '36',
            'comment' => 'id of relationship'
        ],
        'map_to_fieldname' => [
            'name' => 'map_to_fieldname',
            'vname' => 'LBL_MAP_TO_FIELDNAME',
            'type' => 'varchar',
            'comment' => 'name of the non-db field from  sysdictionarydefinition_id'
        ],
        'sysdictionarydefinition_id' => [
            'name' => 'sysdictionarydefinition_id',
            'vname' => 'LBL_SYSDICTIONARYDEFINITIONS_ID',
            'type' => 'char',
            'len' => '36',
            'comment' => 'the dictionary ID for left or right module'
        ],
        'sysdictionaryitem_id' => [
            'name' => 'sysdictionaryitem_id',
            'vname' => 'LBL_SYSDICTIONARYITEM_ID',
            'type' => 'char',
            'len' => '36',
            'comment' => 'the field item to map relationship_fieldname to'
        ],
        'description' => [
            'name' => 'description',
            'type' => 'text'
        ],
        'version' => [
            'name' => 'version',
            'type' => 'varchar',
            'len' => 16
        ],
        'package' => [
            'name' => 'package',
            'type' => 'varchar',
            'len' => 32
        ],
        'status' => [
            'name' => 'status',
            'type' => 'varchar',
            'len' => 1,
            'default' => 'd',
            'comment' => 'the status of the item, d for draft, a for active, i for inactive'
        ],
        'deleted' => [
            'name' => 'deleted',
            'type' => 'bool',
            'default' => 0
        ]
    ],
    'indices' => [
        ['name' =>'sysdictionaryrelationshipfieldspk', 'type' =>'primary', 'fields'=>['id']],
    ]
];

$dictionary['syscustomdictionaryrelationshipfields'] = [
    'table' => 'syscustomdictionaryrelationshipfields',
    'fields' => $dictionary['sysdictionaryrelationshipfields']['fields'],
    'indices' => [
        ['name' =>'syscustomdictionaryrelationshipfieldspk', 'type' =>'primary', 'fields'=>['id']],
    ]
];

$dictionary['sysdictionaryrelationshiprelatefields'] = [
    'table' => 'sysdictionaryrelationshiprelatefields',
    'comment' => 'represents former db_concat_fields attribute in relate name',
    'fields' => [
        'id' => [
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
        ],
        'sysdictionaryrelationship_id' => [
            'name' => 'sysdictionaryrelationship_id',
            'vname' => 'LBL_RELATIONSHIP_ID',
            'type' => 'varchar',
            'comment' => 'id of relationship'
        ],
//        'related_table_field' => [
//            'name' => 'related_table_field',
//            'vname' => 'LBL_RELATED_TABLE_FIELD',
//            'type' => 'id',
//            'comment' => 'the field name in related table to retrieve from'
//        ],
        'sysdictionaryitem_id' => [
            'name' => 'sysdictionaryitem_id',
            'type' => 'id'
        ],
        'description' => [
            'name' => 'description',
            'type' => 'text'
        ],
        'sequence' => [
            'name' => 'sequence',
            'type' => 'int',
            'len' => 4
        ],
        'version' => [
            'name' => 'version',
            'type' => 'varchar',
            'len' => 16
        ],
        'package' => [
            'name' => 'package',
            'type' => 'varchar',
            'len' => 32
        ],
        'status' => [
            'name' => 'status',
            'type' => 'varchar',
            'len' => 1,
            'default' => 'd',
            'comment' => 'the status of the item, d for draft, a for active, i for inactive'
        ],
        'deleted' => [
            'name' => 'deleted',
            'type' => 'bool',
            'default' => 0
        ]
    ],
    'indices' => [
        ['name' =>'sysdictionaryrelationshiprelatefieldspk', 'type' =>'primary', 'fields'=>['id']],
    ]
];
$dictionary['syscustomdictionaryrelationshiprelatefields'] = [
    'table' => 'syscustomdictionaryrelationshiprelatefields',
    'fields' => $dictionary['sysdictionaryrelationshiprelatefields']['fields'],
    'indices' => [
        ['name' =>'syscustomdictionaryrelationshiprelatefieldspk', 'type' =>'primary', 'fields'=>['id']],
    ]
];
