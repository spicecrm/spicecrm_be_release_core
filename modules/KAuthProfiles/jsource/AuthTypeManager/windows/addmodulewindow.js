Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.window.addmodulewindow', {
    extend: 'Ext.window.Window',
    title: 'Add Module',
    layout: 'fit',
    width: 500,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    items: [
        {
            xtype: 'fieldset',
            defaults: {
                width: '100%'
            },
            items: [
                {
                    xtype: 'combo',
                    fieldLabel: 'module',
                    itemId: 'moduleCombo',
                    typeAhead: true,
                    triggerAction: 'all',
                    queryMode: 'local',
                    editable: true,
                    store: {
                        type: 'KAuthManager.modules'
                    },
                    displayField: 'name',
                    valueField: 'name'
                }
            ]
        }
    ],
    buttons: [
        {
            text: 'Cancel',
            handler: function () {
                this.up('.window').close();
            }
        }, {
            text: 'Add',
            handler: function (_button) {
                var newModel = {
                    id: SpiceCRM.KAuthManager.AuthTypeManager.Application.kGuid(),
                    bean: _button.up('window').down('#moduleCombo').getValue()
                };
                Ext.Ajax.request({
                    url: 'KREST/kauthprofiles/core/authtypes',
                    method: 'POST',
                    jsonData: Ext.encode(newModel),
                    success: function(response, opts) {
                        Ext.data.StoreManager.lookup('KAuthManager.authtypes').add(newModel);
                        this.up('.window').close();
                    },
                    failure: function(response, opts) {

                    },
                    scope: this
                });
            }
        }
    ],
    listeners: {
        show: function () {

        }
    }
})
;
