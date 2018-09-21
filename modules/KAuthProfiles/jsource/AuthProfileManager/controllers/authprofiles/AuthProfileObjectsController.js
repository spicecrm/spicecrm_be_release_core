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


