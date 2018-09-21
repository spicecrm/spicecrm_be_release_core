Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthObjectsFieldValues', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthObjectsFieldValues',
    title: 'Field Values',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectFieldValuesController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthObjectFieldValuesController',
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
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectfieldvalues', {storeId: 'KAuthManager.authobjectsfieldvalues'}),
    columns: [
        {
            text: 'name',
            dataIndex: 'name',
            flex: 1,
            menuDisabled: true,
            sortable: false
        }, {
            text: 'operator',
            dataIndex: 'operator',
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
                                value: '',
                                name: 'ignore'
                            }, {
                                value: 'EQ',
                                name: '='
                            }, {
                                value: 'NE',
                                name: '≠'
                            }, {
                                value: 'IN',
                                name: 'in'
                            }, {
                                value: 'NI',
                                name: 'not in'
                            }, {
                                value: 'GT',
                                name: '>'
                            }, {
                                value: 'GTE',
                                name: '≥'
                            }, {
                                value: 'LT',
                                name: '<'
                            }, {
                                value: 'LTE',
                                name: '≤'
                            }, {
                                value: 'LK',
                                name: 'like'
                            }
                        ],
                        storeId: 'operatorTypesStore'
                    }
                ),
                displayField: 'name',
                valueField: 'value',
                lazyRender: true
            },
            renderer: function (_value) {
                var _store = Ext.data.StoreManager.lookup('operatorTypesStore'),
                    _record = _store.findRecord('value', _value);

                if (_record)
                    return _record.get('name');
                else
                    return _value;

            },
            menuDisabled: true,
            sortable: false
        }, {
            text: 'value1',
            dataIndex: 'value1',
            flex: 1,
            menuDisabled: true,
            sortable: false
        }, {
            text: 'value2',
            dataIndex: 'value2',
            flex: 1,
            menuDisabled: true,
            sortable: false
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'button',
                    text: 'SQL'
                }
            ]
        }
    ]
});


