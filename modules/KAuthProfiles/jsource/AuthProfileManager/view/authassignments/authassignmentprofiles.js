Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthAssignmentProfiles', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthAssignmentProfiles',
    title: 'Profiles',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthAssignmentProfilesController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthAssignmentProfilesController',
    layout: 'fit',
    style: {
        'background-color': 'transparent',
    },
    selModel: 'rowmodel',
    store: {
        type: 'KAuthManager.authprofiles',
        storeId: 'KAuthManager.authassignmentprofiles',
        autoLoad: false
    },
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true
        },
        items: [
            {
                text: 'name',
                dataIndex: 'name',
                flex: 2,
                editor: {
                    xtype: 'textfield'
                }
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
                    itemId: 'authProfileAddButton',
                    icon: 'modules/KOrgObjects/images/add.png',
                    tooltip: 'add an Object',
                    disabled: true
                }, {
                    xtype: 'button',
                    itemId: 'authProfileDeleteButton',
                    disabled: true,
                    icon: 'modules/KOrgObjects/images/delete.png',
                    tooltip: 'Delete the selected Object'
                }
            ]
        }
    ]
});


