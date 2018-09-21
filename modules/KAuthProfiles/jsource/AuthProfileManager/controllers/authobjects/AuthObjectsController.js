Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectsController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthObjectsController',
    currentAuthType: null,
    config: {
        listen: {
            global: {}
        },
        control: {
            '#': {
                select: function (_selmode, _record) {
                    this.view.up('#AuthProfileManagerPanel').showLoadMask();

                    Ext.Ajax.request({
                        url: 'KREST/kauthprofiles/core/authobjects/' + _record.id,
                        method: 'GET',
                        success: function (response, opts) {
                            var _responseObj = Ext.decode(response.responseText);
                            Ext.globalEvents.fireEvent('AuthProfileManagerObjectDataRetrieved', _responseObj);

                            if(_responseObj.object.status !== 'r'){
                                this.view.down('#authObjectDeleteButton').enable();
                                this.view.down('#authObjectActivateButton').setIcon('modules/KAuthProfiles/images/bulb_on.png');
                                this.view.down('#authObjectActivateButton').setTooltip('Activate Object');
                            } else {
                                this.view.down('#authObjectDeleteButton').disable();
                                this.view.down('#authObjectActivateButton').setIcon('modules/KAuthProfiles/images/bulb_off.png');
                                this.view.down('#authObjectActivateButton').setTooltip('Dectivate Object');
                            }
                            this.view.up('#AuthProfileManagerPanel').hideLoadMask();
                        },
                        failure: function (response, opts) {
                            this.view.up('#AuthProfileManagerPanel').hideLoadMask();
                        },
                        scope: this
                    });

                },
                edit: function (_editor, _e) {
                    _e.record.save();
                }
            },
            '#authTypeCombo': {
                select: function (_combo, _record) {
                    this.view.up('#AuthProfileManagerPanel').showLoadMask();

                    this.currentAuthType = _record.id;

                    this.view.getStore().removeAll();
                    this.view.getStore().getProxy().extraParams.kauthtypeid = _record.id;
                    this.view.getStore().load();

                    // get the details on the object
                    Ext.Ajax.request({
                        url: 'KREST/kauthprofiles/core/authtypes/' + _record.id,
                        method: 'GET',
                        success: function (response, opts) {
                            var _responseObj = Ext.decode(response.responseText);
                            Ext.globalEvents.fireEvent('AuthProfileManagerElementOrgData', _responseObj.orgtype);
                            Ext.globalEvents.fireEvent('AuthProfileManagerElementAuthFieldData', _responseObj.authtypefields);

                            this.view.up('#AuthProfileManagerPanel').hideLoadMask();
                        },
                        failure: function (response, opts) {
                            this.view.up('#AuthProfileManagerPanel').hideLoadMask();
                        },
                        scope: this
                    });
                }
            },
            '#objectFilter': {
                keyup: function (_field, _e) {
                    if (_e.getKey() == _e.ENTER) {
                        this.view.getStore().getProxy().extraParams.searchterm = _field.getValue();
                        this.view.down('pagingtoolbar').moveFirst();
                    }
                }
            },
            '#authObjectActivateButton': {
                click: function(){
                    this.view.up('#AuthProfileManagerPanel').showLoadMask();
                    var _selction = this.view.getSelection();
                    Ext.each(_selction, function(_selRecord){
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authobjects/' + _selRecord.id + (_selRecord.get('status') === 'r' ? '/deactivate': '/activate'),
                            method: 'POST',
                            success: function (response, opts) {
                                _selRecord.set('status', (_selRecord.get('status') === 'r' ? 'd': 'r'));
                                Ext.globalEvents.fireEvent('AuthObjectStatusChange', _selRecord);
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
            '#authObjectAddButton':{
                click: function(){
                    Ext.Msg.prompt('Add Object', 'Name of new Object:', function (btn, text) {
                        if (btn == 'ok') {
                            var newModel = {
                                id: SpiceCRM.KAuthManager.AuthProfileManager.Application.kGuid(),
                                kauthtype_id: this.currentAuthType,
                                name: text,
                                status: 'd',
                                kauthobjecttype: 0,
                                kauthorgassignment: 0,
                                kauthowner: 0,
                                allorgobjects: 0,
                                activitiy: 0
                            };
                            // load the fields
                            Ext.Ajax.request({
                                url: 'KREST/kauthprofiles/core/authobjects/' + newModel.id,
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
            }
        }
    }
});


