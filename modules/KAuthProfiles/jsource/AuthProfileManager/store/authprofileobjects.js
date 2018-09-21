Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authprofileobjects', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authprofileobject'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authprofileobject',
    alias: 'store.KAuthManager.authprofileobjects',
    autoLoad: false,
    proxy: {
        type: 'memory'
    }
});
