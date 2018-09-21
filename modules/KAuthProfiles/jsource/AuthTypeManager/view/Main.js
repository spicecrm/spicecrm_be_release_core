Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.view.main.Main', {
    extend: 'Ext.panel.Panel',
    requires: [
        'SpiceCRM.KAuthManager.AuthTypeManager.controller.MainController'
    ],
    xtype: 'app-main',
    renderTo: 'authtypemanager',
    height: '100%',
    width: '100%',
    border: false,
    controller: 'AuthTypeManager.main',
    layout: 'fit',
    titel: 'Auith Types',
    style: {
        'background-color': 'transparent',
    },
    items: [
        {
            xtype: 'panel',
            layout: 'hbox',
            width: '100%',
            items: [
                {
                    xtype: 'KAuthManager.AuthTypes',
                    height: '100%',
                    flex: 1
                }, {
                    xtype: 'KAuthManager.AuthTypeDetails',
                    height: '100%',
                    flex: 1
                }
            ]
        }
    ]
});


