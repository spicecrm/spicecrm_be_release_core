Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authprofiles', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authprofile'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authprofile',
    alias: 'store.KAuthManager.authprofiles',
    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authprofiles',
        reader: {
            type: 'json',
            rootProperty: 'records',
            totalProperty: 'totalcount'
        }
    }
});
