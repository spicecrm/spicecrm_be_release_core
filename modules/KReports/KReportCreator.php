<?php
/* * *******************************************************************************
* This file is part of KReporter. KReporter is an enhancement developed
* by aac services k.s.. All rights are (c) 2016 by aac services k.s.
*
* This Version of the KReporter is licensed software and may only be used in
* alignment with the License Agreement received with this Software.
* This Software is copyrighted and may not be further distributed without
* witten consent of aac services k.s.
*
* You can contact us at info@kreporter.org
******************************************************************************* */

class KReportCreator {



    public function displayDefaultConfForm(){
        $sm = new Sugar_Smarty();
        return $sm->display("modules/Administration/templates/KReportsDefault.tpl");
    }


    public function createDefaultConf(){
        // load file containing default queries
        $kreportsdefaultsqls = $this->loadKReportDefaultConfig();
        if(empty($kreportsdefaultsqls))
            return false;

        // process
        foreach($kreportsdefaultsqls as $kreportsdefaultsql){
            $GLOBALS['db']->query($kreportsdefaultsql);
        }

        //display
        if(!$GLOBALS['installing']) {
            echo('KReports Default Config was restored.');
        }
        return true;
    }

    public function loadKReportDefaultConfig(){
        $uiconfigfile = get_custom_file_if_exists('install/kreports/kreportsdefaultconf.txt');
        if(is_file($uiconfigfile)){
            $sqlBuilds = array();
            $sql = "";
            $sqls = explode("\n", file_get_contents($uiconfigfile));

            foreach($sqls as $sqlPart){
                if(substr($sqlPart, 0, 2) == '--') continue;
                if(substr($sqlPart, 0, 2) == '/*') continue;
                if(substr($sqlPart, 0, 2) == 'IN' || substr($sqlPart, 0, 2) == 'RE') {
                    if(strlen($sqlPart) > 1) {
                        $sqlBuilds[] = $sqlPart;
                    }
                }
            }
        }
        return $sqlBuilds;
    }

}
