Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.fields', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.field'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.field',
    alias: 'store.AuthProfileManager.fields',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects/fieldcontrol/fields',
        appendId: true,
        reader: {
            type: 'json'
        }
    }
});
