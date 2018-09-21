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


