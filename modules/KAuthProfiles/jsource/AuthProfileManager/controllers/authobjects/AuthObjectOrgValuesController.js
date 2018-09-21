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


