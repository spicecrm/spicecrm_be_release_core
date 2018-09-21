Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authassignmentusers', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authassignmentuser'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authassignmentuser',
    alias: 'store.KAuthManager.authassignmentuser',
    autoLoad: true,
    pageSize: 25,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authusers',
        reader: {
            type: 'json',
            rootProperty: 'records',
            totalProperty: 'totalcount'
        }
    }
});
