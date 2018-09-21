Ext.define('SpiceCRM.KAuthManager.AuthTypeManager.window.addfieldwindow', {
    extend: 'Ext.window.Window',
    title: 'Add Field',
    layout: 'fit',
    width: 500,
    height: 300,
    closeAction: 'close',
    plain: true,
    draggable: true,
    modal: true,
    currentRecord: undefined,
    items: [
        {
            xtype: 'grid',
            itemId: 'fieldGrid',
            store: {
                type: 'KAuthManager.fields'
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
                            id: SpiceCRM.KAuthManager.AuthTypeManager.Application.kGuid(),
                            kauthtype_id: this.up('window').currentRecord.id,
                            name: _selection[0].get('name')
                        };
                        Ext.Ajax.request({
                            url: 'KREST/kauthprofiles/core/authtypefields',
                            method: 'POST',
                            jsonData: Ext.encode(newModel),
                            success: function (response, opts) {
                                Ext.data.StoreManager.lookup('KAuthManager.authtypefields').add(newModel);
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
            _store.getProxy().extraParams.authtypemodule = this.currentRecord.get('bean');
            _store.getProxy().extraParams.authtypeid = this.currentRecord.get('id');
            _store.load();
        }
    }
})
;
