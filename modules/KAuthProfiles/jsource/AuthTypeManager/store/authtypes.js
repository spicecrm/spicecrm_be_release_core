Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.authtypes', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.authtype'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.authtype',
    alias: 'store.KAuthManager.authtypes',
    autoLoad: true,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypes',
        appendId: true,
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});
