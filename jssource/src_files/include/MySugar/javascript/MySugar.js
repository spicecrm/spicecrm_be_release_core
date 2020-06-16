/*********************************************************************************
* SugarCRM Community Edition is a customer relationship management program developed by
* SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
* 
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU Affero General Public License version 3 as published by the
* Free Software Foundation with the addition of the following permission added
* to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
* IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
* OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
* 
* This program is distributed in the hope that it will be useful, but WITHOUT
* ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
* details.
* 
* You should have received a copy of the GNU Affero General Public License along with
* this program; if not, see http://www.gnu.org/licenses or write to the Free
* Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
* 02110-1301 USA.
* 
* You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
* SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
* 
* The interactive user interfaces in modified source and object code versions
* of this program must display Appropriate Legal Notices, as required under
* Section 5 of the GNU Affero General Public License version 3.
* 
* In accordance with Section 7(b) of the GNU Affero General Public License version 3,
* these Appropriate Legal Notices must retain the display of the "Powered by
* SugarCRM" logo. If the display of the logo is not reasonably feasible for
* technical reasons, the Appropriate Legal Notices must display the words
* "Powered by SugarCRM".
********************************************************************************/



initMySugar = function(){
SUGAR.mySugar = function() {
	var originalLayout = null;
	var configureDashletId = null;
	var currentDashlet = null;
	var leftColumnInnerHTML = null;
	var leftColObj = null;
	var maxCount;
	var warningLang;

    var closeDashletsDialogTimer = null;
	
	var activeTab = activePage;
	var current_user = current_user_id;
	
	var module = moduleName;
	
	var charts = new Object();
	
	if (module == 'Dashboard'){
		cookiePageIndex = current_user + "_activeDashboardPage";
	}
	else{
		cookiePageIndex = current_user + "_activePage";
	}
	
	var homepage_dd;
	

	return {
		

		
		

		
		

        
        
		
		
				
		
		// get the current dashlet layout
		getLayout: function(asString) {
			columns = new Array();
			for(je = 0; je < 3; je++) {
			    dashlets = document.getElementById('col_'+activeTab+'_'+ je);
			    		    
				if (dashlets != null){
				    dashletIds = new Array();
				    for(wp = 0; wp < dashlets.childNodes.length; wp++) {
				      if(typeof dashlets.childNodes[wp].id != 'undefined' && dashlets.childNodes[wp].id.match(/dashlet_[\w-]*/)) {
						dashletIds.push(dashlets.childNodes[wp].id.replace(/dashlet_/,''));
				      }
				    }
					if(asString) 
						columns[je] = dashletIds.join(',');
					else 
						columns[je] = dashletIds;
				}
			}

			if(asString) return columns.join('|');
			else return columns;
		},

		// called when dashlet is picked up
		onDrag: function(e, id) {
			originalLayout = SUGAR.mySugar.getLayout(true);   	
		},
		
		// called when dashlet is dropped
		onDrop: function(e, id) {	
			newLayout = SUGAR.mySugar.getLayout(true);
		  	if(originalLayout != newLayout) { // only save if the layout has changed
				SUGAR.mySugar.saveLayout(newLayout);
				SUGAR.mySugar.sugarCharts.loadSugarCharts(); // called safely because there is a check to be sure the array exists
		  	}
		},
		
		// save the layout of the dashlet  
		saveLayout: function(order) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING_LAYOUT'));
			var success = function(data) {
				ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVED_LAYOUT'));
				window.setTimeout('ajaxStatus.hideStatus()', 2000);
			}
			
			url = 'index.php?to_pdf=1&module='+module+'&action=DynamicAction&DynamicAction=saveLayout&layout=' + order + '&selectedPage=' + activeTab;
			var cObj = YAHOO.util.Connect.asyncRequest('GET', url, {success: success, failure: success});					  
		},


		uncoverPage: function(id) {
			if (!SUGAR.isIE){	
				document.getElementById('dlg_c').style.display = 'none';	
			}		
			configureDlg.hide();
            if ( document.getElementById('dashletType') == null ) {
                dashletType = '';
            } else {
                dashletType = document.getElementById('dashletType').value;
            }
			SUGAR.mySugar.retrieveDashlet(SUGAR.mySugar.configureDashletId, dashletType);
		},
		
		// call to configure a Dashlet
		configureDashlet: function(id) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
			configureDlg = new YAHOO.widget.SimpleDialog("dlg", 
				{ visible:false, 
				  width:"510", 
				  effect:[{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.5}],
				  fixedcenter:true, 
				  modal:true, 
				  draggable:false }
			);
			
			fillInConfigureDiv = function(data){
				ajaxStatus.hideStatus();
				// uncomment the line below to debug w/ FireBug
				// console.log(data.responseText); 
				try {
					eval(data.responseText);
				}
				catch(e) {
					result = new Array();
					result['header'] = 'error';
					result['body'] = 'There was an error handling this request.';
				}
				configureDlg.setHeader(result['header']);
				configureDlg.setBody(result['body']);
				var listeners = new YAHOO.util.KeyListener(document, { keys : 27 }, {fn: function() {this.hide();}, scope: configureDlg, correctScope:true} );
				configureDlg.cfg.queueProperty("keylisteners", listeners);

				configureDlg.render(document.body);
				configureDlg.show();
				configureDlg.configFixedCenter(null, false) ;
				SUGAR.util.evalScript(result['body']);
			}

			SUGAR.mySugar.configureDashletId = id; // save the id of the dashlet being configured
			var cObj = YAHOO.util.Connect.asyncRequest('GET','index.php?to_pdf=1&module='+module+'&action=DynamicAction&DynamicAction=configureDashlet&id=' + id, 
													  {success: fillInConfigureDiv, failure: fillInConfigureDiv}, null);
		},
		
				
		/** returns dashlets contents
		 * if url is defined, dashlet will be retrieve with it, otherwise use default url
		 *
		 * @param string id id of the dashlet to refresh
		 * @param string url url to be used
		 * @param function callback callback function after refresh
		 * @param bool dynamic does the script load dynamic javascript, set to true if you user needs to refresh the dashlet after load
		 */
		retrieveDashlet: function(id, url, callback, dynamic) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
					
			if(!url) {
				url = 'index.php?action=DynamicAction&DynamicAction=displayDashlet&session_commit=1&module='+module+'&to_pdf=1&id=' + id;
				is_chart_dashlet = false;
			}
			else if (url == 'predefined_chart'){
				url = 'index.php?action=DynamicAction&DynamicAction=displayDashlet&session_commit=1&module='+module+'&to_pdf=1&id=' + id;
				scriptUrl = 'index.php?action=DynamicAction&DynamicAction=getPredefinedChartScript&session_commit=1&module='+module+'&to_pdf=1&id=' + id;
				is_chart_dashlet = true;
			}
			
			
			if(dynamic) {
				url += '&dynamic=true';
			}

		 	var fillInDashlet = function(data) {

		 		ajaxStatus.hideStatus();
				if(data) {
                    
                    // before we refresh, lets make sure that the returned data is for the current dashlet in focus
                    // AND that it is not the initial 'please reload' verbage, start by grabbing the current dashlet id
                    current_dashlet_id = SUGAR.mySugar.currentDashlet.getAttribute('id');

                    //lets extract the guid portion of the id, to use as a reference
                    dashlet_guid =  current_dashlet_id.substr('dashlet_entire'.length);

                    //now that we have the guid portion, let's search the returned text for it.  There should be many references to it.
                    if(data.responseText.indexOf(dashlet_guid)<0 &&  data.responseText != SUGAR.language.get('app_strings', 'LBL_RELOAD_PAGE') ){
                        //guid id was not found in the returned html, that means we have stale dashlet info due to an auto refresh, do not update
                        return false;
                    }
					SUGAR.mySugar.currentDashlet.innerHTML = data.responseText;			
				}

				SUGAR.util.evalScript(data.responseText);
				if(callback) callback();
				
				var processChartScript = function(scriptData){
					SUGAR.util.evalScript(scriptData.responseText);
					//custom chart code
					SUGAR.mySugar.sugarCharts.loadSugarCharts(activePage);

				}
				if(typeof(is_chart_dashlet)=='undefined'){
					is_chart_dashlet = false;
				}
				if (is_chart_dashlet){				
					var chartScriptObj = YAHOO.util.Connect.asyncRequest('GET', scriptUrl,
													  {success: processChartScript, failure: processChartScript}, null);
				}
			}
			
			SUGAR.mySugar.currentDashlet = document.getElementById('dashlet_entire_' + id);
			var cObj = YAHOO.util.Connect.asyncRequest('GET', url,
			                    {success: fillInDashlet, failure: fillInDashlet}, null); 
			return false;
		},
		
		// for the display columns widget
		setChooser: function() {		
			var displayColumnsDef = new Array();
			var hideTabsDef = new Array();

		    var left_td = document.getElementById('display_tabs_td');	
		    var right_td = document.getElementById('hide_tabs_td');			
	
		    var displayTabs = left_td.getElementsByTagName('select')[0];
		    var hideTabs = right_td.getElementsByTagName('select')[0];
			
			for(i = 0; i < displayTabs.options.length; i++) {
				displayColumnsDef.push(displayTabs.options[i].value);
			}
			
			if(typeof hideTabs != 'undefined') {
				for(i = 0; i < hideTabs.options.length; i++) {
			         hideTabsDef.push(hideTabs.options[i].value);
				}
			}
			
			document.getElementById('displayColumnsDef').value = displayColumnsDef.join('|');
			document.getElementById('hideTabsDef').value = hideTabsDef.join('|');
		},
		
		deleteDashlet: function(id) {
			if(confirm(SUGAR.language.get('app_strings', 'LBL_REMOVE_DASHLET_CONFIRM'))) {
				ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_REMOVING_DASHLET'));
				
				del = function() {
					var success = function(data) {
						dashlet = document.getElementById('dashlet_' + id);
						dashlet.parentNode.removeChild(dashlet);
						ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_REMOVED_DASHLET'));
						window.setTimeout('ajaxStatus.hideStatus()', 2000);
					}
				
					
					var cObj = YAHOO.util.Connect.asyncRequest('GET','index.php?to_pdf=1&module='+module+'&action=DynamicAction&DynamicAction=deleteDashlet&activePage=' + activeTab + '&id=' + id, 
															  {success: success, failure: success}, null);
				}
				
				var anim = new YAHOO.util.Anim('dashlet_entire_' + id, { height: {to: 1} }, .5 );					
				anim.onComplete.subscribe(del);					
				document.getElementById('dashlet_entire_' + id).style.overflow = 'hidden';
				anim.animate();
				
				return false;
			}
			return false;
		},
		
		
		addDashlet: function(id, type, type_module) {
			ajaxStatus.hideStatus();
			columns = SUGAR.mySugar.getLayout();
						
			var num_dashlets = columns[0].length;
			if (typeof columns[1] == undefined){
				num_dashlets = num_dashlets + columns[1].length;
			}
			
			if((num_dashlets) >= SUGAR.mySugar.maxCount) {
				alert(SUGAR.language.get('app_strings', 'LBL_MAX_DASHLETS_REACHED'));
				return;
			}			
/*			if((columns[0].length + columns[1].length) >= SUGAR.mySugar.maxCount) {
				alert(SUGAR.language.get('Home', 'LBL_MAX_DASHLETS_REACHED'));
				return;
			}*/
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_ADDING_DASHLET'));
			var success = function(data) {

				colZero = document.getElementById('col_'+activeTab+'_0');
				newDashlet = document.createElement('li'); // build the list item
				newDashlet.id = 'dashlet_' + data.responseText;
				newDashlet.className = 'noBullet active';
				// hide it first, but append to getRegion
				newDashlet.innerHTML = '<div style="position: absolute; top: -1000px; overflow: hidden;" id="dashlet_entire_' + data.responseText + '"></div>';

				colZero.insertBefore(newDashlet, colZero.firstChild); // insert it into the first column
				
				var finishRetrieve = function() {
					dashletEntire = document.getElementById('dashlet_entire_' + data.responseText);
					dd = new ygDDList('dashlet_' + data.responseText); // make it draggable
					dd.setHandleElId('dashlet_header_' + data.responseText);
                    // Bug #47097 : Dashlets not displayed after moving them
                    // add new property to save real id of dashlet, it needs to have ability reload dashlet by id
                    dd.dashletID = data.responseText;
					dd.onMouseDown = SUGAR.mySugar.onDrag;  
					dd.onDragDrop = SUGAR.mySugar.onDrop;

					ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_ADDED_DASHLET'));
					dashletRegion = YAHOO.util.Dom.getRegion(dashletEntire);
					dashletEntire.style.position = 'relative';
					dashletEntire.style.height = '1px';
					dashletEntire.style.top = '0px';
					dashletEntire.className = 'dashletPanel';
					
					
					var anim = new YAHOO.util.Anim('dashlet_entire_' + data.responseText, { height: {to: dashletRegion.bottom - dashletRegion.top} }, .5 );
					anim.onComplete.subscribe(function() { document.getElementById('dashlet_entire_' + data.responseText).style.height = '100%'; });	
					anim.animate();
					
					newLayout =	SUGAR.mySugar.getLayout(true);
					SUGAR.mySugar.saveLayout(newLayout);	
//					window.setTimeout('ajaxStatus.hideStatus()', 2000);
				}
				
				if (type == 'module' || type == 'web'){
					url = null;
					type = 'module';
				}
				else if (type == 'predefined_chart'){
					url = 'predefined_chart';
					type = 'predefined_chart';
				}
				else if (type == 'chart'){
					url = 'chart';
					type = 'chart';
				}
				
				SUGAR.mySugar.retrieveDashlet(data.responseText, url, finishRetrieve, true); // retrieve it from the server
			}

			var cObj = YAHOO.util.Connect.asyncRequest('GET','index.php?to_pdf=1&module='+module+'&action=DynamicAction&DynamicAction=addDashlet&activeTab=' + activeTab + '&id=' + id+'&type=' + type + '&type_module=' + encodeURIComponent(type_module), 
													  {success: success, failure: success}, null);						  

			return false;
		},
		
		showDashletsDialog: function() {                                             
			columns = SUGAR.mySugar.getLayout();

            if (this.closeDashletsDialogTimer != null) {
                window.clearTimeout(this.closeDashletsDialogTimer);
            }

			var num_dashlets = 0;
            var i = 0;
            for ( i = 0 ; i < 3; i++ ) {
                if (typeof columns[i] != "undefined") {
                    num_dashlets = num_dashlets + columns[i].length;
                }
            }
			
			if((num_dashlets) >= SUGAR.mySugar.maxCount) {
				alert(SUGAR.language.get('app_strings', 'LBL_MAX_DASHLETS_REACHED'));
				return;
			}
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
			
			var success = function(data) {		
				eval(data.responseText);
				dashletsListDiv = document.getElementById('dashletsList');
				dashletsListDiv.innerHTML = response['html'];
				
				document.getElementById('dashletsDialog_c').style.display = '';
                SUGAR.mySugar.dashletsDialog.show();

				eval(response['script']);
				ajaxStatus.hideStatus();
			}
			
			var cObj = YAHOO.util.Connect.asyncRequest('GET', 'index.php?to_pdf=true&module='+module+'&action=DynamicAction&DynamicAction=dashletsDialog', {success: success, failure: success});			
			return false;
		},
		
		closeDashletsDialog: function(){
			SUGAR.mySugar.dashletsDialog.hide();
			if (this.closeDashletsDialogTimer != null) {
                window.clearTimeout(this.closeDashletsDialogTimer);
            }
            this.closeDashletsDialogTimer = window.setTimeout("document.getElementById('dashletsDialog_c').style.display = 'none';", 2000);
		},

		toggleDashletCategories: function(category){
			document.getElementById('search_string').value = '';
			document.getElementById('searchResults').innerHTML = '';
			
			var moduleTab = document.getElementById('moduleCategory');
			var moduleTabAnchor = document.getElementById('moduleCategoryAnchor');
			var moduleListDiv = document.getElementById('moduleDashlets');

			var chartTab = document.getElementById('chartCategory');
			var chartTabAnchor = document.getElementById('chartCategoryAnchor');			
			var chartListDiv = document.getElementById('chartDashlets');			
			
			var toolsTab = document.getElementById('toolsCategory');
			var toolsTabAnchor = document.getElementById('toolsCategoryAnchor');			
			var toolsListDiv = document.getElementById('toolsDashlets');	
			
			var webTab = document.getElementById('webCategory');
			var webTabAnchor = document.getElementById('webCategoryAnchor');			
			var webListDiv = document.getElementById('webDashlets');	
			
			switch(category){
				case 'module':
					moduleTab.className = 'active';
					moduleTabAnchor.className = 'current';
					moduleListDiv.style.display = '';
					
					chartTab.className = '';
					chartTabAnchor.className = '';
					chartListDiv.style.display = 'none';

					toolsTab.className = '';
					toolsTabAnchor.className = '';
					toolsListDiv.style.display = 'none';

					webTab.className = '';
					webTabAnchor.className = '';
					webListDiv.style.display = 'none';
									
					break;
				case 'chart':
					moduleTab.className = '';
					moduleTabAnchor.className = '';
					moduleListDiv.style.display = 'none';
					
					chartTab.className = 'active';
					chartTabAnchor.className = 'current';
					chartListDiv.style.display = '';					

					toolsTab.className = '';
					toolsTabAnchor.className = '';
					toolsListDiv.style.display = 'none';

					webTab.className = '';
					webTabAnchor.className = '';
					webListDiv.style.display = 'none';
					
					break;
				case 'tools':
					moduleTab.className = '';
					moduleTabAnchor.className = '';
					moduleListDiv.style.display = 'none';
					
					chartTab.className = '';
					chartTabAnchor.className = '';
					chartListDiv.style.display = 'none';					

					toolsTab.className = 'active';
					toolsTabAnchor.className = 'current';
					toolsListDiv.style.display = '';

					webTab.className = '';
					webTabAnchor.className = '';
					webListDiv.style.display = 'none';
					
					break;
                case 'web':
					moduleTab.className = '';
					moduleTabAnchor.className = '';
					moduleListDiv.style.display = 'none';
					
					chartTab.className = '';
					chartTabAnchor.className = '';
					chartListDiv.style.display = 'none';					

					toolsTab.className = '';
					toolsTabAnchor.className = '';
					toolsListDiv.style.display = 'none';

					webTab.className = 'active';
					webTabAnchor.className = 'current';
					webListDiv.style.display = '';					
					
					break;
				default:
					break;					
			}			
			
			document.getElementById('search_category').value = category;
		},
		

		searchDashlets: function(searchStr, searchCategory){
			var moduleTab = document.getElementById('moduleCategory');
			var moduleTabAnchor = document.getElementById('moduleCategoryAnchor');
			var moduleListDiv = document.getElementById('moduleDashlets');

			var chartTab = document.getElementById('chartCategory');
			var chartTabAnchor = document.getElementById('chartCategoryAnchor');			
			var chartListDiv = document.getElementById('chartDashlets');			
			
			var toolsTab = document.getElementById('toolsCategory');
			var toolsTabAnchor = document.getElementById('toolsCategoryAnchor');			
			var toolsListDiv = document.getElementById('toolsDashlets');			

			if (moduleTab != null && chartTab != null && toolsTab != null){	
				moduleListDiv.style.display = 'none';
				chartListDiv.style.display = 'none';	
				toolsListDiv.style.display = 'none';
			
			}
			// dashboards case, where there are no tabs
			else{
				chartListDiv.style.display = 'none';
			}
			
			var searchResultsDiv = document.getElementById('searchResults');
			searchResultsDiv.style.display = '';
	
			var success = function(data) {
				eval(data.responseText);

				searchResultsDiv.innerHTML = response['html'];
			}
			
			var cObj = YAHOO.util.Connect.asyncRequest('GET', 'index.php?to_pdf=true&module='+module+'&action=DynamicAction&DynamicAction=searchDashlets&search='+searchStr+'&category='+searchCategory, {success: success, failure: success});
			return false;
		},
		
		collapseList: function(chartList){
			document.getElementById(chartList+'List').style.display='none';
			document.getElementById(chartList+'ExpCol').innerHTML = '<a href="javascript:void(0)" onClick="javascript:SUGAR.mySugar.expandList(\''+chartList+'\');"><img border="0" src="' + SUGAR.themes.image_server + 'index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=advanced_search.gif" align="absmiddle" />';
		},
		
		expandList: function(chartList){
			document.getElementById(chartList+'List').style.display='';		
			document.getElementById(chartList+'ExpCol').innerHTML = '<a href="javascript:void(0)" onClick="javascript:SUGAR.mySugar.collapseList(\''+chartList+'\');"><img border="0" src="' + SUGAR.themes.image_server + 'index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=basic_search.gif" align="absmiddle" />';
		},
		
		collapseReportList: function(reportChartList){
			document.getElementById(reportChartList+'ReportsChartDashletsList').style.display='none';
			document.getElementById(reportChartList+'ExpCol').innerHTML = '<a href="javascript:void(0)" onClick="javascript:SUGAR.mySugar.expandReportList(\''+reportChartList+'\');"><img border="0" src="' + SUGAR.themes.image_server + 'index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=ProjectPlus.gif" align="absmiddle" />';
		},
		
		expandReportList: function(reportChartList){
			document.getElementById(reportChartList+'ReportsChartDashletsList').style.display='';
			document.getElementById(reportChartList+'ExpCol').innerHTML = '<a href="javascript:void(0)" onClick="javascript:SUGAR.mySugar.collapseReportList(\''+reportChartList+'\');"><img border="0" src="' + SUGAR.themes.image_server + 'index.php?entryPoint=getImage&themeName='+SUGAR.themes.theme_name+'&imageName=ProjectMinus.gif" align="absmiddle" />';
		},
		
		clearSearch: function(){
			document.getElementById('search_string').value = '';

			var moduleTab = document.getElementById('moduleCategory');
			var moduleTabAnchor = document.getElementById('moduleCategoryAnchor');
			var moduleListDiv = document.getElementById('moduleDashlets');
			
			document.getElementById('searchResults').innerHTML = '';
			if (moduleTab != null){
				SUGAR.mySugar.toggleDashletCategories('module');
			}
			else{
				document.getElementById('searchResults').style.display = 'none';
				document.getElementById('chartDashlets').style.display = '';
			}
		},
		
		doneAddDashlets: function() {
			SUGAR.mySugar.dashletsDialog.hide();
			return false;
		},

		
		
		
		renderDashletsDialog: function(){	
            var minHeight = 120;
            var maxHeight = 520;
            var minMargin = 16;

            // adjust dialog height according to current page height
            var pageHeight = document.documentElement.clientHeight;
            var height = Math.min(maxHeight, pageHeight - minMargin * 2);
            height = Math.max(height, minHeight);

			SUGAR.mySugar.dashletsDialog = new YAHOO.widget.Dialog("dashletsDialog", 
			{ width : "480px",
			  height: height + "px",
			  fixedcenter : true,
			  draggable:false,
			  visible : false, 
			 // effect:[{effect:YAHOO.widget.ContainerEffect.SLIDETOP, duration:0.5},{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.5}],
			  modal : true,
			  close:false
			 } );

			var listeners = new YAHOO.util.KeyListener(document, { keys : 27 }, {fn: function() {SUGAR.mySugar.closeDashletsDialog();} } );
			SUGAR.mySugar.dashletsDialog.cfg.queueProperty("keylisteners", listeners);

			document.getElementById('dashletsDialog').style.display = '';																				 
			SUGAR.mySugar.dashletsDialog.render();
			document.getElementById('dashletsDialog_c').style.display = 'none';			
		}	
	 }; 
}();
};
