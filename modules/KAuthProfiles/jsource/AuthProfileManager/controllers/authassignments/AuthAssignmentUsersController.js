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


