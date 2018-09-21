Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthProfiles', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthProfiles',
    title: 'Profiles',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthProfilesController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthProfilesController',
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
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authprofiles', {storeId: 'KAuthManager.authprofiles'}),
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
                    itemId: 'profileFilter',
                    flex: 1,
                    enableKeyEvents: true,
                    emptyText: 'filter profiles'
                },{
                    xtype: 'button',
                    itemId: 'authProfileActivateButton',
                    icon: 'modules/KOrgObjects/images/bulb_on.png',
                    tooltip: 'toggle Activation Status of the selected object'
                },{
                    xtype: 'button',
                    itemId: 'authProfileAddButton',
                    icon: 'modules/KOrgObjects/images/add.png',
                    tooltip: 'add an Object'
                }, {
                    xtype: 'button',
                    itemId: 'authProfileDeleteButton',
                    disabled: true,
                    icon: 'modules/KOrgObjects/images/delete.png',
                    tooltip: 'Delete the selected Object'
                }
            ]
        }, {
            xtype: 'pagingtoolbar',
            store: 'KAuthManager.authprofiles',
            dock: 'bottom',
            displayInfo: true
        }
    ]
});


