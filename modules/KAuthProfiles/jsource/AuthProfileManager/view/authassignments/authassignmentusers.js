Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthAssignmentUsers', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthAssignmentUsers',
    title: 'Profiles',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthAssignmentUsersController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthAssignmentUsersController',
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
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authassignmentusers', {storeId: 'KAuthManager.authassignmentusers'}),
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true
        },
        items: [
            {
                text: 'user name',
                dataIndex: 'user_name',
                flex: 2
            },{
                text: 'first name',
                dataIndex: 'first_name',
                flex: 2
            },{
                text: 'last name',
                dataIndex: 'last_name',
                flex: 2
            }
        ]
    },
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
               {
                    xtype: 'textfield',
                    itemId: 'userFilter',
                    flex: 1,
                    enableKeyEvents: true,
                    emptyText: 'filter users'
                }
            ]
        }, {
            xtype: 'pagingtoolbar',
            store: 'KAuthManager.authassignmentusers',
            dock: 'bottom',
            displayInfo: true
        }
    ]
});


