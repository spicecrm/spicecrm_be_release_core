Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthObjectsOrgValues', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthObjectsOrgValues',
    title: 'Org Values',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectOrgValuesController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthObjectOrgValuesController',
    layout: 'fit',
    style: {
        'background-color': 'transparent',
    },
    viewConfig: {
        markDirty: false
    },
    selModel: 'rowmodel',
    plugins: {
        ptype: 'cellediting',
        clicksToEdit: 2
    },
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectorgvalues', {storeId: 'KAuthManager.authobjectsorgvalues'}),
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true,
            flex: 1
        },
        items: [
            {
                text: 'name',
                dataIndex: 'name'
            }, {
                text: 'value',
                dataIndex: 'displayvalue',
                editor:{
                    xtype: 'textfield'
                }
            }
        ]
    },
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'combo',
                    fieldLabel: 'org assignment',
                    itemId: 'orgAssignment',
                    value: '0',
                    typeAhead: true,
                    triggerAction: 'all',
                    selectOnTab: true,
                    store: Ext.create('Ext.data.Store', {
                            fields: ['value', 'name'],
                            data: [
                                {
                                    value: '0',
                                    name: 'own'
                                },
                                {
                                    value: '1',
                                    name: 'all'
                                },
                                {
                                    value: '2',
                                    name: 'relate'
                                },
                                {
                                    value: '3',
                                    name: 'ignore'
                                }
                            ]
                        }
                    ),
                    displayField: 'name',
                    valueField: 'value',
                    lazyRender: true
                }, {
                    xtype: 'checkbox',
                    itemId: 'assignedUser',
                    fieldLabel: 'assigned user'
                }
            ]
        }
    ]
});


