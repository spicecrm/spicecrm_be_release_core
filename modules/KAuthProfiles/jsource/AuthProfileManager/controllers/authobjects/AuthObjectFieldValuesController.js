Ext.define('SpiceCRM.KAuthManager.AuthProfileManager.controller.AuthObjectFieldValuesController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.KAuthManager.AuthObjectFieldValuesController',
    config: {
        listen: {
            global: {
                AuthProfileManagerElementAuthFieldData: function(_fieldData){
                    this.view.getStore().removeAll();
                    if(_fieldData){
                        Ext.each(_fieldData, function(_field){
                            this.view.getStore().add(_field);
                        }, this);
                    }
                },
                AuthProfileManagerObjectDataRetrieved: function(_data){
                    this.view.store.each(function(_record){
                        _record.set({operator: '', value1: '', value2: ''});
                    }, this);

                    if(_data.fieldvalues){
                        Ext.each(_data.fieldvalues, function(_fieldvalue){
                            var _valRecord = this.view.getStore().getById(_fieldvalue.kauthtypefield_id);
                            if(_valRecord) _valRecord.set({
                                'operator': _fieldvalue.operator,
                                'value1': _fieldvalue.value1,
                                'value2': _fieldvalue.value2
                            });
                        }, this);
                    }
                }
            }
        },
        control: {

        }
    }
});


