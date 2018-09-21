Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.authtypefields', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.authtypefield'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.authtypefield',
    alias: 'store.KAuthManager.authtypefields',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypefields',
        appendId: true,
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});
