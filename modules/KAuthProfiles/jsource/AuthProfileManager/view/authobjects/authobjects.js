Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthObjects', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthObjects',
    title: 'Objects',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectsController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthObjectsController',
    layout: 'fit',
    style: {
        'background-color': 'transparent',
    },
    selModel: 'rowmodel',
    plugins: {
        ptype: 'cellediting',
        clicksToEdit: 2
    },
    viewConfig:{
        markDirty: false
    },
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjects', {storeId: 'KAuthManager.authobjects'}),
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true
        },
        items: [
            {
                text: '<img src="modules/KAuthProfiles/images/lock.png"/>',
                dataIndex: 'usagecount',
                width: 40,
                align: 'center',
                renderer: function (_value) {
                    if (_value)
                        return '<img src="modules/KAuthProfiles/images/lock.png"/>';
                    else
                        return '';
                }
            },
            {
                text: 'name',
                dataIndex: 'name',
                flex: 2,
                editor: {
                    xtype: 'textfield'
                }
            }, {
                text:'<img src="modules/KAuthProfiles/images/bulb_on.png"/>',
                dataIndex: 'status',
                width: 40,
                align: 'center',
                renderer: function (_value) {
                    if (_value === 'r')
                        return '<img src="modules/KAuthProfiles/images/bulb_on.png"/>';
                    else
                        return '<img src="modules/KAuthProfiles/images/bulb_off.png"/>';
                }
            }, {
                text:'<img src="modules/KAuthProfiles/images/sql.png"/>',
                dataIndex: 'customSQL',
                width: 40,
                align: 'center',
                renderer: function (_value) {
                    if (_value && _value !== '')
                        return '<img src="modules/KAuthProfiles/images/sql.png"/>';
                    else
                        return '';
                }
            }, {
                text:'<img src="modules/KAuthProfiles/images/description.png"/>',
                dataIndex: 'description',
                width: 40,
                align: 'center',
                renderer: function (_value) {
                    if (_value && _value !== '')
                        return '<img src="modules/KAuthProfiles/images/description.png"/>';
                    else
                        return '';
                }
            }, {
                text: 'type',
                dataIndex: 'kauthobjecttype',
                flex: 1,
                editor: {
                    xtype: 'combo',
                    typeAhead: true,
                    triggerAction: 'all',
                    selectOnTab: true,
                    store: Ext.create('Ext.data.Store', {
                            fields: ['value', 'name'],
                            data: [
                                {
                                    value: '0',
                                    name: 'standard'
                                }, {
                                    value: '1',
                                    name: 'restrict (all)'
                                }, {
                                    value: '2',
                                    name: 'exclude (all)'
                                }, {
                                    value: '3',
                                    name: 'limit activity'
                                }, {
                                    value: '4',
                                    name: 'restrict (profile)'
                                }, {
                                    value: '5',
                                    name: 'exclude (profile)'
                                }
                            ],
                            storeId: 'objectTypesStore'
                        }
                    ),
                    displayField: 'name',
                    valueField: 'value',
                    lazyRender: true
                },
                renderer: function (_value) {
                    var _store = Ext.data.StoreManager.lookup('objectTypesStore'),
                        _record = _store.findRecord('value', _value);

                    if (_record)
                        return _record.get('name');
                    else
                        return _value;

                }
            }, {
                text: 'activity',
                dataIndex: 'activity',
                flex: 1,
                editor: {
                    xtype: 'combo',
                    typeAhead: true,
                    triggerAction: 'all',
                    selectOnTab: true,
                    store: Ext.create('Ext.data.Store', {
                            fields: ['value', 'name'],
                            data: [
                                {
                                    value: '0',
                                    name: 'List'
                                }, {
                                    value: '1',
                                    name: 'View'
                                }, {
                                    value: '2',
                                    name: 'Edit'
                                }, {
                                    value: '3',
                                    name: 'Create'
                                }, {
                                    value: '4',
                                    name: 'Delete'
                                }, {
                                    value: '5',
                                    name: '*'
                                }
                            ],
                            storeId: 'objectActivitiesStore'
                        }
                    ),
                    displayField: 'name',
                    valueField: 'value',
                    lazyRender: true
                },
                renderer: function (_value) {
                    var _store = Ext.data.StoreManager.lookup('objectActivitiesStore'),
                        _record = _store.findRecord('value', _value);

                    if (_record)
                        return _record.get('name');
                    else
                        return _value;

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
                    flex: 1,
                    itemId: 'authTypeCombo',
                    typeAhead: true,
                    triggerAction: 'all',
                    queryMode: 'local',
                    editable: false,
                    store: {
                        type: 'KAuthManager.authtypes',
                        storeId: 'KAuthManager.authtypes'
                    },
                    displayField: 'bean',
                    valueField: 'id'
                },{
                    xtype: 'textfield',
                    itemId: 'objectFilter',
                    flex: 1,
                    enableKeyEvents: true,
                    emptyText: 'filter objects'
                },{
                    xtype: 'button',
                    itemId: 'authObjectActivateButton',
                    icon: 'modules/KOrgObjects/images/bulb_on.png',
                    tooltip: 'toggle Activation Status of the selected object'
                },{
                    xtype: 'button',
                    itemId: 'authObjectAddButton',
                    icon: 'modules/KOrgObjects/images/add.png',
                    tooltip: 'add an Object'
                }, {
                    xtype: 'button',
                    itemId: 'authObjectDeleteButton',
                    disabled: true,
                    icon: 'modules/KOrgObjects/images/delete.png',
                    tooltip: 'Delete the selected Object'
                }
            ]
        }, {
            xtype: 'pagingtoolbar',
            store: 'KAuthManager.authobjects',
            dock: 'bottom',
            displayInfo: true
        }
    ]
});


