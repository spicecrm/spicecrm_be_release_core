Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.window.addfieldwindow', {
    extend: 'Ext.window.Window',
    title: 'Add Field',
    layout: 'fit',
    width: 500,
    height: 300,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    currentRecordId: undefined,
    items: [
        {
            xtype: 'grid',
            itemId: 'fieldGrid',
            store: {
                type: 'AuthProfileManager.fields'
            },
            columns: [
                {
                    text: 'name',
                    dataIndex: 'name',
                    flex: 1,
                    sortable: true
                }
            ]
        }
    ],
    dockedItems: [{
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
                        var newModel = {
                            kauthobject_id: this.up('window').currentRecordId,
                            field: _selection[0].get('name'),
                            control: '0'
                        };
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authobjects/fieldcontrol',
                            method: 'POST',
                            jsonData: Ext.encode(newModel),
                            success: function (response, opts) {
                                Ext.data.StoreManager.lookup('KAuthManager.authobjectsfieldcontrols').add(newModel);
                                this.up('.window').close();
                            },
                            failure: function (response, opts) {

                            },
                            scope: this
                        });
                    } else {
                        _button.up('window').down('#messagecontainer').setValue('no field selected');
                    }
                }
            }
        ]
    }
    ],
    listeners: {
        show: function () {
            var _store = this.down('#fieldGrid').getStore();
            _store.removeAll();
            _store.getProxy().extraParams.authobjectid = this.currentRecordId;
            _store.load();
        }
    }
})
;
