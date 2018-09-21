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
