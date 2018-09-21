Ext.enableAriaButtons = false;

Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.Application', {
    namespaces: ['SpiceCRM.KAuthManager.AuthTypeManager'],
    controllers: ['Application'],
    extend: 'Ext.app.Application',
    name: 'SpiceCRM.KAuthManager.AuthTypeManager',
    thisMainView: false,
    launch: function () {
        SpiceCRM.KAuthManager.AuthTypeManager.Application = this;

        if (this.thisMainView) this.destroyMainView();
        this.render();

    },
    destroyMainView: function () {
        this.thisMainView.destroy();
        this.thisMainView = false;
    },
    render: function () {
        // Ext.create('SpiceCRM.KOrgManager.CoreConfigurator.view.maintoolbar');
        this.thisMainView = Ext.create('SpiceCRM.KAuthManager.AuthTypeManager.view.main.Main');

        Ext.get(window).on({
            resize: function () {
                if (SpiceCRM.KAuthManager.AuthTypeManager.Application.thisMainView)
                    SpiceCRM.KAuthManager.AuthTypeManager.Application.thisMainView.updateLayout();
            }
        });
    },
    languageGetText: function (_keyID) {
        return SUGAR.language.get('KAuthProfiles', _keyID);
    },
    getRand: function () {
        return Math.random();
    },
    S4: function () {
        return (((1 + this.getRand()) * 0x10000) | 0).toString(16).substring(1);
    },
    kGuid: function () {
        return ('k' + this.S4() + this.S4() + this.S4() + this.S4() + this.S4() + this.S4() + this.S4());
    }
});

Ext.application({
    extend: 'SpiceCRM.KAuthManager.AuthTypeManager.Application'
});