<?php
$dictionary['sysselecttree_fields'] = array(
    'table' => 'sysselecttree_fields',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
        ),
        'keyname' => array(
            'name' => 'keyname',
            'type' => 'varchar',
            'len' => 32
        ),
        'selectable' => array(
            'name' => 'selectable',
            'type' => 'bool',
        ),
        'favorite' => array(
            'name' => 'favorite',
            'type' => 'bool'
        ),
        'parent_id' => array(
            'name' => 'parent_id',
            'type' => 'id',
            'comment' => 'id of a record located in this table'
        ),
        'tree' => array(
            'name' => 'tree',
            'type' => 'id'
        ),
    ),
    'indices' => array(
        array(
            'name' => 'sysselecttree_fieldspk',
            'type' => 'primary',
            'fields' => array('id')
        ),
        array(
            'name' => 'idx_sysselecttree_fieldsparent',
            'type' => 'index',
            'fields' => array('parent_id')
        ),
        array(
            'name' => 'idx_sysselecttree_fieldstree',
            'type' => 'index',
            'fields' => array('tree')
        ),
    )
);


$dictionary['sysselecttree_tree'] = array(
    'table' => 'sysselecttree_tree',
    'fields' => array(
        'id' => array(
            'name' => 'id',
            'type' => 'id'
        ),
        'name' => array(
            'name' => 'name',
            'type' => 'varchar',
        )
    ),
    'indices' => array(
        array(
            'name' => 'sysselecttree_treepk',
            'type' => 'primary',
            'fields' => array('id')
        )
    )
);