Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthProfilesController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthProfilesController',
    config: {
        listen: {
            global: {}
        },
        control: {
            '#': {
                select: function (_selmode, _record) {
                    Ext.globalEvents.fireEvent('AuthProfileSelected', _record);

                    if (_record.get('status') !== 'r') {
                        this.view.down('#authProfileDeleteButton').enable();
                        this.view.down('#authProfileActivateButton').setIcon('modules/KAuthProfiles/images/bulb_on.png');
                        this.view.down('#authProfileActivateButton').setTooltip('Activate Profile');
                    } else {
                        this.view.down('#authProfileDeleteButton').disable();
                        this.view.down('#authProfileActivateButton').setIcon('modules/KAuthProfiles/images/bulb_off.png');
                        this.view.down('#authProfileActivateButton').setTooltip('Dectivate Profile');
                    }
                },
                edit: function (_editor, _e) {
                    _e.record.save();
                }
            },
            '#profileFilter': {
                keyup: function (_field, _e) {
                    if (_e.getKey() == _e.ENTER) {
                        this.view.getStore().getProxy().extraParams.searchterm = _field.getValue();
                        this.view.down('pagingtoolbar').moveFirst();
                    }
                }
            },
            '#authProfileActivateButton': {
                click: function () {
                    this.view.up('#AuthProfileManagerPanel').showLoadMask();
                    var _selction = this.view.getSelection();
                    Ext.each(_selction, function (_selRecord) {
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authprofiles/' + _selRecord.id + (_selRecord.get('status') === 'r' ? '/deactivate' : '/activate'),
                            method: 'POST',
                            success: function (response, opts) {
                                _selRecord.set('status', (_selRecord.get('status') === 'r' ? 'd' : 'r'));
                                Ext.globalEvents.fireEvent('AuthProfileStatusChange', _selRecord);
                                this.view.up('#AuthProfileManagerPanel').hideLoadMask();
                            },
                            failure: function (response, opts) {
                                this.view.up('#AuthProfileManagerPanel').hideLoadMask();
                            },
                            scope: this
                        });
                    }, this);

                }
            },
            '#authProfileAddButton': {
                click: function () {
                    Ext.Msg.prompt('Add Profile', 'Name of new Profile:', function (btn, text) {
                        if (btn == 'ok') {
                            var newModel = {
                                id: SpiceCRM.KAuthManager.AuthProfileManager.Application.kGuid(),
                                name: text,
                                status: 'd'
                            };
                            // load the fields
                            Ext.Ajax.request({
                                url: 'KREST/kauthprofiles/core/authprofiles/' + newModel.id,
                                method: 'POST',
                                jsonData: Ext.encode(newModel),
                                success: function (response, opts) {
                                    var _record = this.view.getStore().add(newModel);
                                    this.view.getStore().sort('name', 'ASC');
                                    this.view.setSelection(_record);
                                    // Ext.globalEvents.fireEvent('AuthProfileSelected', _record);
                                },
                                failure: function (response, opts) {

                                },
                                scope: this
                            });
                        }
                    }, this);
                }
            },
            '#authProfileDeleteButton': {
                click: function () {
                    Ext.each(this.view.getSelection(), function (_selRecord) {
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authprofiles/' + _selRecord.id,
                            method: 'DELETE',
                            success: function (response, opts) {
                                this.view.getStore().remove(_selRecord);
                            },
                            failure: function (response, opts) {

                            },
                            scope: this
                        });
                    }, this);
                    Ext.globalEvents.fireEvent('AuthProfileDeleted');
                }
            }
        }
    }
});


