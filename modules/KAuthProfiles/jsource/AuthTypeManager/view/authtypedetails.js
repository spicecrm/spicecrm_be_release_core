Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.view.AuthTypeDetails', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.KAuthManager.AuthTypeDetails',
    requires: [
        'SpiceCRM.KAuthManager.AuthTypeManager.controller.AuthTypeDetailsController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthTypeDetailsController',
    layout: 'vbox',
    style: {
        'background-color': 'transparent',
    },
    items: [
        {
            xtype: 'grid',
            title: 'fields',
            itemId: 'authtypefields',
            store: {
                type: 'KAuthManager.authtypefields',
                storeId: 'KAuthManager.authtypefields'
            },
            columns: [
                {
                    text: 'name',
                    dataIndex: 'name',
                    flex: 1,
                    sortable: true
                }
            ],
            width: '100%',
            flex: 1,
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        {
                            xtype: 'button',
                            itemId: 'authTypeAddFieldButton',
                            icon: 'modules/KOrgObjects/images/add.png',
                            text: 'add'
                        }, {
                            xtype: 'button',
                            itemId: 'authTypeDeleteFieldButton',
                            icon: 'modules/KOrgObjects/images/delete.png',
                            text: 'delete'
                        }
                    ]
                }
            ]
        }, {
            xtype: 'grid',
            title: 'Actions',
            width: '100%',
            itemId: 'authtypeactions',
            store: {
                type: 'KAuthManager.authtypeactions',
                storeId: 'KAuthManager.authtypeactions'
            },
            columns: [
                {
                    text: 'action',
                    dataIndex: 'action',
                    flex: 1,
                    sortable: true
                }
            ],
            flex: 1,
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    items: [
                        {
                            xtype: 'button',
                            itemId: 'authTypeAddActionButton',
                            icon: 'modules/KOrgObjects/images/add.png',
                            text: 'add'
                        }, {
                            xtype: 'button',
                            itemId: 'authTypetDeleteActionButton',
                            icon: 'modules/KOrgObjects/images/delete.png',
                            text: 'delete'
                        }
                    ]
                }
            ]
        }
    ]
});


