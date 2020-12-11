<?php


namespace SpiceCRM\includes\SugarObjects;

/**
 * Class SpiceModules
 *
 * enables the loading of modules from the Database
 *
 * @package SpiceCRM\includes\SugarObjects
 */
class SpiceModules
{
    static function loadModules(){
        global $moduleList, $beanList, $beanFiles;
        if(isset($_SESSION['modules'])){
            $moduleList = $_SESSION['modules']['moduleList'];
            $beanList = $_SESSION['modules']['beanList'];
            $beanFiles = $_SESSION['modules']['beanFiles'];
        } else {
            $modules = $GLOBALS['db']->query("SELECT module, bean, beanfile, visible FROM sysmodules UNION SELECT module, bean, beanfile, visible FROM syscustommodules");
            while ($module = $GLOBALS['db']->fetchByAssoc($modules)) {
                $moduleList[$module['module']] = $module['module'];

                // if we have a bean try to load the beanfile, build it fromt he name or use the generic sugarbean
                if ($module['bean']) {
                    $beanList[$module['module']] = $module['bean'];
                    if(!empty($module['beanfile']) && file_exists($module['beanfile'])){
                        $beanFiles[$module['bean']] =  $module['beanfile'];
                    } else if (file_exists("modules/{$module['module']}/{$module['bean']}.php")) {
                        $beanFiles[$module['bean']] = "modules/{$module['module']}/{$module['bean']}.php";
                    }  else if (file_exists("custom/modules/{$module['module']}/{$module['bean']}.php")) {
                        $beanFiles[$module['bean']] = "custom/modules/{$module['module']}/{$module['bean']}.php";
                    } else {
                        $beanFiles[$module['bean']] = 'data/SugarBean.php';
                    }
                }
            }
            $_SESSION['modules'] = [
                'moduleList' => $moduleList,
                'beanList' => $beanList,
                'beanFiles' => $beanFiles,
            ];
        }
    }
}
