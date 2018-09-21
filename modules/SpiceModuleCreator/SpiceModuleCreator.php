<?php
/**
 * Created by PhpStorm.
 * User: maretval
 * Date: 09.05.2018
 * Time: 19:16
 * This module enables the creation of basic files needed for a new module
 * directory
 * vardefs.php
 * [ModuleClass].php
 * moduledefs.php
 * Some methods are fakes to be able to use SUgarBean View logic
 */

class SpiceModuleCreator extends SugarBean{
    public $spiceroot; //path to folder where spice is installed
    public $modulepath; //folder path where to save the module
    public $modulename; //module name
    public $tablename; //table name for database
    public $beanname; //class name for module


    public function __construct()
    {
        $this->spiceroot = $this->getSpicePath();
    }

    /**
     * fake SugarBean::toArray()
     * @param bool $dbOnly
     * @param bool $stringOnly
     * @param bool $upperKeys
     * @return array
     */
    function toArray($dbOnly = false, $stringOnly = false, $upperKeys = false)
    {
        return array();
    }

    /**
     * fake SugarBean::isOwner()
     * @return bool
     */
    public function isOwner(){
        return $this->ACLAccess();
    }

    /**
     * fake SugarBean::is_AuditEnabled()
     * @return bool
     */
    public function is_AuditEnabled(){
        return false;
    }

    /**
     * fake SugarBean::ACLAccess()
     * @return bool
     */
    public function ACLAccess(){
        if($GLOBALS['current_user']->is_admin){
            return true;
        }
        return false;
    }
    /**
     * fake SugarBean::unformat_all_fields()
     * @return string
     */
    public function unformat_all_fields(){
        $GLOBALS['log']->deprecated('SugarBean.php: unformat_all_fields() is deprecated');
    }

    /**
     * fake SugarBean::get_summary_text()
     * @return string
     */
    public function get_summary_text(){
        return '';
    }

    /**
     * fake SugarBean::retrieve()
     * @return string
     */
    public function retrieve(){
        return '';
    }

    //////////////// effective methods ////////////////////
    public function getSpicePath(){
        $path = "";
        $levels = 2;
        if(preg_match("custom".DIRECTORY_SEPARATOR."modules", dirname(__FILE__))){
            $levels = 3;
        }

        $parts = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
        for($i=0; $i< count($parts)-$levels; $i++){
            $path.= $parts[$i].DIRECTORY_SEPARATOR;
        }
        return $path;
    }

    public function save(){
        //populate
        $this->modulepath = $_POST['modulepath'];
        if(substr($this->modulepath, -1) != DIRECTORY_SEPARATOR && substr($this->modulepath, -1) != "/")
            $this->modulepath.= "/";

        $this->modulename = $_POST['modulename'];
        if(substr($this->modulename, -1) == DIRECTORY_SEPARATOR || substr($this->modulename, -1) == "/")
            $this->modulename = substr($this->modulename, 0,strlen($this->modulename)-1);
        $this->id = $this->modulename;
        $this->tablename = $_POST['tablename'];
        $this->beanname = $_POST['beanname'];

        //create files
        if(!$this->createModuleDir())
            die("Could not create module directory. Action aborted");
        if(!$this->createModuleDefs())
            die("Could not create moduledefs.php. Action Aborted");
        if(!$this->createModuleVardefs())
            die("Could not create vardefs.php. Action Aborted");
        if(!$this->createModuleClass())
            die("Could not create class ".$this->beanname.".php. Action Aborted");

        return $this->id;

    }

    public function createModuleDir(){
        if(empty($this->modulename)) return false;

        $path = $this->spiceroot.$this->modulepath.$this->modulename;
        if(!opendir($path)) {
            $results = array('success' => true);
            if(!mkdir($path, 0755))
                return false;
        }
        return true;
    }

    public function createModuleDefs(){
        if(empty($this->modulename)) return false;
        if(empty($this->beanname)) return false;

        $path = $this->spiceroot.$this->modulepath.$this->modulename;
        $file = $path . "/" . "moduledefs.php";
        if(!file_exists($file)) {
            $hl = fopen($file, "w");
            $fcontent = "<?php
/**
 * SpiceCRM backend information
 *
 * \$moduleList: array containing a list of modules in the system. The format of the array is to have a numeric index and a value of the modules unique key.
 *
 * \$beanList: array that stores a list of all active beans (modules) in the application.
 *
 * \$beanFiles: array used to reference the class files for a bean.
 *
 * \$modInvisList: removes a module from the navigation tab in the MegaMenu, reporting, and it's subpanels under related modules.
 * To enable a hidden module for reporting, you can use \$report_include_modules. To enable a hidden modules subpanels on related modules, you can use \$modules_exempt_from_availability_check.
 *
 * \$report_include_modules: used in conjunction with \$modInvisList. When a module has been hidden with \$modInvisList, this will allow for the module to be enabled for reporting.
 *
 * \$adminOnlyList: extra level of security for modules that are can be accessed only by administrators through the Admin page. Specifying all will restrict all actions to be admin only.. 
 **/             
//classic settings
\$moduleList[] = '".$this->modulename."'; //comment in case module shall not be display in module administration > display modules and subpanels
\$beanList['".$this->modulename."'] = '".$this->beanname."';
\$beanFiles['".$this->beanname."'] = '".$this->modulepath.$this->modulename."/".$this->beanname.".php';

//possible additional settings
//\$modInvisList[] = '".$this->modulename."';
//\$report_include_modules['".$this->modulename."'] = '".$this->beanname."';
//\$modules_exempt_from_availability_check['".$this->modulename."']] = '".$this->modulename."'];
//\$adminOnlyList['".$this->modulename."'] = array('all' => 1);
";

            if(!fwrite($hl, $fcontent)){
                return false;
            }
            return true;
        }

    }
    public function createModuleVardefs(){
        if(empty($this->modulename)) return false;
        if(empty($this->beanname)) return false;
        if(empty($this->tablename)) return false;

        $path = $this->spiceroot.$this->modulepath.$this->modulename;
        if(!file_exists($path . DIRECTORY_SEPARATOR . "vardefs.php")) {
            $hl = fopen($path . DIRECTORY_SEPARATOR . "vardefs.php", "w");
            $fcontent = "<?php \n 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

\$dictionary['" . $this->beanname . "'] = array(
    'table' => '" . $this->tablename . "',
    'comment' => '" . $this->modulename . " Module',
    'audited' =>  false,
    'duplicate_merge' =>  false,
    'unified_search' =>  false,
	
	'fields' => array(
	
	),
	'relationships' => array(
	),
	'indices' => array(
	)
);

VardefManager::createVardef('" . $this->modulename . "', '" . $this->beanname . "', array('default', 'assignable'));
";
            if(!fwrite($hl, $fcontent)){
                return false;
            }
            return true;
        }

    }
    public function createModuleClass(){
        if(empty($this->modulename)) return false;
        if(empty($this->beanname)) return false;
        if(empty($this->tablename)) return false;

        $path = $this->spiceroot.$this->modulepath.$this->modulename;
        $file = $path . DIRECTORY_SEPARATOR . $this->beanname.".php";
        if(!file_exists($file)) {
            $hl = fopen($file, "w");
            $fcontent = "<?php \n 
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
        
require_once('data/SugarBean.php');

class ". $this->beanname." extends SugarBean {
    public \$module_dir = '".str_replace("modules/", "", $this->modulename)."';
    public \$object_name = '".$this->beanname."';
    public \$table_name = '".$this->tablename."';
    public \$new_schema = true;
    
    public \$additional_column_fields = Array();

    public \$relationship_fields = Array(
    );


    public function get_summary_text(){
        return \$this->name;
    }

    public function bean_implements(\$interface){
        switch(\$interface){
            case 'ACL':return true;
        }
        return false;
    }    
}";
            if(!fwrite($hl, $fcontent)){
                return false;
            }
            return true;
        }
    }


}