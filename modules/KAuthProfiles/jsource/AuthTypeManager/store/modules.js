Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.store.modules', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthTypeManager.model.module'],
    model: 'SpiceCRM.KAuthManager.AuthTypeManager.model.module',
    alias: 'store.KAuthManager.modules',
    autoLoad: true,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authtypes/modules',
        appendId: true,
        reader: {
            type: 'json'
        }
    }
});
