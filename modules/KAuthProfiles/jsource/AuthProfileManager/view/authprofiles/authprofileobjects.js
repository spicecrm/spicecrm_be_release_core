Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthProfileObjects', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthProfileObjects',
    title: 'linked Objects',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthProfileObjectsController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthProfileObjectsController',
    layout: 'fit',
    style: {
        'background-color': 'transparent',
    },
    selModel: 'rowmodel',
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authprofileobjects', {storeId: 'KAuthManager.authprofileobjects'}),
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true
        },
        items: [
            {
                text: 'type',
                dataIndex: 'bean',
                flex: 2
            }, {
                text: 'name',
                dataIndex: 'name',
                flex: 2
            }, {
                text: '<img src="modules/KAuthProfiles/images/bulb_on.png"/>',
                dataIndex: 'status',
                width: 40,
                align: 'center',
                renderer: function (_value) {
                    if (_value === 'r')
                        return '<img src="modules/KAuthProfiles/images/bulb_on.png"/>';
                    else
                        return '<img src="modules/KAuthProfiles/images/bulb_off.png"/>';
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
                    itemId: 'authProfilObjectAddButton',
                    icon: 'modules/KOrgObjects/images/add.png',
                    tooltip: 'add an Object',
                    disabled: true
                }, {
                    xtype: 'button',
                    itemId: 'authProfileObjectDeleteButton',
                    icon: 'modules/KOrgObjects/images/delete.png',
                    tooltip: 'Delete the selected Object',
                    disabled: true
                }
            ]
        }
    ]
});


