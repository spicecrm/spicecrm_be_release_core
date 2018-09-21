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


