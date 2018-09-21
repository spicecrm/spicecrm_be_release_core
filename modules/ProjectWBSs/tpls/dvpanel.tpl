<h4>
    <a href="javascript:void(0)" class="collapseLink" onclick="collapsePanel(3);"><img id="detailpanel_3_img_hide" src="themes/SpiceTheme/images/basic_search.gif?v=JScFIBkPBKkIeEFi2VqRZg" border="0"></a>
    <a href="javascript:void(0)" class="expandLink" onclick="expandPanel(3);"><img id="detailpanel_3_img_show" src="themes/SpiceTheme/images/advanced_search.gif?v=JScFIBkPBKkIeEFi2VqRZg" border="0"></a>
    WBS Manager
</h4>
<script>
    document.getElementById('detailpanel_3').className += ' expanded';
</script>
<script type="text/javascript" src="modules/ProjectWBSs/js/ProjectWBSPanel.js"></script>
{literal}
<style>
    .divTable{
        display: table;
        width: 100%;
    }
    .divTableRow {
        display: table-row;
    }
    .divTableCell, .divTableHead {
        border-bottom: 1px solid #EEE;
        display: table-cell;
        padding: 3px 10px;
    }
    .divTableHeading {
        background-color: #EEE;
        display: table-header-group;
        font-weight: bold;
    }
    .divTableBody {
        display: table-row-group;
    }
    .wbsselected {
        background: #e5e5e5 none repeat scroll 0 0;
    }
</style>
<table id="LBL_WBS_PANEL" class="panelContainer" cellspacing="0" ng-controller="WBSPanelCtrl">
    <tr>
        <td width="50%">
            <wbs-item item-data="wbsService.root" ng-if="wbsService.objects.length > 0"></wbs-item>
            <table class="yui3-skin-sam edit view panelContainer" cellspacing="0" cellpadding="0" border="0" style="padding-bottom: 0px !important;">
                <tr>
                    <td scope="col" width="50%" valign="middle" style="text-align: right;">Name: <input type="text" name="name" id="newWbsName"></td>
                    <td width="50%" valign="middle" style="text-align: left;">
                        <div class="action_buttons">
                            <input class="button" type="button" name="Create" value="Create" ng-click="WBSPanel.saveNew()">
                            <input ng-show="wbsService.selected.id !== undefined" class="button" type="button" name="Delete" value="Delete" ng-click="WBSPanel.deleteItem()">
                        </div>
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%">
            <table class="yui3-skin-sam edit view panelContainer" cellspacing="0" cellpadding="0" border="0" style="padding-bottom: 0px !important;">
                <tr>
                    <td scope="col" width="12.5%" valign="middle">Name:</td>
                    <td width="37.5%" valign="middle"><input type="text" name="name" ng-model="wbsService.selected.form_name"></td>
                    <td scope="col" width="12.5%" valign="middle">Status:</td>
                    <td width="37.5%" valign="middle">
                        <select name="wbs_status" id="wbs_status" ng-options="option.name for option in wbsService.wbsStatusOptions track by option.id" ng-model="wbsService.selected.ng_status"></select>
                    </td>
                </tr>
                <tr>
                    <td scope="col" width="12.5%" valign="middle">Start:</td>
                    <td width="37.5%" valign="middle"><input type="date" name="start_date" ng-model="wbsService.selected.form_start_date"></td>
                    <td scope="col" width="12.5%" valign="middle">End:</td>
                    <td width="37.5%" valign="middle"><input type="date" name="end_date" ng-model="wbsService.selected.form_end_date"></td>
                </tr>
            </table>
            <div class="action_buttons" style="text-align: right;">
                <input type="hidden" name="id" ng-model="wbsService.selected.id">
                <input class="button" type="button" name="Save" value="Save" ng-click="WBSPanel.saveForm()" ng-disabled="wbsService.selected.name == undefined || wbsService.selected.name.length == 0">
                <input class="button" type="button" name="Cancel" value="Cancel" ng-click="WBSPanel.cancelForm()">
            </div>
        </td>
    </tr>
</table>
{/literal}