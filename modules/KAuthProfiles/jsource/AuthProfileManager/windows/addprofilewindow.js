Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.window.addprofilewindow', {
    extend: 'Ext.window.Window',
    title: 'Add Profile',
    layout: 'fit',
    width: 500,
    height: 700,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    currentUserId: undefined,
    items: [
        {
            xtype: 'grid',
            store: {
                type: 'KAuthManager.authprofiles',
                storeId: 'KAuthManager.authprofilesadd',
                autoLoad: false
            },
            selModel: {
                type: 'rowmodel',
                mode: 'multi'
            },
            columns: [
                {
                    text: 'name',
                    dataIndex: 'name',
                    flex: 1,
                    sortable: true
                }, {
                    text: '<img src="modules/KAuthProfiles/images/bulb_on.png"/>',
                    dataIndex: 'status',
                    width: 40,
                    align: 'center',
                    renderer: function (_value) {
                        if (_value === 'r')
                            return '<img src="modules/KAuthProfiles/images/bulb_on.png"/>';
                        else
                            return '<img src="modules/KAuthProfiles/images/bulb_off.png"/>';
                    }
                }
            ],
            dockedItems: [{
                xtype: 'pagingtoolbar',
                store: 'KAuthManager.authprofilesadd',
                dock: 'bottom',
                displayInfo: true
            }
            ]
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'textfield',
                    flex: 1,
                    itemId: 'authProfileFilter',
                    enableKeyEvents: true,
                    listeners: {
                        keyup: function (_field, _e) {
                            if (_e.getKey() == _e.ENTER) {
                                var _grid = _field.up('window').down('grid');
                                _grid.getStore().getProxy().extraParams.searchterm = _field.getValue();
                                // _grid.getStore().load();
                                _field.up('window').down('pagingtoolbar').moveFirst();
                            }
                        }
                    }
                }/*, {
                 xtype: 'checkbox',
                 itemId: 'authProfileActiveOnlyFilter',
                 fieldLabel: 'active only',
                 listeners: {
                 change: function (_field, _newValue) {
                 var _grid = _field.up('window').down('grid');
                 _grid.getStore().getProxy().extraParams.activeOnly = _newValue;
                 // _grid.getStore().load();
                 _field.up('window').down('pagingtoolbar').moveFirst();
                 }
                 }
                 }*/
            ]
        }, {
            xtype: 'toolbar',
            dock: 'bottom',
            items: [
                {
                    xtype: 'displayfield',
                    itemId: 'messagecontainer'
                },
                '->',
                {
                    text: 'Cancel',
                    handler: function () {
                        this.up('.window').close();
                    }
                }, {
                    text: 'Add',
                    handler: function (_button) {
                        var _grid = _button.up('window').down('grid'),
                            _selection = _grid.getSelection();
                        if (_selection.length > 0) {
                            Ext.each(_selection, function (_thisSelection) {
                                var newModel = {
                                    id: _thisSelection.get('id'),
                                    name: _thisSelection.get('name'),
                                    status: _thisSelection.get('status')
                                };
                                Ext.Ajax.request({
                                    url: 'KREST/kauthprofiles/core/authusers/' + this.up('window').currentUserId + '/authprofiles/' + _thisSelection.get('id'),
                                    method: 'POST',
                                    success: function (response, opts) {
                                        Ext.data.StoreManager.lookup('KAuthManager.authassignmentprofiles').add(newModel);

                                    },
                                    failure: function (response, opts) {

                                    },
                                    scope: this
                                });
                            }, this);
                            this.up('.window').close();
                        } else {
                            _button.up('window').down('#messagecontainer').setValue('no Profile selected');
                        }
                    }
                }
            ]
        }
    ],
    listeners: {
        show: function () {
            var _store = this.down('grid').getStore();
            _store.removeAll();
            _store.getProxy().extraParams.authuserid = this.currentUserId;
            _store.load();
        }
    }
})
;
