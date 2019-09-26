<?php

$dictionary['systextids_modules'] = array(
    'table' => 'systextids_modules',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'text_id' => array(
            'name' => 'text_id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 255
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_systextids_modules',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_systextids_modules_text_id',
            'type' => 'index',
            'fields' => array('text_id'))
    )
);

$dictionary['syscustomtextids_modules'] = array(
    'table' => 'syscustomtextids_modules',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'text_id' => array(
            'name' => 'text_id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 255
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_systextids_modules',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_systextids_modules_text_id',
            'type' => 'index',
            'fields' => array('text_id'))
    )
);
$dictionary['systextids'] = array(
    'table' => 'systextids',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'text_id' => array(
            'name' => 'text_id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 255
        ),
        'label' => array(
            'name' => 'label',
            'type' => 'varchar',
            'len' => 255
        ),
        'text_type' => array(
            'name' => 'text_type',
            'type' => 'varchar',
            'len' => '255'
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_systextids',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_systextids_text_id',
            'type' => 'index',
            'fields' => array('text_id'))
    )
);
$dictionary['syscustomtextids'] = array(
    'table' => 'syscustomtextids',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'text_id' => array(
            'name' => 'text_id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 255
        ),
        'label' => array(
            'name' => 'label',
            'type' => 'varchar',
            'len' => 255
        ),
        'text_type' => array(
            'name' => 'text_type',
            'type' => 'varchar',
            'len' => '255'
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_systextids',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_systextids_text_id',
            'type' => 'index',
            'fields' => array('text_id'))
    )
);

$dictionary['sysuipackagerepositories'] = array(
    'table' => 'sysuipackagerepositories',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'url' => array(
            'name' => 'url',
            'type' => 'varchar',
            'len' => 100
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
    ),
    'indices' => array(
        array(
            'name' => 'idx_sysuipackagerepositories',
            'type' => 'primary',
            'fields' => array('id'))
    )
);


$dictionary['sysuiloadtasks'] = array(
    'table' => 'sysuiloadtasks',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'display' => array(
            'name' => 'display',
            'type' => 'varchar',
            'len' => 100
        ),
        'phase' => array(
            'name' => 'phase',
            'type' => 'varchar',
            'len' => 10
        ),
        'sequence' => array(
            'name' => 'sequence',
            'type' => 'varchar',
            'len' => 10
        ),
        'route' => array(
            'name' => 'route',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuiloadtasks',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary['sysuiloadtaskitems'] = array(
    'table' => 'sysuiloadtaskitems',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'sysuiloadtasks_id' => array(
            'name' => 'sysuiloadtasks_id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'method' => array(
            'name' => 'method',
            'type' => 'varchar',
            'len' => 150
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
            'name' => 'idx_sysuiloadtaskitems',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);


$dictionary['sysuicustomloadtasks'] = array(
    'table' => 'sysuicustomloadtasks',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'display' => array(
            'name' => 'display',
            'type' => 'varchar',
            'len' => 100
        ),
        'phase' => array(
            'name' => 'phase',
            'type' => 'varchar',
            'len' => 10
        ),
        'sequence' => array(
            'name' => 'sequence',
            'type' => 'varchar',
            'len' => 10
        ),
        'route' => array(
            'name' => 'route',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuiloadtasks',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);


$dictionary['sysuicustomloadtaskitems'] = array(
    'table' => 'sysuicustomloadtaskitems',
    'audited' => true,
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'sysuiloadtasks_id' => array(
            'name' => 'sysuiloadtasks_id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'method' => array(
            'name' => 'method',
            'type' => 'varchar',
            'len' => 150
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
            'name' => 'idx_sysuicustomloadtaskitems',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary['sysuicalendars'] = array(
    'table' => 'sysuicalendars',
    'audited' => true,
    'fields' =>
        array(
            'id' => array(
                'name' => 'id',
                'type' => 'id'
            ),
            'name' => array(
                'name' => 'name',
                'type' => 'varchar',
                'len' => 100
            ),
            'icon' => array(
                'name' => 'icon',
                'type' => 'varchar',
                'len' => 50
            ),
            'is_default' => array(
                'name' => 'is_default',
                'type' => 'int',
                'len' => 1
            ),
        ),
);

$dictionary['sysuicalendaritems'] = array(
    'table' => 'sysuicalendaritems',
    'fields' =>
        array(
            'id' => array(
                'name' => 'id',
                'type' => 'id'
            ),
            'name' => array(
                'name' => 'name',
                'type' => 'varchar',
                'len' => 100
            ),
            'module' => array(
                'name' => 'module',
                'type' => 'varchar',
                'len' => 50
            ),
            'type' => array(
                'name' => 'type',
                'vname' => 'LBL_TYPE',
                'type' => 'enum',
                'len' => 100,
                'options' => 'calendar_type_dom',
                'importable' => 'required',
                'required' => true,
            ),
            'field_date_start' => array(
                'name' => 'field_date_start',
                'type' => 'varchar',
            ),
            'field_date_end' => array(
                'name' => 'field_date_end',
                'type' => 'varchar',
            ),
            'field_event' => array(
                'name' => 'field_event',
                'type' => 'varchar',
                'len' => 50
            ),
            'module_filter' => array(
                'name' => 'module_filter',
                'type' => 'id'
            ),
            'calendar_id' => array(
                'name' => 'calendar_id',
                'type' => 'id'
            ),
            'owner' => array(
                'name' => 'owner',
                'type' => 'id'
            ),
        ),
    'indices' => array(
        array(
            'name' => 'idx_sysuicalendaritems',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_sysuicalendaritems_owner',
            'type' => 'index',
            'fields' => array('owner'))
    )
);
$dictionary['sysuicustomcalendaritems'] = array(
    'table' => 'sysuicustomcalendaritems',
    'fields' =>
        array(
            'id' => array(
                'name' => 'id',
                'type' => 'id'
            ),
            'name' => array(
                'name' => 'name',
                'type' => 'varchar',
                'len' => 100
            ),
            'module' => array(
                'name' => 'module',
                'type' => 'varchar',
                'len' => 50
            ),
            'type' => array(
                'name' => 'type',
                'vname' => 'LBL_TYPE',
                'type' => 'enum',
                'len' => 100,
                'options' => 'calendar_type_dom',
                'importable' => 'required',
                'required' => true,
            ),
            'field_date_start' => array(
                'name' => 'field_date_start',
                'type' => 'varchar',
            ),
            'field_date_end' => array(
                'name' => 'field_date_end',
                'type' => 'varchar',
            ),
            'field_event' => array(
                'name' => 'field_event',
                'type' => 'varchar',
                'len' => 50
            ),
            'module_filter' => array(
                'name' => 'module_filter',
                'type' => 'id'
            ),
            'calendar_id' => array(
                'name' => 'calendar_id',
                'type' => 'id'
            ),
            'owner' => array(
                'name' => 'owner',
                'type' => 'id'
            ),
        ),
    'indices' => array(
        array(
            'name' => 'idx_sysuicalendaritems',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_sysuicalendaritems_owner',
            'type' => 'index',
            'fields' => array('owner'))
    )
);

$dictionary['sysuimodulerepository'] = array(
    'table' => 'sysuimodulerepository',
    'changerequests' => array(
        'active' => true,
        'name' => 'module'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'path' => array(
            'name' => 'path',
            'type' => 'varchar',
            'len' => 500
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
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
            'name' => 'idx_sysuimodulerepository',
            'type' => 'primary',
            'fields' => array('id')),
    )
);

$dictionary['sysuicustommodulerepository'] = array(
    'table' => 'sysuicustommodulerepository',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'path' => array(
            'name' => 'path',
            'type' => 'varchar',
            'len' => 500
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
        'version' => array(
            'name' => 'version',
            'type' => 'varchar',
            'len' => 5
        ),
        'package' => array(
            'name' => 'package',
            'type' => 'varchar',
            'len' => 32
        )
    ),
    'indices' => array(
        array(
            'name' => 'idx_sysuicustommodulerepository',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuiobjectrepository'] = array(
    'table' => 'sysuiobjectrepository',
    'changerequests' => array(
        'active' => true,
        'name' => 'object'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'object' => array(
            'name' => 'object',
            'type' => 'varchar',
            'len' => 100
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 36
        ),
        'path' => array(
            'name' => 'path',
            'type' => 'varchar',
            'len' => 500
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'deprecated' => array(
            'name' => 'deprecated',
            'type' => 'bool',
            'default' => 0
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuiobjectrepository',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicustomobjectrepository'] = array(
    'table' => 'sysuicustomobjectrepository',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'object' => array(
            'name' => 'object',
            'type' => 'varchar',
            'len' => 100
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 36
        ),
        'path' => array(
            'name' => 'path',
            'type' => 'varchar',
            'len' => 500
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
        ),
        'deprecated' => array(
            'name' => 'deprecated',
            'type' => 'bool',
            'default' => 0
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
            'name' => 'idx_sysuicustomobjectrepository',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicomponentsets'] = array(
    'table' => 'sysuicomponentsets',
    'changerequests' => array(
        'active' => true,
        'name' => 'name'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuicomponentsets',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicustomcomponentsets'] = array(
    'table' => 'sysuicustomcomponentsets',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuicustomcomponentsets',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicomponentsetscomponents'] = array(
    'table' => 'sysuicomponentsetscomponents',
    'changerequests' => array(
        'active' => true,
        'name' => 'component'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'componentset_id' => array(
            'name' => 'componentset_id',
            'type' => 'id'
        ),
        'sequence' => array(
            'name' => 'sequence',
            'type' => 'int'
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuicomponentsetscomponents',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_sysuicomponentsetscomponents_setid',
            'type' => 'index',
            'fields' => array('componentset_id'))
    )
);

$dictionary['sysuicustomcomponentsetscomponents'] = array(
    'table' => 'sysuicustomcomponentsetscomponents',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'componentset_id' => array(
            'name' => 'componentset_id',
            'type' => 'id'
        ),
        'sequence' => array(
            'name' => 'sequence',
            'type' => 'int'
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuicustomcomponentsetscomponents',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_sysuicustomcomponentsetscomponents_setid',
            'type' => 'index',
            'fields' => array('componentset_id'))
    )
);

$dictionary['sysuifieldsets'] = array(
    'table' => 'sysuifieldsets',
    'changerequests' => array(
        'active' => true,
        'name' => 'module'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuifieldsets',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicustomfieldsets'] = array(
    'table' => 'sysuicustomfieldsets',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuicustomfieldsets',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuifieldsetsitems'] = array(
    'table' => 'sysuifieldsetsitems',
    'changerequests' => array(
        'active' => true,
        'name' => 'field'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'fieldset_id' => array(
            'name' => 'fieldset_id',
            'type' => 'id'
        ),
        'sequence' => array(
            'name' => 'sequence',
            'type' => 'int'
        ),
        'field' => array(
            'name' => 'field',
            'type' => 'varchar',
            'len' => 100
        ),
        'fieldset' => array(
            'name' => 'fieldset',
            'type' => 'varchar',
            'len' => 36
        ),
        'fieldconfig' => array(
            'name' => 'fieldconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuifieldsetsitems',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_sysuifieldsetsitems_setid',
            'type' => 'index',
            'fields' => array('fieldset_id'))
    )
);

$dictionary['sysuicustomfieldsetsitems'] = array(
    'table' => 'sysuicustomfieldsetsitems',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'fieldset_id' => array(
            'name' => 'fieldset_id',
            'type' => 'id'
        ),
        'sequence' => array(
            'name' => 'sequence',
            'type' => 'int'
        ),
        'field' => array(
            'name' => 'field',
            'type' => 'varchar',
            'len' => 100
        ),
        'fieldset' => array(
            'name' => 'fieldset',
            'type' => 'varchar',
            'len' => 36
        ),
        'fieldconfig' => array(
            'name' => 'fieldconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuicustomfieldsetsitems',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_sysuicustomfieldsetsitems_setid',
            'type' => 'index',
            'fields' => array('fieldset_id'))
    )
);

$dictionary['sysuiactionsets'] = array(
    'table' => 'sysuiactionsets',
    'changerequests' => array(
        'active' => true,
        'name' => 'name'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuiactionsets',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicustomactionsets'] = array(
    'table' => 'sysuicustomactionsets',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuicustomactionsets',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuiactionsetitems'] = array(
    'table' => 'sysuiactionsetitems',
    'changerequests' => array(
        'active' => true,
        'name' => 'action'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'actionset_id' => array(
            'name' => 'actionset_id',
            'type' => 'id'
        ),
        'sequence' => array(
            'name' => 'sequence',
            'type' => 'int'
        ),
        'action' => array(
            'name' => 'action',
            'type' => 'varchar',
            'len' => 100
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'actionconfig' => array(
            'name' => 'actionconfig',
            'type' => 'text'
        ),
        'singlebutton' => array(
            'name' => 'singlebutton',
            'type' => 'bool',
            'default' => 0
        ),
        'requiredmodelstate' => array(
            'name' => 'requiredmodelstate',
            'type' => 'varchar',
            'len' => 30
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
            'name' => 'idx_sysuiactionsetitems',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicustomactionsetitems'] = array(
    'table' => 'sysuicustomactionsetitems',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'actionset_id' => array(
            'name' => 'actionset_id',
            'type' => 'id'
        ),
        'sequence' => array(
            'name' => 'sequence',
            'type' => 'int'
        ),
        'action' => array(
            'name' => 'action',
            'type' => 'varchar',
            'len' => 100
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'actionconfig' => array(
            'name' => 'actionconfig',
            'type' => 'text'
        ),
        'singlebutton' => array(
            'name' => 'singlebutton',
            'type' => 'bool',
            'default' => 0
        ),
        'requiredmodelstate' => array(
            'name' => 'requiredmodelstate',
            'type' => 'varchar',
            'len' => 30
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
            'name' => 'idx_sysuicustomactionsetitems',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuiroutes'] = array(
    'table' => 'sysuiroutes',
    'changerequests' => array(
        'active' => true,
        'name' => 'path'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'path' => array(
            'name' => 'path',
            'type' => 'varchar',
            'len' => 255
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'redirectto' => array(
            'name' => 'redirectto',
            'type' => 'varchar',
            'len' => 100
        ),
        'pathmatch' => array(
            'name' => 'pathmatch',
            'type' => 'varchar',
            'len' => 4
        ),
        'loginrequired' => array(
            'name' => 'loginrequired',
            'type' => 'int',
            'len' => 1
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
            'name' => 'idx_sysuiroutes',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicustomroutes'] = array(
    'table' => 'sysuicustomroutes',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'path' => array(
            'name' => 'path',
            'type' => 'varchar',
            'len' => 255
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'redirectto' => array(
            'name' => 'redirectto',
            'type' => 'varchar',
            'len' => 100
        ),
        'pathmatch' => array(
            'name' => 'pathmatch',
            'type' => 'varchar',
            'len' => 4
        ),
        'loginrequired' => array(
            'name' => 'loginrequired',
            'type' => 'int',
            'len' => 1
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
            'name' => 'idx_sysuicustomroutes',
            'type' => 'primary',
            'fields' => array('id'))
    )
);


$dictionary['sysmodules'] = array(
    'table' => 'sysmodules',
    'changerequests' => array(
        'active' => true,
        'name' => 'module'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'module_label' => array(
            'name' => 'module_label',
            'type' => 'varchar',
            'len' => 100
        ),
        'singular' => array(
            'name' => 'singular',
            'type' => 'varchar',
            'len' => 100
        ),
        'singular_label' => array(
            'name' => 'singular_label',
            'type' => 'varchar',
            'len' => 100
        ),
        'icon' => array(
            'name' => 'icon',
            'type' => 'varchar',
            'len' => 100
        ),
        'track' => array(
            'name' => 'track',
            'type' => 'int',
            'len' => 1
        ),
        'favorites' => array(
            'name' => 'favorites',
            'type' => 'int',
            'len' => 1
        ),
        'duplicatecheck' => array(
            'name' => 'duplicatecheck',
            'type' => 'int',
            'len' => 1
        ),
        'actionset' => array(
            'name' => 'actionset',
            'type' => 'varchar',
            'len' => 36
        ),
        'bean' => array(
            'name' => 'bean',
            'type' => 'varchar',
        ),
        'beanfile' => array(
            'name' => 'beanfile',
            'type' => 'varchar',
        ),
        'beantable' => array(
            'name' => 'beantable',
            'type' => 'varchar',
        ),
        'visible' => array(
            'name' => 'visible',
            'type' => 'bool',
        ),
        'visibleaclaction' => array(
            'name' => 'visibleaclaction',
            'type' => 'varchar',
            'len' => 30
        ),
        'tagging' => array(
            'name' => 'tagging',
            'type' => 'bool',
            'default' => 0
        ),
        'acl' => array(
            'name' => 'acl',
            'type' => 'bool',
            'default' => 1
        ),
        'workflow' => array(
            'name' => 'workflow',
            'type' => 'bool',
            'default' => 0
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
            'name' => 'sysmodulespk',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_sysmodules',
            'type' => 'index',
            'fields' => array('module'))
    )
);

$dictionary['syscustommodules'] = array(
    'table' => 'syscustommodules',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'module_label' => array(
            'name' => 'module_label',
            'type' => 'varchar',
            'len' => 100
        ),
        'singular' => array(
            'name' => 'singular',
            'type' => 'varchar',
            'len' => 100
        ),
        'singular_label' => array(
            'name' => 'singular_label',
            'type' => 'varchar',
            'len' => 100
        ),
        'icon' => array(
            'name' => 'icon',
            'type' => 'varchar',
            'len' => 100
        ),
        'track' => array(
            'name' => 'track',
            'type' => 'int',
            'len' => 1
        ),
        'favorites' => array(
            'name' => 'favorites',
            'type' => 'int',
            'len' => 1
        ),
        'duplicatecheck' => array(
            'name' => 'duplicatecheck',
            'type' => 'int',
            'len' => 1
        ),
        'actionset' => array(
            'name' => 'actionset',
            'type' => 'varchar',
            'len' => 36
        ),
        'bean' => array(
            'name' => 'bean',
            'type' => 'varchar',
        ),
        'beanfile' => array(
            'name' => 'beanfile',
            'type' => 'varchar',
        ),
        'beantable' => array(
            'name' => 'beantable',
            'type' => 'varchar',
        ),
        'visible' => array(
            'name' => 'visible',
            'type' => 'bool',
        ),
        'visibleaclaction' => array(
            'name' => 'visibleaclaction',
            'type' => 'varchar',
            'len' => 30
        ),
        'tagging' => array(
            'name' => 'tagging',
            'type' => 'bool',
            'default' => 0
        ),
        'acl' => array(
            'name' => 'acl',
            'type' => 'bool',
            'default' => 1
        ),
        'workflow' => array(
            'name' => 'workflow',
            'type' => 'bool',
            'default' => 0
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
            'name' => 'idx_syscustommodules',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysmodulemenus'] = array(
    'table' => 'sysmodulemenus',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'menuitem' => array(
            'name' => 'menuitem',
            'type' => 'varchar',
            'len' => 100
        ),
        'action' => array(
            'name' => 'action',
            'type' => 'varchar',
            'len' => 100
        ),
        'route' => array(
            'name' => 'route',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysmodulemenus',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_sysmodule_module',
            'type' => 'index',
            'fields' => array('module')
        )
    )
);

$dictionary['sysuicomponentdefaultconf'] = array(
    'table' => 'sysuicomponentdefaultconf',
    'changerequests' => array(
        'active' => true,
        'name' => 'component'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'role_id' => array(
            'name' => 'role_id',
            'type' => 'id'
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuicomponentdefaultconf',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicustomcomponentdefaultconf'] = array(
    'table' => 'sysuicustomcomponentdefaultconf',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'role_id' => array(
            'name' => 'role_id',
            'type' => 'id'
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuicustomcomponentdefaultconf',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicomponentmoduleconf'] = array(
    'table' => 'sysuicomponentmoduleconf',
    'changerequests' => array(
        'active' => true,
        'name' => array('module', 'component')
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'role_id' => array(
            'name' => 'role_id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuicomponentmoduleconf',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_sysuicomponentmoduleconf_module',
            'type' => 'index',
            'fields' => array('module'))
    )
);

$dictionary['sysuicustomcomponentmoduleconf'] = array(
    'table' => 'sysuicustomcomponentmoduleconf',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'role_id' => array(
            'name' => 'role_id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuicustomcomponentmoduleconf',
            'type' => 'primary',
            'fields' => array('id')),
        array(
            'name' => 'idx_sysuicustomcomponentmoduleconf_module',
            'type' => 'index',
            'fields' => array('module'))
    )
);

$dictionary['sysmodulelists'] = array(
    'table' => 'sysmodulelists',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'created_by_id' => array(
            'name' => 'created_by_id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'global' => array(
            'name' => 'global',
            'type' => 'int',
            'len' => 1
        ),
        'basefilter' => array(
            'name' => 'basefilter',
            'type' => 'varchar',
            'len' => 3,
            'default' => 'all'
        ),
        'fielddefs' => array(
            'name' => 'fielddefs',
            'type' => 'text'
        ),
        'filterdefs' => array(
            'name' => 'filterdefs',
            'type' => 'text'
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
            'name' => 'idx_sysmodulelists',
            'type' => 'primary',
            'fields' => array('id'))
    )
);


$dictionary['sysuidashboarddashlets'] = array(
    'table' => 'sysuidashboarddashlets',
    'changerequests' => array(
        'active' => true,
        'name' => 'name'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'label' => array(
            'name' => 'label',
            'type' => 'varchar',
            'len' => 100
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'icon' => array(
            'name' => 'icon',
            'type' => 'varchar',
            'len' => 100
        ),
        'acl_action' => array(
            'name' => 'acl_action',
            'type' => 'varchar',
            'len' => 30
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuidashboarddashlets',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicustomdashboarddashlets'] = array(
    'table' => 'sysuicustomdashboarddashlets',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'label' => array(
            'name' => 'label',
            'type' => 'varchar',
            'len' => 100
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'icon' => array(
            'name' => 'icon',
            'type' => 'varchar',
            'len' => 100
        ),
        'acl_action' => array(
            'name' => 'acl_action',
            'type' => 'varchar',
            'len' => 30
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
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
            'name' => 'idx_sysuicustomdashboarddashlets',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuiroles'] = array(
    'table' => 'sysuiroles',
    'changerequests' => array(
        'active' => true,
        'name' => 'name'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'identifier' => array(
            'name' => 'identifier',
            'type' => 'varchar',
            'len' => '3'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => '100'
        ),
        'label' => array(
            'name' => 'label',
            'type' => 'varchar',
            'len' => '100'
        ),
        'icon' => array(
            'name' => 'icon',
            'type' => 'varchar',
            'len' => '50'
        ),
        'systemdefault' => array(
            'name' => 'systemdefault',
            'type' => 'bool'
        ),
        'portaldefault' => array(
            'name' => 'portaldefault',
            'type' => 'bool'
        ),
        'showsearch' => array(
            'name' => 'showsearch',
            'type' => 'bool',
            'default' => 1
        ),
        'showfavorites' => array(
            'name' => 'showfavorites',
            'type' => 'bool',
            'default' => 1
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
        'default_dashboard' => array(
            'name' => 'default_dashboard',
            'type' => 'id'
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
            'name' => 'idx_sysuiroles',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuicustomroles'] = array(
    'table' => 'sysuicustomroles',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'identifier' => array(
            'name' => 'identifier',
            'type' => 'varchar',
            'len' => '3'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => '100'
        ),
        'label' => array(
            'name' => 'label',
            'type' => 'varchar',
            'len' => '100'
        ),
        'icon' => array(
            'name' => 'icon',
            'type' => 'varchar',
            'len' => '50'
        ),
        'systemdefault' => array(
            'name' => 'systemdefault',
            'type' => 'bool'
        ),
        'portaldefault' => array(
            'name' => 'portaldefault',
            'type' => 'bool'
        ),
        'showsearch' => array(
            'name' => 'showsearch',
            'type' => 'bool',
            'default' => 1
        ),
        'showfavorites' => array(
            'name' => 'showfavorites',
            'type' => 'bool',
            'default' => 1
        ),
        'description' => array(
            'name' => 'description',
            'type' => 'text'
        ),
        'default_dashboard' => array(
            'name' => 'default_dashboard',
            'type' => 'id'
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
            'name' => 'idx_sysuicustomroles',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

$dictionary['sysuiuserroles'] = array(
    'table' => 'sysuiuserroles',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'user_id' => array(
            'name' => 'user_id',
            'type' => 'id'
        ),
        'sysuirole_id' => array(
            'name' => 'sysuirole_id',
            'type' => 'id'
        ),
        'defaultrole' => array(
            'name' => 'defaultrole',
            'type' => 'int',
            'len' => 1,
            'default' => 0
        )
    ),
    'indices' => array(
        array(
            'name' => 'idx_sysuiuserroles',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_sysuiuserroles_userid',
            'type' => 'index',
            'fields' => array('user_id')
        )
    )
);

$dictionary['sysuirolemodules'] = array(
    'table' => 'sysuirolemodules',
    'changerequests' => array(
        'active' => true,
        'name' => array('sysuirole_id', 'module')
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'sysuirole_id' => array(
            'name' => 'sysuirole_id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'sequence' => array(
            'name' => 'sequence',
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
            'name' => 'idx_sysuirolemodules',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_sysuirolemodules_roleid',
            'type' => 'index',
            'fields' => array('sysuirole_id')
        )
    )
);

$dictionary['sysuicustomrolemodules'] = array(
    'table' => 'sysuicustomrolemodules',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'sysuirole_id' => array(
            'name' => 'sysuirole_id',
            'type' => 'id'
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 100
        ),
        'sequence' => array(
            'name' => 'sequence',
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
            'name' => 'idx_sysuicustomrolemodules',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_sysuicustomrolemodules_roleid',
            'type' => 'index',
            'fields' => array('sysuirole_id')
        )
    )
);

$dictionary['sysuiadmincomponents'] = array(
    'table' => 'sysuiadmincomponents',
    'changerequests' => array(
        'active' => true,
        'name' => array('admingroup', 'adminaction')
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'admingroup' => array(
            'name' => 'admingroup',
            'type' => 'varchar',
            'len' => 100
        ),
        'adminaction' => array(
            'name' => 'adminaction',
            'type' => 'varchar',
            'len' => 100
        ),
        // darf nicht nur label heißen...
        'admin_label' => array(
            'name' => 'admin_label',
            'type' => 'varchar',
            'len' => 40
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
        ),
        'sequence' => array(
            'name' => 'sequence',
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
            'name' => 'idx_sysuiadmincomponents',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary['sysuicustomadmincomponents'] = array(
    'table' => 'sysuicustomadmincomponents',
    'changerequests' => array(
        'active' => true,
        'name' => array('admingroup', 'adminaction')
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'admingroup' => array(
            'name' => 'admingroup',
            'type' => 'varchar',
            'len' => 100
        ),
        'adminaction' => array(
            'name' => 'adminaction',
            'type' => 'varchar',
            'len' => 100
        ),
        // darf nicht nur label heißen...
        'admin_label' => array(
            'name' => 'admin_label',
            'type' => 'varchar',
            'len' => 40
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
        ),
        'componentconfig' => array(
            'name' => 'componentconfig',
            'type' => 'text'
        ),
        'sequence' => array(
            'name' => 'sequence',
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
            'name' => 'idx_sysuicustomadmincomponents',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);


$dictionary['sysuifieldtypemapping'] = array(
    'table' => 'sysuifieldtypemapping',
    'changerequests' => array(
        'active' => true,
        'name' => 'fieldtype'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'fieldtype' => array(
            'name' => 'fieldtype',
            'type' => 'varchar',
            'len' => 100
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuifieldtypemapping',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);


$dictionary['sysuicustomfieldtypemapping'] = array(
    'table' => 'sysuicustomfieldtypemapping',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'fieldtype' => array(
            'name' => 'fieldtype',
            'type' => 'varchar',
            'len' => 100
        ),
        'component' => array(
            'name' => 'component',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuicustomfieldtypemapping',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);


$dictionary['sysuicopyrules'] = array(
    'table' => 'sysuicopyrules',
    'changerequests' => array(
        'active' => true,
        'name' => array('frommodule', 'tomodule', 'tofield')
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'frommodule' => array(
            'name' => 'frommodule',
            'type' => 'varchar',
            'len' => 50
        ),
        'fromfield' => array(
            'name' => 'fromfield',
            'type' => 'varchar',
            'len' => 50
        ),
        'tomodule' => array(
            'name' => 'tomodule',
            'type' => 'varchar',
            'len' => 50
        ),
        'tofield' => array(
            'name' => 'tofield',
            'type' => 'varchar',
            'len' => 50
        ),
        'fixedvalue' => array(
            'name' => 'fixedvalue',
            'type' => 'varchar',
            'len' => 100
        ),
        'calculatedvalue' => array(
            'name' => 'calculatedvalue',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuicopyrules',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary['sysuicustomcopyrules'] = array(
    'table' => 'sysuicustomcopyrules',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'frommodule' => array(
            'name' => 'frommodule',
            'type' => 'varchar',
            'len' => 50
        ),
        'fromfield' => array(
            'name' => 'fromfield',
            'type' => 'varchar',
            'len' => 50
        ),
        'tomodule' => array(
            'name' => 'tomodule',
            'type' => 'varchar',
            'len' => 50
        ),
        'tofield' => array(
            'name' => 'tofield',
            'type' => 'varchar',
            'len' => 50
        ),
        'fixedvalue' => array(
            'name' => 'fixedvalue',
            'type' => 'varchar',
            'len' => 100
        ),
        'calculatedvalue' => array(
            'name' => 'calculatedvalue',
            'type' => 'varchar',
            'len' => 100
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
            'name' => 'idx_sysuicustomcopyrules',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);

$dictionary['spiceimportlogs'] = array(
    'table' => 'spiceimportlogs',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'import_id' => array(
            'name' => 'import_id',
            'type' => 'id'
        ),
        'msg' => array(
            'name' => 'msg',
            'type' => 'varchar'
        ),
        'data' => array(
            'name' => 'data',
            'type' => 'text'
        )
    ),
    'indices' => array(
        array(
            'name' => 'idx_spiceimportlogs',
            'type' => 'primary',
            'fields' => array('id'))
    )
);

/**
 * VALIDATIONs
 */


$dictionary['sysuimodelvalidations'] = array(
    'table' => 'sysuimodelvalidations',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 50,
            'required' => true,
        ),
        'module' => array(
            'name' => 'module',
            'type' => 'varchar',
            'len' => 20,
            'required' => true,
        ),
        'onevents' => array(
            'name' => 'onevents',
            'type' => 'varchar',
            'len' => 100,
        ),
        'active' => array(
            'name' => 'active',
            'type' => 'bool',
            'default' => 1,
        ),
        'logicoperator' => array(
            'name' => 'logicoperator',
            'type' => 'enum',
            'options' => 'logicoperators_dom',
            'len' => 3,
        ),
        'priority' => array(
            'name' => 'priority',
            'type' => 'int',
            'default' => 0,
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => 0,
            'isnull' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'prm_sysuimodelvalidations',
            'type' => 'primary',
            'fields' => array('id')
        )
    ),
);

$dictionary['sysuimodelvalidationconditions'] = array(
    'table' => 'sysuimodelvalidationconditions',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'sysuimodelvalidation_id' => array(
            'name' => 'sysuimodelvalidation_id',
            'type' => 'id',
            'required' => true,
        ),
        'fieldname' => array(
            'name' => 'fieldname',
            'type' => 'varchar',
            'required' => true,
            'len' => 50,
        ),
        'comparator' => array(
            'name' => 'comparator',
            'type' => 'enum',
            'options' => 'comparators_dom',
            'default' => 'equal',
            'len' => 20,
        ),
        'valuations' => array(
            'name' => 'valuations',
            'type' => 'varchar',
            'required' => true,
        ),
        'onchange' => array(
            'name' => 'onchange',
            'type' => 'bool'
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => 0,
            'isnull' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'prm_sysuimodvalcon',
            'type' => 'primary',
            'fields' => array('id'),
        ),
    ),
);

$dictionary['sysuimodelvalidationactions'] = array(
    'table' => 'sysuimodelvalidationactions',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'sysuimodelvalidation_id' => array(
            'name' => 'sysuimodelvalidation_id',
            'type' => 'id'
        ),
        'fieldname' => array(
            'name' => 'fieldname',
            'type' => 'varchar',
            'len' => 20,
            'required' => true,
        ),
        'action' => array(
            'name' => 'action',
            'type' => 'varchar',
            'len' => 20,
            'required' => true,
        ),
        'params' => array(
            'name' => 'params',
            'type' => 'varchar'
        ),
        'priority' => array(
            'name' => 'priority',
            'type' => 'int',
            'default' => 0,
        ),
        'deleted' => array(
            'name' => 'deleted',
            'type' => 'bool',
            'default' => 0,
            'isnull' => false,
        ),
    ),
    'indices' => array(
        array(
            'name' => 'prm_sysuimodvalact',
            'type' => 'primary',
            'fields' => array('id'),
        ),
    ),
);

$dictionary['sysmailrelais'] = array(
    'table' => 'sysmailrelais',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 50,
            'required' => true,
        ),
        'service' => array(
            'name' => 'service',
            'type' => 'varchar',
            'len' => 50,
            'required' => true,
        ),
        'api_key' => array(
            'name' => 'api_key',
            'type' => 'varchar'
        ),
        'username' => array(
            'name' => 'username',
            'type' => 'varchar'
        ),
        'password' => array(
            'name' => 'password',
            'type' => 'varchar'
        ),
        'from_email' => array(
            'name' => 'from_email',
            'type' => 'varchar'
        ),
        'from_name' => array(
            'name' => 'from_name',
            'type' => 'varchar'
        ),

    ),
    'indices' => array(
        array(
            'name' => 'idx_sysmailrelais',
            'type' => 'primary',
            'fields' => array('id'),
        )
    ),
);


$dictionary['sysuicustomlibs'] = array(
    'table' => 'sysuicustomlibs',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 50,
        ),
        'src' => array(
            'name' => 'src',
            'type' => 'varchar',
        ),
        'rank' => array(
            'name' => 'rank',
            'type' => 'int',
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
            'name' => 'idx_sysuicustomlibs',
            'type' => 'primary',
            'fields' => array('id'),
        )
    ),
);
$dictionary['sysuilibs'] = array(
    'table' => 'sysuilibs',
    'changerequests' => array(
        'active' => true,
        'name' => 'name'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 50,
        ),
        'src' => array(
            'name' => 'src',
            'type' => 'varchar',
        ),
        'rank' => array(
            'name' => 'rank',
            'type' => 'int',
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
            'name' => 'idx_sysuiapis',
            'type' => 'primary',
            'fields' => array('id'),
        )
    ),
);


$dictionary['sysuihtmlstylesheets'] = array(
    'table' => 'sysuihtmlstylesheets',
    'changerequests' => array(
        'active' => true,
        'name' => 'name'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'csscode' => array(
            'name' => 'csscode',
            'type' => 'text'
        ),
        'inactive' => array(
            'name' => 'inactive',
            'type' => 'bool',
            'default' => false
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
            'name' => 'idx_sysuihtmlstylesheets_prim',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_sysuihtmlstylesheets_2',
            'type' => 'index',
            'fields' => array('id', 'inactive')
        ),
    )
);

$dictionary['sysuihtmlformats'] = array(
    'table' => 'sysuihtmlformats',
    'changerequests' => array(
        'active' => true,
        'name' => 'name'
    ),
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
            'len' => 100
        ),
        'inline' => array(
            'name' => 'inline',
            'type' => 'varchar',
            'len' => 30
        ),
        'block' => array(
            'name' => 'block',
            'type' => 'varchar',
            'len' => 30
        ),
        'classes' => array(
            'name' => 'classes',
            'type' => 'varchar',
            'len' => 100
        ),
        'styles' => array(
            'name' => 'styles',
            'type' => 'text'
        ),
        'wrapper' => array(
            'name' => 'wrapper',
            'type' => 'bool',
            'default' => false
        ),
        'stylesheet_id' => array(
            'name' => 'stylesheet_id',
            'type' => 'id'
        ),
        'inactive' => array(
            'name' => 'inactive',
            'type' => 'bool',
            'default' => false
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
            'name' => 'idx_sysuihtmlformats',
            'type' => 'primary',
            'fields' => array('id')),
    )
);
