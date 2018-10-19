/* * *******************************************************************************
* This file is part of SpiceCRM FulltextSearch. SpiceCRM FulltextSearch is an enhancement developed
* by aac services k.s.. All rights are (c) 2016 by aac services k.s.
*
* This Version of the SpiceCRM FulltextSearch is licensed software and may only be used in
* alignment with the License Agreement received with this Software.
* This Software is copyrighted and may not be further distributed without
* witten consent of aac services k.s.
*
* You can contact us at info@spicecrm.io
******************************************************************************* */

Ext.define("SpiceCRM.KReporter.Viewer.model.reportResult",{extend:"Ext.data.Model",fields:["id"]}),Ext.define("SpiceCRM.KReporter.Viewer.store.reportResults",{extend:"Ext.data.Store",requires:["SpiceCRM.KReporter.Viewer.model.reportResult"],model:"SpiceCRM.KReporter.Viewer.model.reportResult",alias:["store.KReportViewer.plugins.reportResults"],reportId:void 0,filterId:void 0,remoteSort:void 0,dynamicoptions:void 0,blockDynamicoptions:0,linkedFields:!1,proxy:{type:"ajax",url:"KREST/KReporter/"+this.reportId+"/presentation",actionMethods:{read:"POST"},paramsAsJson:!0,timeout:12e4,reader:{type:"json",rootProperty:"records",totalProperty:"count"}},listeners:{beforeload:function(e,t,r){t._proxy.url="KREST/KReporter/"+this.reportId+"/presentation",this.filterId&&(t._proxy.extraParams={filter:this.filterId}),t._proxy=SpiceCRM.KReporter.Common.sendParentBeanParams(t._proxy,this.reportId),_url=SpiceCRM.KReporter.Common.buildDynamicOptionsUrl(this.reportId,"presentation"),null!==_url&&(t._proxy.url=_url,t._proxy.extraParams.dynamicoptions=SpiceCRM.KReporter.Common.catchDynamicOptionsFromSession(this.reportId),t._proxy.extraParams.blockDynamicoptions=e.blockDynamicoptions,t._proxy.extraParams.dynamicoptionsfromurl=SpiceCRM.KReporter.Common.catchDynamicOptionsFromUrl())},load:function(e){this.proxy.reader.metaData.gridColumns&&0===this.panel.getColumns().length&&this.panel.reconfigure(this.proxy.reader.metaData.gridColumns)}},buildLinkedFields:function(){this.linkedFields={};for(var e=this.proxy.reader.metaData.fields,t=0;t<e.length;t++)void 0===e[t].linkInfo?this.linkedFields[e[t].name]=null:this.linkedFields[e[t].name]=Ext.JSON.decode(e[t].linkInfo)}}),Ext.define("SpiceCRM.KReporter.Viewer.controller.plugins.StandardViewerController",{extend:"Ext.app.ViewController",alias:"controller.KReportViewer.plugins.StandardViewerController",whereConfig:{},config:{listen:{global:{whereClauseUpdated:function(e,t){this.view.getStore().blockDynamicoptions=1,this.reloadStore(e,t)},snapshotSelected:function(e){var t=this.view.getStore();t.getProxy().extraParams.snapshotid=e,t.removeAll(),t.load()}}}},showContexMenu:function(e,t,r,o,i,n){this.view.up("panel").controller.displayContextMenu(t,i)},reloadStore:function(e,t){var r=this.view.getStore();r.getProxy().extraParams.whereConditions=Ext.encode(e),r.getProxy().extraParams.sort=Ext.encode(t),r.removeAll(),r.load()},renderField:function(e,t,r,o,i,n,a){return t&&t.column&&t.column.fieldrenderer?Ext.util.Format[t.column.fieldrenderer](e,t,r,o,i,n,a):e}}),Ext.define("SpiceCRM.KReporter.Viewer.plugins.StandardViewPanel",{extend:"Ext.grid.Panel",controller:"KReportViewer.plugins.StandardViewerController",itemId:"KReporterViewerPresentation",store:{type:"KReportViewer.plugins.reportResults"},selType:"rowmodel",columns:[],monitorResize:!0,autoHeight:!0,minHeight:400,boxMinHeight:250,context:"Viewer",reportRecord:void 0,remoteSort:!0,viewConfig:{enableTextSelection:!0},initComponent:function(){_accesslevel=SpiceCRM.KReporter.Common.getAccessLevel(),_saveLayoutLevel=SpiceCRM.KReporter.Common.getSaveLayoutLevel(),this.callParent(),"Viewer"===this.context&&this.down("pagingtoolbar").add({text:languageGetText("LBL_SAVE_LAYOUT_BUTTON_LABEL"),disabled:!(_accesslevel>=_saveLayoutLevel),handler:function(){var t=[],r=1;Ext.each(this.up("grid").getColumns(),function(e){t.push({dataIndex:e.dataIndex,width:e.width,sequence:r,isHidden:e.hidden}),r++}),Ext.Ajax.request({url:"KREST/KReporter/core/savelayout",method:"POST",jsonData:{record:this.up("grid").reportRecord.get("id"),layout:Ext.encode(t)},success:function(e,t){},failure:function(e,t){console.log("server-side failure with status code "+e.status)},scope:this})}}),this.down("pagingtoolbar").setStore(this.store),(this.store.panel=this).store.reportId=this.reportRecord.get("id"),this.presentationParams.pluginData&&this.presentationParams.pluginData.standardViewProperties&&this.presentationParams.pluginData.standardViewProperties.listEntries&&(this.store.pageSize=this.presentationParams.pluginData.standardViewProperties.listEntries),this.presentationFilter&&(this.store.filterId=this.presentationFilter),this.store.load()},buildColumns:function(e){var t=[];return Ext.each(e,function(e){t.push({text:languageGetText(e.name),readOnly:!0,dataIndex:e.fieldid,width:e.width,sortable:"-"!==e.sort})}),t},getDynamicColumns:function(){var r=[];return Ext.each(this.getColumns(),function(e,t){r.push({sequence:t+1,dataIndex:e.dataIndex,width:e.getWidth(),isHidden:e.isHidden(),sortState:e.sortState})},this),r},bbar:{xtype:"pagingtoolbar",displayInfo:!0},listeners:{itemcontextmenu:"showContexMenu",sortchange:function(e,t){_operators={},Ext.ComponentQuery.query("#KReportViewqerWherePanel")&&Ext.ComponentQuery.query("#KReportViewqerWherePanel")[0]&&(_operators=Ext.ComponentQuery.query("#KReportViewqerWherePanel")[0].controller.extractWhereClause()),Ext.globalEvents.fireEvent("whereClauseUpdated",_operators,[{direction:t.sortState,property:t.dataIndex}])}}});