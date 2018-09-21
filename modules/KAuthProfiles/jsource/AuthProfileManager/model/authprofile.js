Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authprofile', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name', 'status', 'usagecount'],
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authprofiles',
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});