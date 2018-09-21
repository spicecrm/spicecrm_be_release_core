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


