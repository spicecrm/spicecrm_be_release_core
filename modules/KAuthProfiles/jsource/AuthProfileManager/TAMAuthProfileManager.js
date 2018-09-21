Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authassignmentuser', {
    extend: 'Ext.data.Model',
    fields: ['id', 'user_name', 'first_name', 'last_name']
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authobject', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name', 'status', 'customSQL', 'description', 'usagecount', 'kauthobjecttype', 'activity'],
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects',
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectfieldcontrol', {
    extend: 'Ext.data.Model',
    fields: ['kauthobject_id', 'field', 'control']
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectfieldvalue', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name', 'operator', 'value1', 'value2']
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectorgvalue', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name', 'value', 'displayvalue']
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authprofile', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name', 'status', 'usagecount'],
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authprofiles',
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authprofileobject', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name', 'status', 'bean']
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authtype', {
    extend: 'Ext.data.Model',
    fields: ['id', 'bean']
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.field', {
	extend: 'Ext.data.Model',
	fields: ['name']
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authassignmentusers', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authassignmentuser'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authassignmentuser',
    alias: 'store.KAuthManager.authassignmentuser',
    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authusers',
        reader: {
            type: 'json',
            rootProperty: 'records',
            totalProperty: 'totalcount'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectfieldcontrols', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectfieldcontrol'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectfieldcontrol',
    alias: 'store.KAuthManager.authobjectfieldcontrols',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects/fieldcontrols',
        reader: {
            type: 'json'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectfieldvalues', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectfieldvalue'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectfieldvalue',
    alias: 'store.KAuthManager.authobjectorgvalues',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects/fieldvalues',
        reader: {
            type: 'json'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectorgvalues', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectorgvalue'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectorgvalue',
    alias: 'store.KAuthManager.authobjectorgvalues',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects/orgvalues',
        reader: {
            type: 'json'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjects', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authobject'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authobject',
    alias: 'store.KAuthManager.authobjects',
    autoLoad: false,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects',
        reader: {
            type: 'json',
            rootProperty: 'records',
            totalProperty: 'totalcount'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authprofileobjects', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authprofileobject'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authprofileobject',
    alias: 'store.KAuthManager.authprofileobjects',
    autoLoad: false,
    proxy: {
        type: 'memory'
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authprofiles', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authprofile'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authprofile',
    alias: 'store.KAuthManager.authprofiles',
    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authprofiles',
        reader: {
            type: 'json',
            rootProperty: 'records',
            totalProperty: 'totalcount'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authtypes', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authtype'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authtype',
    alias: 'store.KAuthManager.authtypes',
    autoLoad: true,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypes',
        appendId: true,
        reader: {
            type: 'json'
        }
    }
});

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.fields', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.field'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.field',
    alias: 'store.AuthProfileManager.fields',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects/fieldcontrol/fields',
        appendId: true,
        reader: {
            type: 'json'
        }
    }
});


Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.Application', {
    extend: 'Ext.app.Controller',
    doInit: function () {

    } ,
    finishInit: function () {

    },
    onLaunch: function () {

    }
});
Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthAssignmentProfilesController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthAssignmentProfilesController',
    currentUserId: undefined,
    config: {
        listen: {
            global: {
                AuthProfileManagerUserDataRetrieved: function (_userId, _records) {
                    // enable the add button
                    this.view.down('#authProfileAddButton').enable();
                    this.view.down('#authProfileDeleteButton').disable();

                    // set the current user id
                    this.currentUserId = _userId;

                    this.view.getStore().removeAll();
                    this.view.getStore().add(_records);
                }
            }
        },
        control: {
            '#': {
                select: function (_selmode, _record) {
                    this.view.down('#authProfileDeleteButton').enable();
                }
            },
            '#authProfileAddButton': {
                click: function () {
                    Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.window.addprofilewindow', {currentUserId: this.currentUserId}).show();
                }
            },
            '#authProfileDeleteButton': {
                click: function () {
                    Ext.each(this.view.getSelection(), function (_record) {
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authusers/' + this.currentUserId + '/authprofiles/' + _record.get('id'),
                            method: 'DELETE',
                            success: function (response, opts) {
                                this.view.getStore().remove(_record);
                            },
                            failure: function (response, opts) {

                            },
                            scope: this
                        });

                    }, this);
                }
            }
        }
    }
});



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthAssignmentUsersController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthAssignmentUsersController',
    config: {
        listen: {
            global: {}
        },
        control: {
            '#': {
                select: function (_selmode, _record) {
                    this.view.up('#AuthProfileManagerPanel').showLoadMask();
                    Ext.Ajax.request({
                        url: 'KREST/kauthprofiles/core/authusers/' + _record.id + '/authprofiles',
                        method: 'GET',
                        success: function (response, opts) {
                            var _responseObj = Ext.decode(response.responseText);
                            Ext.globalEvents.fireEventArgs('AuthProfileManagerUserDataRetrieved', [_record.id, _responseObj]);

                            this.view.up('#AuthProfileManagerPanel').hideLoadMask();
                        },
                        failure: function (response, opts) {
                            this.view.up('#AuthProfileManagerPanel').hideLoadMask();
                        },
                        scope: this
                    });
                }
            },
            '#userFilter': {
                keyup: function (_field, _e) {
                    if (_e.getKey() == _e.ENTER) {
                        this.view.getStore().getProxy().extraParams.searchterm = _field.getValue();
                        this.view.down('pagingtoolbar').moveFirst();
                    }
                }
            }
        }
    }
});



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



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectFieldValuesController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthObjectFieldValuesController',
    config: {
        listen: {
            global: {
                AuthProfileManagerElementAuthFieldData: function(_fieldData){
                    this.view.getStore().removeAll();
                    if(_fieldData){
                        Ext.each(_fieldData, function(_field){
                            this.view.getStore().add(_field);
                        }, this);
                    }
                },
                AuthProfileManagerObjectDataRetrieved: function(_data){
                    this.view.store.each(function(_record){
                        _record.set({operator: '', value1: '', value2: ''});
                    }, this);

                    if(_data.fieldvalues){
                        Ext.each(_data.fieldvalues, function(_fieldvalue){
                            var _valRecord = this.view.getStore().getById(_fieldvalue.kauthtypefield_id);
                            if(_valRecord) _valRecord.set({
                                'operator': _fieldvalue.operator,
                                'value1': _fieldvalue.value1,
                                'value2': _fieldvalue.value2
                            });
                        }, this);
                    }
                }
            }
        },
        control: {

        }
    }
});



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectOrgValuesController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthObjectOrgValuesController',
    currentRecordId: undefined,
    config: {
        listen: {
            global: {
                AuthProfileManagerElementOrgData: function (_orgData) {
                    this.view.getStore().removeAll();
                    this.view.disable();
                    if (_orgData) {
                        Ext.each(_orgData.orgelements, function (_element) {
                            this.view.getStore().add(_element);
                        }, this);
                    }
                },
                AuthProfileManagerObjectDataRetrieved: function (_data) {

                    this.currentRecordId = _data.object.id;

                    this.view.store.each(function (_record) {
                        _record.set({
                            value: '',
                            displayvalue: ''
                        });
                    }, this);

                    if (this.view.getStore().getCount() > 0) {
                        Ext.each(_data.orgvalues, function (_orgValue) {
                            var _valRecord = this.view.getStore().getById(_orgValue.korgobjectelement_id);
                            if (_valRecord) _valRecord.set({
                                value: _orgValue.value,
                                displayvalue: _orgValue.displayvalue
                            });
                        }, this);

                        this.view.down('#orgAssignment').setValue(_data.object.kauthorgassignment);
                        this.view.down('#orgAssignment').enable();

                    } else {
                        // set to 3 and disable
                        this.view.down('#orgAssignment').setValue('3');
                        this.view.down('#orgAssignment').disable();
                    }

                    // set the owner flag
                    this.view.down('#assignedUser').setValue(_data.object.kauthowner);

                    // enalbe or disable the view
                    if (_data.object.status === 'r')
                        this.view.disable();
                    else
                        this.view.enable();
                },
                AuthObjectStatusChange: function(_record){
                    if(_record.get('status') === 'r')
                        this.view.disable();
                    else
                        this.view.enable();
                }
            }
        },
        control: {
            '#' : {
                edit: function(editor, e){
                    // console.log(e);
                    var orgValues = [];
                    this.view.getStore().each(function(record){
                        orgValues.push(record.data);
                    });
                    // load the fields
                    Ext.Ajax.request({
                        url: 'KREST/kauthprofiles/core/authobjects/orgvalues/' + this.currentRecordId,
                        method: 'POST',
                        jsonData: Ext.encode(orgValues),
                        success: function (response, opts) {

                        },
                        failure: function (response, opts) {

                        },
                        scope: this
                    });
                }
            }
        }
    }
});



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



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthProfileObjectsController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthProfileObjectsController',
    currentProfileRecord: undefined,
    config: {
        listen: {
            global: {
                AuthProfileSelected: function (_record) {
                    this.currentProfileRecord = _record;

                    this.view.getStore().removeAll();
                    Ext.Ajax.request({
                        url: 'KREST/kauthprofiles/core/authprofiles/' + _record.id + '/authobjects',
                        method: 'GET',
                        success: function (response, opts) {
                            var responseObj = Ext.decode(response.responseText);
                            this.view.getStore().add(responseObj);
                        },
                        failure: function (response, opts) {

                        },
                        scope: this
                    });

                    // toggle the buttons
                    this.view.down('#authProfileObjectDeleteButton').disable();
                    if(_record.get('status') == 'r')
                        this.view.down('#authProfilObjectAddButton').disable();
                    else
                        this.view.down('#authProfilObjectAddButton').enable();


                },
                AuthProfileDeleted: function(){
                    // remove all entries and disable the delete button
                    this.view.getStore().removeAll();
                    this.view.down('#authProfileObjectDeleteButton').disable();
                }
            }
        },
        control: {
            '#': {
                select: function(_grid, _record){
                    if(this.currentProfileRecord.get('status') !== 'r')
                        this.view.down('#authProfileObjectDeleteButton').enable();
                }
            },
            '#authProfilObjectAddButton': {
                click: function () {
                    Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.window.addobjectwindow', {currentRecordId: this.currentProfileRecord.id}).show();
                }
            },
            '#authProfileObjectDeleteButton': {
                click: function () {
                    Ext.each(this.view.getSelection(), function(_selRecord){
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authprofiles/' + this.currentProfileRecord.id + '/authobjects/' + _selRecord.get('id'),
                            method: 'DELETE',
                            success: function (response, opts) {
                                this.view.getStore().remove(_selRecord);
                            },
                            failure: function (response, opts) {

                            },
                            scope: this
                        });
                    }, this);
                }
            }
        }
    }
});



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



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.MainController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.AuthProfileManager.main'
});



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.window.addfieldwindow', {
    extend: 'Ext.window.Window',
    title: 'Add Field',
    layout: 'fit',
    width: 500,
    height: 300,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    currentRecordId: undefined,
    items: [
        {
            xtype: 'grid',
            itemId: 'fieldGrid',
            store: {
                type: 'AuthProfileManager.fields'
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
                            kauthobject_id: this.up('window').currentRecordId,
                            field: _selection[0].get('name'),
                            control: '0'
                        };
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authobjects/fieldcontrol',
                            method: 'POST',
                            jsonData: Ext.encode(newModel),
                            success: function (response, opts) {
                                Ext.data.StoreManager.lookup('KAuthManager.authobjectsfieldcontrols').add(newModel);
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
            _store.getProxy().extraParams.authobjectid = this.currentRecordId;
            _store.load();
        }
    }
})
;

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.window.addobjectwindow', {
    extend: 'Ext.window.Window',
    title: 'Add Object',
    layout: 'fit',
    width: 500,
    height: 700,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    currentRecordId: undefined,
    currentBean: undefined,
    items: [
        {
            xtype: 'grid',
            itemId: 'objectGrid',
            store: {
                type: 'KAuthManager.authobjects',
                storeId: 'KAuthManager.authprofileobjectsadd',
                autoLoad: false
            },
            selModel: {
                type: 'rowmodel',
                mode: 'multi'
            },
            columns: [
                {
                    text: 'name',
                    dataIndex: 'name',
                    flex: 1,
                    sortable: true
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
            ],
            dockedItems: [{
                xtype: 'pagingtoolbar',
                store: 'KAuthManager.authprofileobjectsadd',
                dock: 'bottom',
                displayInfo: true
            }
            ]
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'combo',
                    flex: 1,
                    itemId: 'authTypeCombo',
                    typeAhead: true,
                    triggerAction: 'all',
                    queryMode: 'local',
                    editable: false,
                    store: {
                        type: 'KAuthManager.authtypes'
                    },
                    displayField: 'bean',
                    valueField: 'id',
                    listeners: {
                        select: function (_combo, _record) {
                            var _grid = _combo.up('window').down('grid');
                            _grid.getStore().removeAll();
                            _grid.getStore().getProxy().extraParams.kauthtypeid = _record.id;
                            _grid.getStore().load();

                            _combo.up('window').currentBean = _record.get('bean');
                        }
                    }
                }, {
                    xtype: 'textfield',
                    flex: 1,
                    itemId: 'authObjectFilter',
                    enableKeyEvents: true,
                    listeners: {
                        keyup: function (_field, _e) {
                            if (_e.getKey() == _e.ENTER) {
                                var _grid = _field.up('window').down('grid');
                                _grid.getStore().getProxy().extraParams.searchterm = _field.getValue();
                                // _grid.getStore().load();
                                _field.up('window').down('pagingtoolbar').moveFirst();
                            }
                        }
                    }
                }
            ]
        }, {
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
                            Ext.each(_selection, function (_thisSelection) {
                                var newModel = {
                                    id: _thisSelection.get('id'),
                                    name: _thisSelection.get('name'),
                                    status: _thisSelection.get('status'),
                                    bean: this.up('window').currentBean
                                };
                                Ext.Ajax.request({
                                    url: 'KREST/kauthprofiles/core/authprofiles/' + this.up('window').currentRecordId + '/authobjects/' + _thisSelection.get('id'),
                                    method: 'POST',
                                    success: function (response, opts) {
                                        Ext.data.StoreManager.lookup('KAuthManager.authprofileobjects').add(newModel);

                                    },
                                    failure: function (response, opts) {

                                    },
                                    scope: this
                                });
                            }, this);
                            this.up('.window').close();
                        } else {
                            _button.up('window').down('#messagecontainer').setValue('no Object selected');
                        }
                    }
                }
            ]
        }
    ],
    listeners: {
        show: function () {
            var _store = this.down('#objectGrid').getStore();
            _store.removeAll();
            _store.getProxy().extraParams.authprofileid = this.currentRecordId;
        }
    }
})
;

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.window.addprofilewindow', {
    extend: 'Ext.window.Window',
    title: 'Add Profile',
    layout: 'fit',
    width: 500,
    height: 700,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    currentUserId: undefined,
    items: [
        {
            xtype: 'grid',
            store: {
                type: 'KAuthManager.authprofiles',
                storeId: 'KAuthManager.authprofilesadd',
                autoLoad: false
            },
            selModel: {
                type: 'rowmodel',
                mode: 'multi'
            },
            columns: [
                {
                    text: 'name',
                    dataIndex: 'name',
                    flex: 1,
                    sortable: true
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
            ],
            dockedItems: [{
                xtype: 'pagingtoolbar',
                store: 'KAuthManager.authprofilesadd',
                dock: 'bottom',
                displayInfo: true
            }
            ]
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'textfield',
                    flex: 1,
                    itemId: 'authProfileFilter',
                    enableKeyEvents: true,
                    listeners: {
                        keyup: function (_field, _e) {
                            if (_e.getKey() == _e.ENTER) {
                                var _grid = _field.up('window').down('grid');
                                _grid.getStore().getProxy().extraParams.searchterm = _field.getValue();
                                // _grid.getStore().load();
                                _field.up('window').down('pagingtoolbar').moveFirst();
                            }
                        }
                    }
                }/*, {
                 xtype: 'checkbox',
                 itemId: 'authProfileActiveOnlyFilter',
                 fieldLabel: 'active only',
                 listeners: {
                 change: function (_field, _newValue) {
                 var _grid = _field.up('window').down('grid');
                 _grid.getStore().getProxy().extraParams.activeOnly = _newValue;
                 // _grid.getStore().load();
                 _field.up('window').down('pagingtoolbar').moveFirst();
                 }
                 }
                 }*/
            ]
        }, {
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
                            Ext.each(_selection, function (_thisSelection) {
                                var newModel = {
                                    id: _thisSelection.get('id'),
                                    name: _thisSelection.get('name'),
                                    status: _thisSelection.get('status')
                                };
                                Ext.Ajax.request({
                                    url: 'KREST/kauthprofiles/core/authusers/' + this.up('window').currentUserId + '/authprofiles/' + _thisSelection.get('id'),
                                    method: 'POST',
                                    success: function (response, opts) {
                                        Ext.data.StoreManager.lookup('KAuthManager.authassignmentprofiles').add(newModel);

                                    },
                                    failure: function (response, opts) {

                                    },
                                    scope: this
                                });
                            }, this);
                            this.up('.window').close();
                        } else {
                            _button.up('window').down('#messagecontainer').setValue('no Profile selected');
                        }
                    }
                }
            ]
        }
    ],
    listeners: {
        show: function () {
            var _store = this.down('grid').getStore();
            _store.removeAll();
            _store.getProxy().extraParams.authuserid = this.currentUserId;
            _store.load();
        }
    }
})
;

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



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthAssignmentUsers', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthAssignmentUsers',
    title: 'Profiles',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthAssignmentUsersController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthAssignmentUsersController',
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
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authassignmentusers', {storeId: 'KAuthManager.authassignmentusers'}),
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true
        },
        items: [
            {
                text: 'user name',
                dataIndex: 'user_name',
                flex: 2
            },{
                text: 'first name',
                dataIndex: 'first_name',
                flex: 2
            },{
                text: 'last name',
                dataIndex: 'last_name',
                flex: 2
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
                    itemId: 'userFilter',
                    flex: 1,
                    enableKeyEvents: true,
                    emptyText: 'filter users'
                }
            ]
        }, {
            xtype: 'pagingtoolbar',
            store: 'KAuthManager.authassignmentusers',
            dock: 'bottom',
            displayInfo: true
        }
    ]
});



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthObjects', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthObjects',
    title: 'Objects',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectsController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthObjectsController',
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
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjects', {storeId: 'KAuthManager.authobjects'}),
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
            }, {
                text:'<img src="modules/KAuthProfiles/images/sql.png"/>',
                dataIndex: 'customSQL',
                width: 40,
                align: 'center',
                renderer: function (_value) {
                    if (_value && _value !== '')
                        return '<img src="modules/KAuthProfiles/images/sql.png"/>';
                    else
                        return '';
                }
            }, {
                text:'<img src="modules/KAuthProfiles/images/description.png"/>',
                dataIndex: 'description',
                width: 40,
                align: 'center',
                renderer: function (_value) {
                    if (_value && _value !== '')
                        return '<img src="modules/KAuthProfiles/images/description.png"/>';
                    else
                        return '';
                }
            }, {
                text: 'type',
                dataIndex: 'kauthobjecttype',
                flex: 1,
                editor: {
                    xtype: 'combo',
                    typeAhead: true,
                    triggerAction: 'all',
                    selectOnTab: true,
                    store: Ext.create('Ext.data.Store', {
                            fields: ['value', 'name'],
                            data: [
                                {
                                    value: '0',
                                    name: 'standard'
                                }, {
                                    value: '1',
                                    name: 'restrict (all)'
                                }, {
                                    value: '2',
                                    name: 'exclude (all)'
                                }, {
                                    value: '3',
                                    name: 'limit activity'
                                }, {
                                    value: '4',
                                    name: 'restrict (profile)'
                                }, {
                                    value: '5',
                                    name: 'exclude (profile)'
                                }
                            ],
                            storeId: 'objectTypesStore'
                        }
                    ),
                    displayField: 'name',
                    valueField: 'value',
                    lazyRender: true
                },
                renderer: function (_value) {
                    var _store = Ext.data.StoreManager.lookup('objectTypesStore'),
                        _record = _store.findRecord('value', _value);

                    if (_record)
                        return _record.get('name');
                    else
                        return _value;

                }
            }, {
                text: 'activity',
                dataIndex: 'activity',
                flex: 1,
                editor: {
                    xtype: 'combo',
                    typeAhead: true,
                    triggerAction: 'all',
                    selectOnTab: true,
                    store: Ext.create('Ext.data.Store', {
                            fields: ['value', 'name'],
                            data: [
                                {
                                    value: '0',
                                    name: 'List'
                                }, {
                                    value: '1',
                                    name: 'View'
                                }, {
                                    value: '2',
                                    name: 'Edit'
                                }, {
                                    value: '3',
                                    name: 'Create'
                                }, {
                                    value: '4',
                                    name: 'Delete'
                                }, {
                                    value: '5',
                                    name: '*'
                                }
                            ],
                            storeId: 'objectActivitiesStore'
                        }
                    ),
                    displayField: 'name',
                    valueField: 'value',
                    lazyRender: true
                },
                renderer: function (_value) {
                    var _store = Ext.data.StoreManager.lookup('objectActivitiesStore'),
                        _record = _store.findRecord('value', _value);

                    if (_record)
                        return _record.get('name');
                    else
                        return _value;

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
                    xtype: 'combo',
                    flex: 1,
                    itemId: 'authTypeCombo',
                    typeAhead: true,
                    triggerAction: 'all',
                    queryMode: 'local',
                    editable: false,
                    store: {
                        type: 'KAuthManager.authtypes',
                        storeId: 'KAuthManager.authtypes'
                    },
                    displayField: 'bean',
                    valueField: 'id'
                },{
                    xtype: 'textfield',
                    itemId: 'objectFilter',
                    flex: 1,
                    enableKeyEvents: true,
                    emptyText: 'filter objects'
                },{
                    xtype: 'button',
                    itemId: 'authObjectActivateButton',
                    icon: 'modules/KOrgObjects/images/bulb_on.png',
                    tooltip: 'toggle Activation Status of the selected object'
                },{
                    xtype: 'button',
                    itemId: 'authObjectAddButton',
                    icon: 'modules/KOrgObjects/images/add.png',
                    tooltip: 'add an Object'
                }, {
                    xtype: 'button',
                    itemId: 'authObjectDeleteButton',
                    disabled: true,
                    icon: 'modules/KOrgObjects/images/delete.png',
                    tooltip: 'Delete the selected Object'
                }
            ]
        }, {
            xtype: 'pagingtoolbar',
            store: 'KAuthManager.authobjects',
            dock: 'bottom',
            displayInfo: true
        }
    ]
});



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthObjectsFieldControls', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthObjectsFieldControls',
    title: 'Field Controls',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectFieldControlsController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthObjectFieldControlsController',
    layout: 'fit',
    style: {
        'background-color': 'transparent',
    },
    selModel: 'rowmodel',
    plugins: {
        ptype: 'cellediting',
        clicksToEdit: 2
    },
    viewConfig: {
        markDirty: false
    },
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectfieldcontrols', {storeId: 'KAuthManager.authobjectsfieldcontrols'}),
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true
        },
        items: [
            {
                text: 'field',
                dataIndex: 'field',
                flex: 1
            }, {
                text: 'control',
                dataIndex: 'control',
                flex: 1,
                editor: {
                    xtype: 'combo',
                    typeAhead: true,
                    triggerAction: 'all',
                    selectOnTab: true,
                    store: Ext.create('Ext.data.Store', {
                            fields: ['value', 'name'],
                            data: [[0, 'none'], [1, 'hide'], [2, 'display'], [3, 'edit']],
                            storeId: 'fieldControlStore'
                        }
                    ),
                    displayField: 'name',
                    valueField: 'value',
                    lazyRender: true
                },
                renderer: function (_value) {
                    var _store = Ext.data.StoreManager.lookup('fieldControlStore'),
                        _record = _store.findRecord('value', _value);

                    if (_record)
                        return _record.get('name');
                    else
                        return _value;

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
                    itemId: 'authObjectFieldControlAddButton',
                    icon: 'modules/KOrgObjects/images/add.png',
                    text: 'Add'
                }, {
                    xtype: 'button',
                    itemId: 'authObjectFieldControlDeleteButton',
                    icon: 'modules/KOrgObjects/images/delete.png',
                    text: 'delete'
                }
            ]
        }
    ]
});



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthObjectsFieldValues', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthObjectsFieldValues',
    title: 'Field Values',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectFieldValuesController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthObjectFieldValuesController',
    layout: 'fit',
    style: {
        'background-color': 'transparent',
    },
    viewConfig: {
        markDirty: false
    },
    selModel: 'rowmodel',
    plugins: {
        ptype: 'cellediting',
        clicksToEdit: 2
    },
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectfieldvalues', {storeId: 'KAuthManager.authobjectsfieldvalues'}),
    columns: [
        {
            text: 'name',
            dataIndex: 'name',
            flex: 1,
            menuDisabled: true,
            sortable: false
        }, {
            text: 'operator',
            dataIndex: 'operator',
            flex: 1,
            editor: {
                xtype: 'combo',
                typeAhead: true,
                triggerAction: 'all',
                selectOnTab: true,
                store: Ext.create('Ext.data.Store', {
                        fields: ['value', 'name'],
                        data: [
                            {
                                value: '',
                                name: 'ignore'
                            }, {
                                value: 'EQ',
                                name: '='
                            }, {
                                value: 'NE',
                                name: '≠'
                            }, {
                                value: 'IN',
                                name: 'in'
                            }, {
                                value: 'NI',
                                name: 'not in'
                            }, {
                                value: 'GT',
                                name: '>'
                            }, {
                                value: 'GTE',
                                name: '≥'
                            }, {
                                value: 'LT',
                                name: '<'
                            }, {
                                value: 'LTE',
                                name: '≤'
                            }, {
                                value: 'LK',
                                name: 'like'
                            }
                        ],
                        storeId: 'operatorTypesStore'
                    }
                ),
                displayField: 'name',
                valueField: 'value',
                lazyRender: true
            },
            renderer: function (_value) {
                var _store = Ext.data.StoreManager.lookup('operatorTypesStore'),
                    _record = _store.findRecord('value', _value);

                if (_record)
                    return _record.get('name');
                else
                    return _value;

            },
            menuDisabled: true,
            sortable: false
        }, {
            text: 'value1',
            dataIndex: 'value1',
            flex: 1,
            menuDisabled: true,
            sortable: false
        }, {
            text: 'value2',
            dataIndex: 'value2',
            flex: 1,
            menuDisabled: true,
            sortable: false
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'button',
                    text: 'SQL'
                }
            ]
        }
    ]
});



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthObjectsOrgValues', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthObjectsOrgValues',
    title: 'Org Values',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectOrgValuesController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthObjectOrgValuesController',
    layout: 'fit',
    style: {
        'background-color': 'transparent',
    },
    viewConfig: {
        markDirty: false
    },
    selModel: 'rowmodel',
    plugins: {
        ptype: 'cellediting',
        clicksToEdit: 2
    },
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectorgvalues', {storeId: 'KAuthManager.authobjectsorgvalues'}),
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true,
            flex: 1
        },
        items: [
            {
                text: 'name',
                dataIndex: 'name'
            }, {
                text: 'value',
                dataIndex: 'displayvalue',
                editor:{
                    xtype: 'textfield'
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
                    xtype: 'combo',
                    fieldLabel: 'org assignment',
                    itemId: 'orgAssignment',
                    value: '0',
                    typeAhead: true,
                    triggerAction: 'all',
                    selectOnTab: true,
                    store: Ext.create('Ext.data.Store', {
                            fields: ['value', 'name'],
                            data: [
                                {
                                    value: '0',
                                    name: 'own'
                                },
                                {
                                    value: '1',
                                    name: 'all'
                                },
                                {
                                    value: '2',
                                    name: 'relate'
                                },
                                {
                                    value: '3',
                                    name: 'ignore'
                                }
                            ]
                        }
                    ),
                    displayField: 'name',
                    valueField: 'value',
                    lazyRender: true
                }, {
                    xtype: 'checkbox',
                    itemId: 'assignedUser',
                    fieldLabel: 'assigned user'
                }
            ]
        }
    ]
});



Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.view.AuthProfileObjects', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.KAuthManager.AuthProfileObjects',
    title: 'linked Objects',
    requires: [
        'SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthProfileObjectsController'
    ],
    height: '100%',
    width: '100%',
    border: false,
    controller: 'KAuthManager.AuthProfileObjectsController',
    layout: 'fit',
    style: {
        'background-color': 'transparent',
    },
    selModel: 'rowmodel',
    store: Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.store.authprofileobjects', {storeId: 'KAuthManager.authprofileobjects'}),
    columns: {
        defaults: {
            sortable: false,
            menuDisabled: true
        },
        items: [
            {
                text: 'type',
                dataIndex: 'bean',
                flex: 2
            }, {
                text: 'name',
                dataIndex: 'name',
                flex: 2
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
                    itemId: 'authProfilObjectAddButton',
                    icon: 'modules/KOrgObjects/images/add.png',
                    tooltip: 'add an Object',
                    disabled: true
                }, {
                    xtype: 'button',
                    itemId: 'authProfileObjectDeleteButton',
                    icon: 'modules/KOrgObjects/images/delete.png',
                    tooltip: 'Delete the selected Object',
                    disabled: true
                }
            ]
        }
    ]
});



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



Ext.enableAriaButtons = false;

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.Application', {
    namespaces: ['SpiceCRM.KAuthManager.AuthProfileManager'],
    controllers: ['Application'],
    extend: 'Ext.app.Application',
    name: 'SpiceCRM.KAuthManager.AuthProfileManager',
    thisMainView: false,
    launch: function () {
        SpiceCRM.KAuthManager.AuthProfileManager.Application = this;

        if (this.thisMainView) this.destroyMainView();
        this.render();

    },
    destroyMainView: function () {
        this.thisMainView.destroy();
        this.thisMainView = false;
    },
    render: function () {
        this.thisMainView = Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.view.main.Main');

        Ext.get(window).on({
            resize: function () {
                if (SpiceCRM.KAuthManager.AuthProfileManager.Application.thisMainView)
                    SpiceCRM.KAuthManager.AuthProfileManager.Application.thisMainView.updateLayout();
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
    extend: 'SpiceCRM.KAuthManager.AuthProfileManager.Application'
});