SpiceCRM.factory('WBSPanelService', ['$http', '$q', function (_http, _q) {
    var _wbsDataService = {
        objects:[],
        root:{},
        loading:false,
        selected:{},
        wbsStatusOptions:[
            {id:0,name:"created"},
            {id:1,name:"started"},
            {id:2,name:"complete"}
        ],
        load:function(){
            var q = _q.defer();
            _wbsDataService.loading = true;
            var bean_id = $('form[name$="View"] input[name="record"]').val();
            _wbsDataService.root = {
                name: $('span#name').text(),
                id: bean_id,
                start_date: $('span#estimated_start_date').text(),
                end_date: $('span#estimated_end_date').text(),
                status: $('#status').parent().text().trim(),
                parent_id: null
            };
            _http({
                method: 'GET',
                url: 'KREST/projectwbs/'+bean_id
            }).success(function (_response) {
                q.resolve(_response);
            });
            return q.promise;
        },
        getChildren: function(_parent){
            var bean_id = $('form[name$="View"] input[name="record"]').val();
            var _return = [];
            if(_parent === bean_id){ //for root children get all records with parent_id empty
                for (var index = 0; index < _wbsDataService.objects.length; ++index) {
                    if(_wbsDataService.objects[index].parent_id === null || _wbsDataService.objects[index].parent_id.length === 0){
                        _return.push(_wbsDataService.objects[index]);
                    }
                }
            }else{
                for (var index = 0; index < _wbsDataService.objects.length; ++index) {
                    if(_wbsDataService.objects[index].parent_id === _parent ){
                        _return.push(_wbsDataService.objects[index]);
                    }
                }
            }
            return _return;
        },
        saveForm: function(){
            _http({
                method: 'POST',
                url: 'KREST/projectwbs',
                data: {
                    id:_wbsDataService.selected.id,
                    name:_wbsDataService.selected.form_name,
                    status:_wbsDataService.selected.ng_status.id,
                    start_date:_wbsDataService.selected.form_start_date.toISOString(),
                    end_date:_wbsDataService.selected.form_end_date.toISOString()
                }
            }).success(function (_response) {
                for (var index = 0; index < _wbsDataService.objects.length; ++index) {
                    if(_wbsDataService.objects[index].id === _wbsDataService.selected.id ){
                        _wbsDataService.objects[index] = _wbsDataService.selected;
                        _wbsDataService.objects[index].status = _wbsDataService.selected.ng_status.name;
                        _wbsDataService.objects[index].name = _wbsDataService.selected.form_name;
                        _wbsDataService.objects[index].start_date = _wbsDataService.objects[index].form_start_date.toLocaleDateString();
                        _wbsDataService.objects[index].end_date = _wbsDataService.objects[index].form_end_date.toLocaleDateString();
                    }
                }
            });
        },
        saveNew: function (_name) {
            var q = _q.defer();
            var bean_id = $('form[name$="View"] input[name="record"]').val();
            var _parent = "";
            if(_wbsDataService.selected.id !== undefined){
                _parent = _wbsDataService.selected.id;
            }
            _http({
                method: 'POST',
                url: 'KREST/projectwbs',
                data: {
                    name: _name,
                    project_id: bean_id,
                    parent_id: _parent
                }
            }).success(function (_response) {
                q.resolve(_response);
            });
            return q.promise;
        },
        deleteItem: function(){
            _http({
                method: 'DELETE',
                url: 'KREST/projectwbs/' + _wbsDataService.selected.id
            }).success(function (_response) {
                _wbsDataService.deleteRecursive(_wbsDataService.selected.id);
                _wbsDataService.selected = {};
            });
        },
        deleteRecursive: function(_id){
            for (var index = 0; index < _wbsDataService.objects.length; ++index) {
                if(_wbsDataService.objects[index].id == _id){
                    _wbsDataService.objects.splice(index,1);
                }
                if(_wbsDataService.objects[index].parent_id == _id){
                    _wbsDataService.deleteRecursive(_id);
                }
            }
        }
    };
    var res = _wbsDataService.load();
    res.then(function (_result) {
        _wbsDataService.objects = _result;
        _wbsDataService.loading = false;
    });
    return _wbsDataService;
}]);

SpiceCRM.controller('WBSPanelCtrl', ['$scope','WBSPanelService', function (_scope, _service) {
    angular.extend(_scope, {
        WBSPanel:{
            getChildren: function(_parent){
                return _service.getChildren(_parent);
            },
            showChildren: function(_id){
                $('.'+_id+'children').show();
                $('#'+_id+'basic').show();
                $('#'+_id+'advanced').hide();
            },
            hideChildren: function(_id){
                $('.'+_id+'children').hide();
                $('#'+_id+'basic').hide();
                $('#'+_id+'advanced').show();
            },
            hasChildren: function(_id){
                var _res = _service.getChildren(_id);
                if(_res !== undefined && _res.length > 0){
                    return true;
                }else{
                    return false;
                }
            },
            itemSelected: function(_id){
                _scope.WBSPanel.cancelForm();
                for (var index = 0; index < _service.objects.length; ++index) {
                    $('#'+_service.objects[index].id).removeClass('wbsselected');
                    if(_service.objects[index].id === _id){
                        _service.selected = _service.objects[index];
                        _service.selected.form_name = _service.selected.name;
                        if(typeof _service.selected.form_start_date !== 'Date'){
                            _service.selected.form_start_date = new Date(Date.parse(_service.selected.form_start_date));
                        }
                        if(typeof _service.selected.form_end_date !== 'Date') {
                            _service.selected.form_end_date = new Date(Date.parse(_service.selected.form_end_date));
                        }
                        $('#'+_id).addClass('wbsselected');
                    }
                }
            },
            saveForm: function(){
                if(
                    $('input[name="start_date"]')[0].nodeName === "INPUT" && $('input[name="start_date"]')[0].type === $('input[name="start_date"]')[0].getAttribute("type")
                    && $('input[name="end_date"]')[0].nodeName === "INPUT" && $('input[name="end_date"]')[0].type === $('input[name="end_date"]')[0].getAttribute("type")
                ) {
                    $('input[name="end_date"]').attr('min',$('input[name="start_date"]').val());
                    $('input[name="start_date"]')[0].checkValidity();
                    $('input[name="end_date"]')[0].checkValidity()
                    if($('input[name="start_date"]')[0].validity.valid && $('input[name="end_date"]')[0].validity.valid){
                        $('input[name="start_date"]').removeClass('error');
                        $('input[name="end_date"]').removeClass('error');
                        _service.saveForm();
                    }else{
                        if(!$('input[name="start_date"]')[0].validity.valid){
                            $('input[name="start_date"]').addClass('error');
                        }
                        if(!$('input[name="end_date"]')[0].validity.valid){
                            $('input[name="end_date"]').addClass('error');
                        }
                    }
                }else{
                    _service.saveForm();
                }
            },
            cancelForm: function(){
                _service.selected = {};
                $('.wbsselected').removeClass('wbsselected');
                $('input[name="start_date"]').removeClass('error');
                $('input[name="end_date"]').removeClass('error');
            },
            saveNew: function(){
                var _name = $('#newWbsName').val();
                var save = _service.saveNew(_name);
                save.then(function (_result) {
                    _service.objects.push(_result);
                    //_scope.$apply();
                });
                $('#newWbsName').val('');
            },
            deleteItem: function(){
                _service.deleteItem();
                _scope.WBSPanel.cancelForm();
            }
        },
        wbsService: _service
    });
}]);

SpiceCRM.directive('wbsItem', [
    function () {
        return {
            restrict: 'E',
            templateUrl: 'modules/ProjectWBSs/tpls/wbsitem.html',
            replace: true,
            controller: 'WBSPanelCtrl',
            scope:{
                itemData:'='
            }
        };
    }
]);