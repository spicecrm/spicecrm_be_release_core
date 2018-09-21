function populateModuleCreator() {

    $( "#modulename" ).keyup(function() {
        //set table name
        $( "#tablename" ).val($('#modulename').val().toLowerCase());
        //set beanname
        $( "#beanname" ).val($('#modulename').val().substring(0, ($('#modulename').val().length-1)));
    });
}
function requiredFieldsForModuleCreator(){
    addToValidate('EditView','modulepath','varchar',true,'missing module path');
    addToValidate('EditView','modulename','varchar',true,'missing module name');
    addToValidate('EditView','tablename','varchar',true,'missing table name');
    addToValidate('EditView','beanname','varchar',true,'missing bean name');
}

window.onload = function() {
    populateModuleCreator();
    requiredFieldsForModuleCreator();
}