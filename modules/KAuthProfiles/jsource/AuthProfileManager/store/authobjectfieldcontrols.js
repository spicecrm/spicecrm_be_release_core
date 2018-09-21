Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.store.authobjectfieldcontrols', {
    extend: 'Ext.data.Store',
    requires: ['SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectfieldcontrol'],
    model: 'SpiceCRM.KAuthManager.AuthProfileManager.model.authobjectfieldcontrol',
    alias: 'store.KAuthManager.authobjectfieldcontrols',
    autoLoad: false,
    proxy: {
        type: 'rest',
        url: 'KREST/kauthprofiles/core/authobjects/fieldcontrols',
        reader: {
            type: 'json'
        }
    }
});
