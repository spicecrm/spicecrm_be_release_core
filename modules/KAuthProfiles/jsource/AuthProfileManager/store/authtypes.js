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
