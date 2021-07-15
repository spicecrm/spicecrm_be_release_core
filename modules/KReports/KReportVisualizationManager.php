<?php
/***** SPICE-KREPORTER-HEADER-SPACEHOLDER *****/

use SpiceCRM\modules\KReports\KReportPluginManager;

class KReportVisualizationManager {

    // the available layouts
    var $layouts = [];
    // header data we include before the grid
    var $headerData = [];
    // item data we include per item
    var $itemData = [];
    // plugins that register themeselves for updates and hanlgin on the page
    // currently handled if a plugin has a uid
    var $pluginRegistry = [];
    // centrally keep the pluginmanager
    var $pluginManager;

    public function __construct() {
        global $kreportLayouts;
        include('modules/KReports/config/KReportLayouts.php');
        if (is_array($kreportLayouts))
            $this->layouts = $kreportLayouts;

        $this->pluginManager = new KReportPluginManager();
    }

    public function getLayouts() {
        // get the Layouts
        $layouts = [];
        $layouts[] = [
            'name' => '-',
            'count' => 0
        ];
        foreach ($this->layouts as $layoutName => $layoutData) {
            $layouts[] = [
                'name' => $layoutName,
                'count' => count($layoutData['items'])
            ];
        }

        // manage Colorschemas
        $colors = [];
        global $kreportColors;
        include('modules/KReports/config/KReportColors.php');
        foreach ($kreportColors as $colorSchema => $colorDetails) {
            $colors[] = [
                'id' => $colorSchema,
                'name' => $colorDetails['name'],
                'colors' => implode('*', $colorDetails['colors'])
            ];
        }

        return '<script type="text/javascript">kreportavailablelayouts = ' . json_encode($layouts) . ';kreportavailablecolors = ' . json_encode($colors) . ';</script>';
    }

    public function generateLayout($thisLayout, $height) {
        $layoutGuid = create_guid();

        //$layoutString = '<script type="text/javascript" src="modules/KReports/javascript/kreportsvisualizationmanager.js"></script>';
        // write the registry
        $layoutString = "";
        if (count($this->pluginRegistry) > 0)
            $layoutString .= '<script type="text/javascript">K.kreports.visualizationmanager.myID="vis' . $layoutGuid . '";K.kreports.visualizationmanager.registeredPlugins=' . json_encode($this->pluginRegistry) . '</script>';

        // write the header data
        foreach ($this->headerData as $plugin => $pluginData)
            $layoutString .= $pluginData['object']->getHeader();

        $layoutString .= "<div id='vis" . $layoutGuid . "' style='margin-top: 5px;position: relative;height:" . $height . "px;'>";

        for ($i = 0; $i < count($this->layouts[$thisLayout]['items']); $i++) {
            if (isset($this->itemData[$i + 1])) {
                $layoutString .= "<div id='" . $this->itemData[$i + 1]['divID'] . "' style='position:absolute;" .
                        "top:" . $this->layouts[$thisLayout]['items'][$i]['top'] . ";" .
                        "left:" . $this->layouts[$thisLayout]['items'][$i]['left'] . ";" .
                        "height:" . $this->layouts[$thisLayout]['items'][$i]['height'] . ";" .
                        "width:" . $this->layouts[$thisLayout]['items'][$i]['width'] .
                        ($this->layouts[$thisLayout]['items'][$i]['style'] != '' ? '; ' . $this->layouts[$thisLayout]['items'][$i]['style'] : '') . "'></div>";
                $layoutString .= $this->itemData[$i + 1]['addDivData'];
                $layoutString .= $this->itemData[$i + 1]['content'];
            } else {
                $layoutString .= "<div id='vis" . $layoutGuid . "_" . $i . "' style='position:absolute;border: 2px solid white;background:grey;" .
                        "top:" . $this->layouts[$thisLayout]['items'][$i]['top'] . ";" .
                        "left:" . $this->layouts[$thisLayout]['items'][$i]['left'] . ";" .
                        "height:" . $this->layouts[$thisLayout]['items'][$i]['height'] . ";" .
                        "width:" . $this->layouts[$thisLayout]['items'][$i]['width'] .
                        ($this->layouts[$thisLayout]['items'][$i]['style'] != '' ? '; ' . $this->layouts[$thisLayout]['items'][$i]['style'] : '') . "'></div>";
            }
        }

        $layoutString .= "</div>";
        return $layoutString;
    }

    public function generateLayoutPreview($thisLayout){
         $layoutString = "";

        for ($i = 0; $i < count($this->layouts[$thisLayout]['items']); $i++) {
                $itemText = '<b>Item:&nbsp;' .($i+1) . '</b><i><br>top:&nbsp;' . $this->layouts[$thisLayout]['items'][$i]['top'] . '<br>left:&nbsp;' . $this->layouts[$thisLayout]['items'][$i]['left'] . '<br>height:&nbsp;' . $this->layouts[$thisLayout]['items'][$i]['height']. '<br>width:&nbsp;' . $this->layouts[$thisLayout]['items'][$i]['width'] . '</i>'; 
                $layoutString .= "<div id='" . $this->itemData[$i + 1]['divID'] . "' style='position:absolute;border: 1px solid white;background:#6484B3;color:white;margin:2px;padding:10px;" .
                        "top:" . $this->layouts[$thisLayout]['items'][$i]['top'] . ";" .
                        "left:" . $this->layouts[$thisLayout]['items'][$i]['left'] . ";" .
                        "height:" . $this->layouts[$thisLayout]['items'][$i]['height'] . ";" .
                        "width:" . $this->layouts[$thisLayout]['items'][$i]['width'] .
                        "'>$itemText</div>";
        }

        return $layoutString;
    }
    
    public function updateVisualization($visData, $thisReport, $snaphotId = 0) {

        if ($snaphotId == 'actual')
            $snaphotId = 0;

        $updateArray = [];

        if ($visData != '') {
            // convert JSON to Array
            $visObject = json_decode($visData, true);
            if ($visObject['layout'] != '-') {
                for ($thisElement = 1; $thisElement <= count($this->layouts[$visObject['layout']]['items']); $thisElement++) {
                    $thisData = $visObject[$thisElement];
                    if (is_array($thisData) && $thisData['plugin'] != '' && $thisData[$thisData['plugin']]['uid'] != '') {

                        $thisPluginObject = $this->pluginManager->getVisualizationObject($thisData['plugin']);
                        if ($thisPluginObject)
                            $updateArray[$thisData[$thisData['plugin']]['uid']] = base64_encode($thisPluginObject->getItemUpdate($thisReport, $thisData[$thisData['plugin']], $snaphotId));
                        else
                            die('pluginError');
                    }
                }
                return json_encode($updateArray);
            }
        }
        else
            return '';
    }

    public function renderVisualization($visData, $thisReport, $addParams = []) {

        if ($visData != '') {
            // convert JSON to Array
            $visObject = json_decode($visData, true);
            if ($visObject['layout'] != '-') {
                for ($thisElement = 1; $thisElement <= count($this->layouts[$visObject['layout']]['items']); $thisElement++) {
                    $thisData = $visObject[$thisElement];
                    if (is_array($thisData) && isset($thisData['plugin'])) {
                        if (!isset($this->headerData[$thisData['plugin']])) {
                            $this->headerData[$thisData['plugin']]['object'] = $this->pluginManager->getVisualizationObject($thisData['plugin']);
                            if ($this->headerData[$thisData['plugin']]['object'])
                                $this->headerData[$thisData['plugin']]['header'] = $this->headerData[$thisData['plugin']]['object']->getHeader();
                            else
                                die('pluginError');
                        }

                        $this->itemData[$thisElement]['divID'] = 'vis' . ($thisData[$thisData['plugin']]['uid'] != '' ? $thisData[$thisData['plugin']]['uid'] : 'gc' . create_guid());
                        $this->itemData[$thisElement]['addDivData'] =  $this->headerData[$thisData['plugin']]['object']->getAddVizDiv($this->itemData[$thisElement]['divID']);
                        
                        if ($thisData[$thisData['plugin']]['uid'] != '')
                            $this->pluginRegistry[] = $thisData[$thisData['plugin']]['uid'];

                        $this->itemData[$thisElement]['content'] = $this->headerData[$thisData['plugin']]['object']->getItem($this->itemData[$thisElement]['divID'], $thisReport, $thisData[$thisData['plugin']], $addParams);
                        
                        
                    }
                }

                // start processing each Vizualization Area
                $layoutData = $this->generateLayout($visObject['layout'], $visObject['height']);
                return $layoutData;
            }
        }
        else
            return '';
    }

    public function getVisualizationExport($visData, $itemData) {
        $visObjectArray = [];

        $pluginObjects = [];

        if ($visData != '') {
            // convert JSON to Array
            $visObject = json_decode($visData, true);
            if ($visObject['layout'] != '-') {
                for ($thisElement = 1; $thisElement <= count($this->layouts[$visObject['layout']]['items']); $thisElement++) {
                    $thisData = $visObject[$thisElement];
                    if (is_array($thisData) && isset($thisData['plugin'])) {
                        if (!isset($pluginObjects[$thisData['plugin']])) {
                            $pluginObjects[$thisData['plugin']] = $this->pluginManager->getVisualizationObject($thisData['plugin']);
                            if (!$pluginObjects[$thisData['plugin']])
                                die('pluginError');
                        }
                        $thisVisObjectArray = $pluginObjects[$thisData['plugin']]->parseExportData($itemData[$thisData[$thisData['plugin']]['uid']]);
                        $thisVisObjectArray['layoutdata'] = $this->layouts[$visObject['layout']]['items'][$thisElement - 1];
                        $visObjectArray[] = $thisVisObjectArray;
                    }
                }
            }
        }
        return $visObjectArray;
    }

}
