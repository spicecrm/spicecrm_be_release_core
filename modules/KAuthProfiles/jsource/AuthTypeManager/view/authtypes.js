Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.view.AuthTypes', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthTypes',
    requires: [
        'SpiceCRM.KAuthManager.AuthTypeManager.controller.AuthTypesController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthTypesController',
    layout: 'fit',
    title: 'modules',
    style: {
        'background-color': 'transparent',
    },
    store: Ext.create('SpiceCRM.KAuthManager.AuthTypeManager.store.authtypes', {storeId: 'KAuthManager.authtypes'}),
    columns: [
        {
            text: 'name',
            dataIndex: 'bean',
            flex: 1,
            sortable: true
        }, {
            text: '#',
            dataIndex: 'usagecount',
            width: 30,
            renderer: function (_value) {
                if (_value > 0)
                    return '<img src="modules/KAuthProfiles/images/lock.png"/>';
                else
                    return '<img src="modules/KAuthProfiles/images/unlock.png"/>';
            }
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'button',
                    itemId: 'authTypeAddButton',
                    icon: 'modules/KOrgObjects/images/add.png',
                    text: 'add'
                }, {
                    xtype: 'button',
                    itemId: 'authTypetDeleteButton',
                    disabled: true,
                    icon: 'modules/KOrgObjects/images/delete.png',
                    text: 'delete'
                }
            ]
        },{
            xtype: 'pagingtoolbar',
            store: 'KAuthManager.authtypes',
            dock: 'bottom',
            displayInfo: true
        }
    ]
});


