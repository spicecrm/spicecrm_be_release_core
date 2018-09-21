Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.window.addobjectwindow', {
    extend: 'Ext.window.Window',
    title: 'Add Object',
    layout: 'fit',
    width: 500,
    height: 700,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    currentRecordId: undefined,
    currentBean: undefined,
    items: [
        {
            xtype: 'grid',
            itemId: 'objectGrid',
            store: {
                type: 'KAuthManager.authobjects',
                storeId: 'KAuthManager.authprofileobjectsadd',
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
                store: 'KAuthManager.authprofileobjectsadd',
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
                    xtype: 'combo',
                    flex: 1,
                    itemId: 'authTypeCombo',
                    typeAhead: true,
                    triggerAction: 'all',
                    queryMode: 'local',
                    editable: false,
                    store: {
                        type: 'KAuthManager.authtypes'
                    },
                    displayField: 'bean',
                    valueField: 'id',
                    listeners: {
                        select: function (_combo, _record) {
                            var _grid = _combo.up('window').down('grid');
                            _grid.getStore().removeAll();
                            _grid.getStore().getProxy().extraParams.kauthtypeid = _record.id;
                            _grid.getStore().load();

                            _combo.up('window').currentBean = _record.get('bean');
                        }
                    }
                }, {
                    xtype: 'textfield',
                    flex: 1,
                    itemId: 'authObjectFilter',
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
                }
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
                                    status: _thisSelection.get('status'),
                                    bean: this.up('window').currentBean
                                };
                                Ext.Ajax.request({
                                    url: 'KREST/kauthprofiles/core/authprofiles/' + this.up('window').currentRecordId + '/authobjects/' + _thisSelection.get('id'),
                                    method: 'POST',
                                    success: function (response, opts) {
                                        Ext.data.StoreManager.lookup('KAuthManager.authprofileobjects').add(newModel);

                                    },
                                    failure: function (response, opts) {

                                    },
                                    scope: this
                                });
                            }, this);
                            this.up('.window').close();
                        } else {
                            _button.up('window').down('#messagecontainer').setValue('no Object selected');
                        }
                    }
                }
            ]
        }
    ],
    listeners: {
        show: function () {
            var _store = this.down('#objectGrid').getStore();
            _store.removeAll();
            _store.getProxy().extraParams.authprofileid = this.currentRecordId;
        }
    }
})
;
