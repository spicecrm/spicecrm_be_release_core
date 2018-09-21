Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.authtypeactions', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.authtypeaction'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.authtypeaction',
    alias: 'store.KAuthManager.authtypeactions',
    autoLoad: true,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypeactions',
        appendId: true,
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});
