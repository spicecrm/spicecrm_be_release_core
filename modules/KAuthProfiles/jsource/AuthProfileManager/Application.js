Ext.enableAriaButtons = false;

Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.Application', {
    namespaces: ['SpiceCRM.KAuthManager.AuthProfileManager'],
    controllers: ['Application'],
    extend: 'Ext.app.Application',
    name: 'SpiceCRM.KAuthManager.AuthProfileManager',
    thisMainView: false,
    launch: function () {
        SpiceCRM.KAuthManager.AuthProfileManager.Application = this;

        if (this.thisMainView) this.destroyMainView();
        this.render();

    },
    destroyMainView: function () {
        this.thisMainView.destroy();
        this.thisMainView = false;
    },
    render: function () {
        this.thisMainView = Ext.create('SpiceCRM.KAuthManager.AuthProfileManager.view.main.Main');

        Ext.get(window).on({
            resize: function () {
                if (SpiceCRM.KAuthManager.AuthProfileManager.Application.thisMainView)
                    SpiceCRM.KAuthManager.AuthProfileManager.Application.thisMainView.updateLayout();
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
    extend: 'SpiceCRM.KAuthManager.AuthProfileManager.Application'
});