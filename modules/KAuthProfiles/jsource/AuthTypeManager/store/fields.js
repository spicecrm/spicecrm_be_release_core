Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.fields', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.field'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.field',
    alias: 'store.KAuthManager.fields',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypefields/fields',
        appendId: true,
        reader: {
            type: 'json'
        }
    }
});
