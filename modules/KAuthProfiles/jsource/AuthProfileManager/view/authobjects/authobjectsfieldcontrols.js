Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthObjectsFieldControls', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthObjectsFieldControls',
    title: 'Field Controls',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectFieldControlsController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthObjectFieldControlsController',
    layout: 'fit',
    style: {
        'background-color': 'transparent',
    },
    selModel: 'rowmodel',
    plugins: {
        ptype: 'cellediting',
        clicksToEdit: 2
    },
    viewConfig: {
        markDirty: false
    },
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectfieldcontrols', {storeId: 'KAuthManager.authobjectsfieldcontrols'}),
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true
        },
        items: [
            {
                text: 'field',
                dataIndex: 'field',
                flex: 1
            }, {
                text: 'control',
                dataIndex: 'control',
                flex: 1,
                editor: {
                    xtype: 'combo',
                    typeAhead: true,
                    triggerAction: 'all',
                    selectOnTab: true,
                    store: Ext.create('Ext.data.Store', {
                            fields: ['value', 'name'],
                            data: [[0, 'none'], [1, 'hide'], [2, 'display'], [3, 'edit']],
                            storeId: 'fieldControlStore'
                        }
                    ),
                    displayField: 'name',
                    valueField: 'value',
                    lazyRender: true
                },
                renderer: function (_value) {
                    var _store = Ext.data.StoreManager.lookup('fieldControlStore'),
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
                    xtype: 'button',
                    itemId: 'authObjectFieldControlAddButton',
                    icon: 'modules/KOrgObjects/images/add.png',
                    text: 'Add'
                }, {
                    xtype: 'button',
                    itemId: 'authObjectFieldControlDeleteButton',
                    icon: 'modules/KOrgObjects/images/delete.png',
                    text: 'delete'
                }
            ]
        }
    ]
});


