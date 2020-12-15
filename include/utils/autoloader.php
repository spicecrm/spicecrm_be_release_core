<?php
/***** SPICE-SUGAR-HEADER-SPACEHOLDER *****/

class SugarAutoLoader
{

	public static $noAutoLoad = array(
		'Tracker' => true,
	);

	public static $moduleMap = array();

	public static function autoload($class)
	{
		if (!empty(SugarAutoLoader::$noAutoLoad[$class])) {
			return false;
		}

		if (empty(SugarAutoLoader::$moduleMap)) {
			SugarAutoLoader::$moduleMap = $_SESSION['modules']['beanFiles'];
		}
		if (!empty(SugarAutoLoader::$moduleMap[$class])) {
			if (file_exists(SugarAutoLoader::$moduleMap[$class])) {
				require_once(SugarAutoLoader::$moduleMap[$class]);
				return true;
			}
		}
		return false;
	}

}

