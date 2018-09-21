Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectFieldControlsController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthObjectFieldControlsController',
    currentRecordId: undefined,
    config: {
        listen: {
            global: {
                AuthProfileManagerElementAuthFieldData: function (_fieldData) {
                    this.view.enable();
                    this.view.getStore().removeAll();
                    this.currentRecordId = undefined;
                },
                AuthProfileManagerObjectDataRetrieved: function (_data) {
                    this.view.getStore().removeAll();

                    this.currentRecordId = _data.object.id;

                    if (_data.fieldcontrols) {
                        this.view.getStore().add(_data.fieldcontrols);
                    }
                }
            }
        },
        control: {
            '#': {
                edit: function (_editor, _e) {
                    Ext.Ajax.request({
                        url: 'KREST/kauthprofiles/core/authobjects/fieldcontrol',
                        method: 'PUT',
                        jsonData: Ext.encode(_e.record.data),
                        scope: this
                    });
                }
            },
            '#authObjectFieldControlAddButton': {
                click: function () {
                    if (this.currentRecordId)
                        Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.window.addfieldwindow', {currentRecordId: this.currentRecordId}).show();
                }
            },
            '#authObjectFieldControlDeleteButton': {
                click: function () {
                    var _selection = this.view.getSelection();

                    Ext.each(_selection, function (_selRecord) {
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authobjects/fieldcontrol',
                            method: 'DELETE',
                            jsonData: Ext.encode(_selRecord.data),
                            success: function () {
                                this.view.getStore().remove(_selRecord);
                            },
                            scope: this
                        });
                    }, this);
                }
            }
        }
    }
});


