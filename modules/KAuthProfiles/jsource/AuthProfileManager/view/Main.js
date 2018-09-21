Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.main.Main', {
    extend: 'Ext.panel.Panel',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.MainController'
    ],
    xtype: 'app-main',
    renderTo: 'authprofilemanager',
    height: '100%',
    width: '100%',
    border: false,
    controller: 'AuthProfileManager.main',
    layout: 'fit',
    itemId: 'AuthProfileManagerPanel',
    loadMask: undefined,
    items: [
        {
            xtype: 'tabpanel',
            width: '100%',
            items: [
                {
                    xtype: 'panel',
                    title: 'Auth Objects',
                    layout: 'hbox',
                    items: [
                        {
                            xtype: 'KAuthManager.AuthObjects',
                            height: '100%',
                            flex: 2,
                            border: false,
                            style: {
                                'border-right' : '1px solid rgb(208, 208, 208)'
                            }
                        }, {
                            xtype: 'panel',
                            height: '100%',
                            flex: 1,
                            border: false,
                            layout: 'vbox',
                            items: [
                                {
                                    xtype: 'KAuthManager.AuthObjectsOrgValues',
                                    disabled: true,
                                    width: '100%',
                                    flex: 1
                                },{
                                    xtype: 'KAuthManager.AuthObjectsFieldValues',
                                    width: '100%',
                                    flex: 1
                                },{
                                    xtype: 'KAuthManager.AuthObjectsFieldControls',
                                    width: '100%',
                                    disabled: true,
                                    flex: 1
                                }
                            ]
                        }
                    ]
                }, {
                    xtype: 'panel',
                    title: 'Auth Profiles',
                    layout: 'hbox',
                    items: [
                        {
                            xtype: 'KAuthManager.AuthProfiles',
                            height: '100%',
                            flex: 1,
                            border: false,
                            style: {
                                'border-right' : '1px solid rgb(208, 208, 208)'
                            }
                        }, {
                            xtype: 'KAuthManager.AuthProfileObjects',
                            height: '100%',
                            flex: 1
                        }
                    ]
                }, {
                    xtype: 'panel',
                    title: 'Auth Assignment',
                    height: '100%',
                    layout: 'hbox',
                    items: [
                        {
                            xtype: 'KAuthManager.AuthAssignmentUsers',
                            height: '100%',
                            flex: 1,
                            border: false,
                            style: {
                                'border-right' : '1px solid rgb(208, 208, 208)'
                            }
                        }, {
                            xtype: 'KAuthManager.AuthAssignmentProfiles',
                            height: '100%',
                            flex: 1
                        }
                    ]
                }
            ]
        }
    ],
    showLoadMask: function(){
        if(!this.loadMask)
            this.loadMask = Ext.create('Ext.LoadMask', {target: this});

        this.loadMask.show();
    },
    hideLoadMask: function(){
        this.loadMask.hide();
    }
});


