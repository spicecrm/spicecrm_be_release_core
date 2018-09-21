Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjects', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authobject'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authobject',
    alias: 'store.KAuthManager.authobjects',
    autoLoad: false,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects',
        reader: {
            type: 'json',
            rootProperty: 'records',
            totalProperty: 'totalcount'
        }
    }
});
