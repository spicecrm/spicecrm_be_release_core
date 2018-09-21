Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.model.authtype', {
    extend: 'Ext.data.Model',
    fields: ['id', 'bean', 'status', 'usagecount']
});
Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.model.authtypeaction', {
    extend: 'Ext.data.Model',
    fields: ['id', 'action', 'shortcode']
});
Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.model.authtypefield', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name']
});
Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.model.field', {
	extend: 'Ext.data.Model',
	fields: ['name']
});
Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.model.module', {
	extend: 'Ext.data.Model',
	fields: ['name']
});
Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.authtypeactions', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.authtypeaction'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.authtypeaction',
    alias: 'store.KAuthManager.authtypeactions',
    autoLoad: true,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypeactions',
        appendId: true,
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.authtypefields', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.authtypefield'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.authtypefield',
    alias: 'store.KAuthManager.authtypefields',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypefields',
        appendId: true,
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.authtypes', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.authtype'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.authtype',
    alias: 'store.KAuthManager.authtypes',
    autoLoad: true,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypes',
        appendId: true,
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.fields', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.field'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.field',
    alias: 'store.KAuthManager.fields',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypefields/fields',
        appendId: true,
        reader: {
            type: 'json'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.modules', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.module'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.module',
    alias: 'store.KAuthManager.modules',
    autoLoad: true,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypes/modules',
        appendId: true,
        reader: {
            type: 'json'
        }
    }
});

/**
 *  @constructor
 */
Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.controller.Application', {
    extend: 'Ext.app.Controller',
    config: {
        listen: {
            global: {

            }
        }
    },
    doInit: function () {

    } ,
    finishInit: function () {

    },
    onLaunch: function () {

    }
});
Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.controller.AuthTypeDetailsController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthTypeDetailsController',
    currentRecord: undefined,
    config: {
        listen: {
            global: {
                AuthTypeSelected: function (_record) {

                    this.currentRecord = _record;

                    var _fieldStore = this.view.down('#authtypefields').getStore();
                    _fieldStore.removeAll();
                    _fieldStore.getProxy().extraParams.authType = _record.id;
                    _fieldStore.load();

                    var _actionsStore = this.view.down('#authtypeactions').getStore();
                    _actionsStore.removeAll();
                    _actionsStore.getProxy().extraParams.authType = _record.id;
                    _actionsStore.load();
                }
            }
        },
        control: {
            '#': {},
            '#authTypeAddFieldButton': {
                click: function () {
                    if (this.currentRecord)
                        Ext.create('SpiceCRM.KAuthManager.AuthTypeManager.window.addfieldwindow', {currentRecord: this.currentRecord}).show();
                }
            },
            '#authTypeDeleteFieldButton': {
                click: function (_button) {
                    var _records = this.view.down('#authtypefields').getSelection();
                    Ext.each(_records, function (_record) {
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authtypefields/' + _record.id,
                            method: 'DELETE',
                            success: function (response, opts) {
                                Ext.data.StoreManager.lookup('KAuthManager.authtypefields').remove(_record);
                            },
                            failure: function (response, opts) {

                            },
                            scope: this
                        });
                    });
                }
            },
            '#authTypeAddActionButton': {
                click: function () {
                    if (this.currentRecord)
                        Ext.Msg.prompt('Add Action', 'new action', function (btn, text) {
                            if (btn == 'ok') {
                                var newModel = {
                                    id: SpiceCRM.KAuthManager.AuthTypeManager.Application.kGuid(),
                                    kauthtype_id: this.currentRecord.id,
                                    action: text
                                };
                                Ext.Ajax.request({
                                    url: 'KREST/kauthprofiles/core/authtypeactions',
                                    method: 'POST',
                                    jsonData: Ext.encode(newModel),
                                    success: function (response, opts) {
                                        Ext.data.StoreManager.lookup('KAuthManager.authtypeactions').add(newModel);
                                        this.up('.window').close();
                                    },
                                    failure: function (response, opts) {

                                    },
                                    scope: this
                                });
                            }
                        }, this);
                }
            },
            '#authTypetDeleteActionButton': {
                click: function(){
                    var _records = this.view.down('#authtypeactions').getSelection();
                    Ext.each(_records, function (_record) {
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authtypeactions/' + _record.id,
                            method: 'DELETE',
                            success: function (response, opts) {
                                Ext.data.StoreManager.lookup('KAuthManager.authtypeactions').remove(_record);
                            },
                            failure: function (response, opts) {

                            },
                            scope: this
                        });
                    });
                }
            }
        }
    }
});



Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.controller.AuthTypesController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthTypesController',
    config: {
        listen: {
            global: {}
        },
        control: {
            '#': {
                select: function () {
                    var _records = this.view.getSelection();

                    Ext.globalEvents.fireEvent('AuthTypeSelected', _records[0]);



                    if(_records[0].get('usagecount') > 0)
                        this.view.down('#authTypetDeleteButton').disable();
                    else
                        this.view.down('#authTypetDeleteButton').enable();

                }
            },
            '#authTypeAddButton' : {
                click: function(){
                    Ext.create('SpiceCRM.KAuthManager.AuthTypeManager.window.addmodulewindow').show();
                }
            },
            '#authTypetDeleteButton':{
                click: function(){
                    var _records = this.view.getSelection();
                    Ext.each(_records, function(_record){
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authtypes/'+_record.id,
                            method: 'DELETE',
                            success: function(response, opts) {
                                Ext.data.StoreManager.lookup('KAuthManager.authtypes').remove(_record);
                            },
                            failure: function(response, opts) {

                            },
                            scope: this
                        });
                    });
                }
            }
        }
    }
});



Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.controller.MainController', {
    extend: 'Ext.app.ViewController',
    requires: [
        //  'Ext.MessageBox'
    ],
    saving: false,
    alias: 'controller.AuthTypeManager.main',
    loadMask: undefined,
    config: {
        listen: {
            global: {

            }
        }
    }
});



Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.window.addfieldwindow', {
    extend: 'Ext.window.Window',
    title: 'Add Field',
    layout: 'fit',
    width: 500,
    height: 300,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    currentRecord: undefined,
    items: [
        {
            xtype: 'grid',
            itemId: 'fieldGrid',
            store: {
                type: 'KAuthManager.fields'
            },
            columns: [
                {
                    text: 'name',
                    dataIndex: 'name',
                    flex: 1,
                    sortable: true
                }
            ]
        }
    ],
    dockedItems: [{
        xtype: 'toolbar',
        dock: 'bottom',
        items: [
            {
                xtype: 'displayfield',
                itemId: 'messagecontainer'
            },
            '->',
            {
                text: 'Cancel',
                handler: function () {
                    this.up('.window').close();
                }
            }, {
                text: 'Add',
                handler: function (_button) {
                    var _grid = _button.up('window').down('grid'),
                        _selection = _grid.getSelection();
                    if (_selection.length > 0) {
                        var newModel = {
                            id: SpiceCRM.KAuthManager.AuthTypeManager.Application.kGuid(),
                            kauthtype_id: this.up('window').currentRecord.id,
                            name: _selection[0].get('name')
                        };
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authtypefields',
                            method: 'POST',
                            jsonData: Ext.encode(newModel),
                            success: function (response, opts) {
                                Ext.data.StoreManager.lookup('KAuthManager.authtypefields').add(newModel);
                                this.up('.window').close();
                            },
                            failure: function (response, opts) {

                            },
                            scope: this
                        });
                    } else {
                        _button.up('window').down('#messagecontainer').setValue('no field selected');
                    }
                }
            }
        ]
    }
    ],
    listeners: {
        show: function () {
            var _store = this.down('#fieldGrid').getStore();
            _store.removeAll();
            _store.getProxy().extraParams.authtypemodule = this.currentRecord.get('bean');
            _store.getProxy().extraParams.authtypeid = this.currentRecord.get('id');
            _store.load();
        }
    }
})
;

Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.window.addmodulewindow', {
    extend: 'Ext.window.Window',
    title: 'Add Module',
    layout: 'fit',
    width: 500,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    items: [
        {
            xtype: 'fieldset',
            defaults: {
                width: '100%'
            },
            items: [
                {
                    xtype: 'combo',
                    fieldLabel: 'module',
                    itemId: 'moduleCombo',
                    typeAhead: true,
                    triggerAction: 'all',
                    queryMode: 'local',
                    editable: true,
                    store: {
                        type: 'KAuthManager.modules'
                    },
                    displayField: 'name',
                    valueField: 'name'
                }
            ]
        }
    ],
    buttons: [
        {
            text: 'Cancel',
            handler: function () {
                this.up('.window').close();
            }
        }, {
            text: 'Add',
            handler: function (_button) {
                var newModel = {
                    id: SpiceCRM.KAuthManager.AuthTypeManager.Application.kGuid(),
                    bean: _button.up('window').down('#moduleCombo').getValue()
                };
                Ext.Ajax.request({
                    url: 'KREST/kauthprofiles/core/authtypes',
                    method: 'POST',
                    jsonData: Ext.encode(newModel),
                    success: function(response, opts) {
                        Ext.data.StoreManager.lookup('KAuthManager.authtypes').add(newModel);
                        this.up('.window').close();
                    },
                    failure: function(response, opts) {

                    },
                    scope: this
                });
            }
        }
    ],
    listeners: {
        show: function () {

        }
    }
})
;

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



Ext.enableAriaButtons = false;

Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.Application', {
    namespaces: ['SpiceCRM.KAuthManager.AuthTypeManager'],
    controllers: ['Application'],
    extend: 'Ext.app.Application',
    name: 'SpiceCRM.KAuthManager.AuthTypeManager',
    thisMainView: false,
    launch: function () {
        SpiceCRM.KAuthManager.AuthTypeManager.Application = this;

        if (this.thisMainView) this.destroyMainView();
        this.render();

    },
    destroyMainView: function () {
        this.thisMainView.destroy();
        this.thisMainView = false;
    },
    render: function () {
        // Ext.create('SpiceCRM.KOrgManager.CoreConfigurator.view.maintoolbar');
        this.thisMainView = Ext.create('SpiceCRM.KAuthManager.AuthTypeManager.view.main.Main');

        Ext.get(window).on({
            resize: function () {
                if (SpiceCRM.KAuthManager.AuthTypeManager.Application.thisMainView)
                    SpiceCRM.KAuthManager.AuthTypeManager.Application.thisMainView.updateLayout();
            }
        });
    },
    languageGetText: function (_keyID) {
        return SUGAR.language.get('KAuthProfiles', _keyID);
    },
    getRand: function () {
        return Math.random();
    },
    S4: function () {
        return (((1 + this.getRand()) * 0x10000) | 0).toString(16).substring(1);
    },
    kGuid: function () {
        return ('k' + this.S4() + this.S4() + this.S4() + this.S4() + this.S4() + this.S4() + this.S4());
    }
});

Ext.application({
    extend: 'SpiceCRM.KAuthManager.AuthTypeManager.Application'
});