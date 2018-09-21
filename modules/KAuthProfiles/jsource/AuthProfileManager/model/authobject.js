Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.model.authobject', {
    extend: 'Ext.data.Model',
    fields: ['id', 'name', 'status', 'customSQL', 'description', 'usagecount', 'kauthobjecttype', 'activity'],
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects',
        reader: {
            type: 'json'
        },
        writer: {
            type: 'json'
        }
    }
});