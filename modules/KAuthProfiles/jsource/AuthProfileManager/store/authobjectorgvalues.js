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
