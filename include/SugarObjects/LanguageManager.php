<?php
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

/**
 * Language files management
 * @api
 */
class LanguageManager
{

	/**
	 * Called from VardefManager to allow for caching a lang file for a module
	 * @param module - the name of the module we are working with
	 * @param templates - an array of templates this module uses
	 */
	public static function createLanguageFile($module , $templates=array('default'), $refresh = false){
		global $mod_strings, $current_language;
		if(inDeveloperMode() || !empty($_SESSION['developerMode'])){
        	$refresh = true;
    	}
		$temp_mod_strings = $mod_strings;
		$lang = $current_language;
        if(empty($lang))
            $lang = $GLOBALS['sugar_config']['default_language'];
		static $createdModules = array();
		if(empty($createdModules[$module]) && ($refresh || !file_exists(sugar_cached('modules/').$module.'/language/'.$lang.'.lang.php'))){
            $loaded_mod_strings = array();
			$loaded_mod_strings = LanguageManager::loadTemplateLanguage($module , $templates, $lang , $loaded_mod_strings);
			$createdModules[$module] = true;
			LanguageManager::refreshLanguage($module,$lang, $loaded_mod_strings);
		}
	}

    /**
     * syslanguage
     * @param bool $sysonly
     * @return array
     */
	public static function getLanguages($sysonly = false){
	    global $db;

	    $retArray =[
	        'available' => [],
	        'default' => ''
        ];

	    $languages = $db->query("SELECT * FROM syslangs " . ($sysonly ? "WHERE system_language = 1" : ""). " ORDER BY sort_sequence, language_name");
	    while($language = $db->fetchByAssoc($languages)){
            $retArray['available'][] = [
                'language_code' => $language['language_code'],
                'language_name' => $language['language_name'],
                'system_language' => $language['system_language'],
                'communication_language' => $language['communication_language']
            ];

            if($language['is_default'])
                $retArray['default'] = $language['language_code'];
        }

        return $retArray;
    }

    /**
     * syslanguage
     * @param $syslang
     * @return array
     */
	public static function loadDatabaseLanguage($syslang){
        $retArray = array();

        // get default Labels
        $q = "SELECT syslanguagetranslations.*, syslanguagelabels.name label
        FROM syslanguagetranslations, syslanguagelabels
        WHERE syslanguagetranslations.syslanguagelabel_id = syslanguagelabels.id
          AND syslanguagetranslations.syslanguage = '".$syslang."'
        ORDER BY label ASC";

        if($res = $GLOBALS['db']->query($q)) {
            while ($row = $GLOBALS['db']->fetchByAssoc($res)) {
                $retArray[$row['label']] = array(
                    'label' => $row['label'],
                    'default' => $row['translation_default'],
                    'short' => $row['translation_short'],
                    'long' => $row['translation_long'],
                );
            }
        }

        // custom translations to default labels
        $q = "SELECT syslanguagecustomtranslations.*, syslanguagelabels.name label
        FROM syslanguagecustomtranslations, syslanguagelabels
        WHERE (syslanguagecustomtranslations.syslanguagelabel_id = syslanguagelabels.id )
          AND syslanguagecustomtranslations.syslanguage = '".$syslang."' ORDER BY label ASC";
        if($res = $GLOBALS['db']->query($q)) {
            while ($row = $GLOBALS['db']->fetchByAssoc($res)) {
                $retArray[$row['label']] = array(
                    'label' => $row['label'],
                    'default' => $row['translation_default'],
                    'short' => $row['translation_short'],
                    'long' => $row['translation_long'],
                );
            }
        }

        // get custom labels
        $q = "SELECT  syslanguagecustomtranslations.*, syslanguagecustomlabels.name label
        FROM syslanguagecustomtranslations, syslanguagecustomlabels
        WHERE syslanguagecustomtranslations.syslanguagelabel_id = syslanguagecustomlabels.id
          AND syslanguagecustomtranslations.syslanguage = '".$syslang."' ORDER BY label ASC";
        if($res = $GLOBALS['db']->query($q)) {
            while ($row = $GLOBALS['db']->fetchByAssoc($res)) {
                $retArray[$row['label']] = array(
                    'label' => $row['label'],
                    'default' => $row['translation_default'],
                    'short' => $row['translation_short'],
                    'long' => $row['translation_long'],
                );
            }
        }

        /*
        no exception handling wanted...
        elseif($GLOBALS['db']->last_error){
            throw new Exception($GLOBALS['db']->last_error);
        }
        */
        return $retArray;
    }

    /**
     * syslanguage
     * create extension language files for labels retrieved from database
     * @param $module
     * @param $language
     */
    public static function createLanguageFileUIOverride($module, $language){
        // loop vardefs and grab module labels
        $bean = BeanFactory::getBean($module);
        if($bean) {
            $fieldDefs = $bean->getFieldDefinitions();
            $extlabels = array();
            foreach ($fieldDefs as $field => $f) {
                if (isset($f['vname']) && !empty($f['vname'])) {
                    $extlabels[] = $f['vname'];
                }
            }

            // Grab labels from db and save to file
            $path = dirname(__FILE__);
            $path = substr($path, 0, strpos($path, "include".DIRECTORY_SEPARATOR."SugarObjects"));

            $file = $path."custom/Extension/modules/". $module ."/Ext/Language/".$language . ".lang.uioverride.php";
            //check dirs in path

            if (count($extlabels) > 0) {
                $q = "(SELECT trans.translation_default, labels.name
                                FROM syslanguagelabels labels
                                INNER JOIN syslanguagetranslations trans on trans.syslanguagelabel_id=labels.id
                                WHERE trans.syslanguage= '" . $language . "' AND labels.name IN ('" . implode("','", $extlabels) . "') 
                      ) UNION (
                        SELECT trans.translation_default, labels.name
                                FROM syslanguagelabels labels
                                INNER JOIN syslanguagecustomtranslations trans on trans.syslanguagelabel_id=labels.id
                                WHERE trans.syslanguage= '" . $language . "' AND labels.name IN ('" . implode("','", $extlabels) . "') 
                      ) UNION (
                        SELECT trans.translation_default, labels.name
                                FROM syslanguagecustomlabels labels
                                INNER JOIN syslanguagecustomtranslations trans on trans.syslanguagelabel_id=labels.id
                                WHERE trans.syslanguage= '" . $language . "' AND labels.name IN ('" . implode("','", $extlabels) . "') 
                      )         
                ";
                if (!$results = $GLOBALS['db']->query($q)) {
                    $GLOBALS['log']->fatal($q . " => " . $GLOBALS['db']->last_error);
                } else {
                        $savefile = false;
                        $extlblstr = "<?php\n";
                        while ($row = $GLOBALS['db']->fetchByAssoc($results)) {
                            if(!$savefile) $savefile = true;
                            $extlblstr .= "\$mod_strings['" . $row['name'] . "'] = '" . addslashes($row['translation_default']) . "';\n";
                        }
                        if($savefile) {
                            //check directories in path
                            if (!is_dir($path . "custom/Extension")) sugar_mkdir($path . "custom/Extension", '0755');
                            if (!is_dir($path . "custom/Extension/modules")) sugar_mkdir($path . "custom/Extension/modules", '0755');
                            if (!is_dir($path . "custom/Extension/modules/" . $module)) sugar_mkdir($path . "custom/Extension/modules/" . $module, '0755');
                            if (!is_dir($path . "custom/Extension/modules/" . $module . "/Ext")) sugar_mkdir($path . "custom/Extension/modules/" . $module . "/Ext", '0755');
                            if (!is_dir($path . "custom/Extension/modules/" . $module . "/Ext/Language")) sugar_mkdir($path . "custom/Extension/modules/" . $module . "/Ext/Language", '0755');
                            //save file
                            sugar_file_put_contents($file, $extlblstr);
                        }
                }
            }
            else{
                echo '<br>no count';
            }
            unset($bean);
        }
    }


	/**
	 * Load the module  tempalte lauguage files
	 * @param module - the name of the module we are working with
	 * @param templates - an array of templates this module uses
	 * @param lang - current language this module use
	 * @param loaded_mod_strings - the string that we will add the module template language  into
	 */
	public static function loadTemplateLanguage($module , $templates , $lang, $loaded_mod_strings){
		$templates = array_reverse($templates);
		foreach($templates as $template){
			$temp = LanguageManager::addTemplate($module,$lang, $template);
			$loaded_mod_strings = sugarLangArrayMerge($loaded_mod_strings, $temp);
		}
		return $loaded_mod_strings;
	}

    public static function addTemplate($module, $lang, $template){
		if($template == 'default')$template = 'basic';
		$templates = array();
		$fields = array();
		if(empty($templates[$template])){
			$path = 'include/SugarObjects/templates/' . $template . '/language/'.$lang.'.lang.php';
			if(file_exists($path)){
				require($path);
				$templates[$template] = $mod_strings;
			}else{
				$path = 'include/SugarObjects/implements/' . $template . '/language/'.$lang.'.lang.php';
				if(file_exists($path)){
					require($path);
					$templates[$template] = $mod_strings;
				}
			}
		}
		if(!empty($templates[$template])){
			return $templates[$template];
		}
	}

	public static function saveCache($module,$lang, $loaded_mod_strings, $additonal_objects= array()){
		if(empty($lang))
			$lang = $GLOBALS['sugar_config']['default_language'];

		$file = create_cache_directory('modules/' . $module . '/language/'.$lang.'.lang.php');
		write_array_to_file('mod_strings',$loaded_mod_strings, $file);
		include($file);

		// put the item in the sugar cache.
		$key = self::getLanguageCacheKey($module,$lang);
		sugar_cache_put($key,$loaded_mod_strings);
	}

	/**
	 * clear out the language cache.
	 * @param string module_dir the module_dir to clear, if not specified then clear
	 *                      clear language cache for all modules.
	 * @param string lang the name of the object we are clearing this is for sugar_cache
	 */
	public static function clearLanguageCache($module_dir = '', $lang = ''){
		if(empty($lang)) {
			$languages = array_keys($GLOBALS['sugar_config']['languages']);
		} else {
			$languages = array($lang);
		}
		//if we have a module name specified then just remove that language file
		//otherwise go through each module and clean up the language
		if(!empty($module_dir)) {
			foreach($languages as $clean_lang) {
				LanguageManager::_clearCache($module_dir, $clean_lang);
			}
		} else {
			$cache_dir = sugar_cached('modules/');
			if(file_exists($cache_dir) && $dir = @opendir($cache_dir)) {
				while(($entry = readdir($dir)) !== false) {
					if ($entry == "." || $entry == "..") continue;
						foreach($languages as $clean_lang) {
							LanguageManager::_clearCache($entry, $clean_lang);
						}
				}
				closedir($dir);
			}
		}
	}

	/**
	 * PRIVATE function used within clearLanguageCache so we do not repeat logic
	 * @param string module_dir the module_dir to clear
	 * @param string lang the name of the language file we are clearing this is for sugar_cache
	 */
	function _clearCache($module_dir = '', $lang){
		if(!empty($module_dir) && !empty($lang)){
			$file = sugar_cached('modules/').$module_dir.'/language/'.$lang.'.lang.php';
			if(file_exists($file)){
				unlink($file);
				$key = self::getLanguageCacheKey($module_dir,$lang);
				sugar_cache_clear($key);
			}
		}
	}

	/**
	 * Given a module, search all of the specified locations, and any others as specified
	 * in order to refresh the cache file
	 *
	 * @param string $module the given module we want to load the vardefs for
	 * @param string $lang the given language we wish to load
	 * @param array $additional_search_paths an array which allows a consumer to pass in additional vardef locations to search
	 */
	public static function refreshLanguage($module, $lang, $loaded_mod_strings = array(), $additional_search_paths = null){
		// Some of the vardefs do not correctly define dictionary as global.  Declare it first.
		$lang_paths = array(
					'modules/'.$module.'/language/'.$lang.'.lang.php',
					'modules/'.$module.'/language/'.$lang.'.lang.override.php',
					'custom/modules/'.$module.'/language/'.$lang.'.lang.php',
					'custom/modules/'.$module.'/Ext/Language/'.$lang.'.lang.ext.php',
				 );

        if(is_array($additional_search_paths) && !empty($additional_search_paths))
            $lang_paths = array_merge($lang_paths, $additional_search_paths);


        #27023, if this module template language file was not attached , get the template from this module vardef cache file if exsits and load the template language files.
		static $createdModules;
		if(empty($createdModules[$module]) && isset($GLOBALS['beanList'][$module])){
				$object = $GLOBALS['beanList'][$module];

// CR1000426 cleanup backend, module Cases removed
//				if ($object == 'aCase')
//		            $object = 'Case';

		        if(!empty($GLOBALS["dictionary"]["$object"]["templates"])){
		        	$templates = $GLOBALS["dictionary"]["$object"]["templates"];
					$loaded_mod_strings = LanguageManager::loadTemplateLanguage($module , $templates, $lang , $loaded_mod_strings);
					$createdModules[$module] = true;
		        }
		}
		//end of fix #27023

		// Add in additional search paths if they were provided.
		if(!empty($additional_search_paths) && is_array($additional_search_paths))
		{
			$lang_paths = array_merge($lang_paths, $additional_search_paths);
		}

		//search a predefined set of locations for the vardef files
		foreach($lang_paths as $path){
			if(file_exists($path)){
				require($path);
				if(!empty($mod_strings)){
					if (function_exists('sugarArrayMergeRecursive')){
						$loaded_mod_strings = sugarArrayMergeRecursive($loaded_mod_strings, $mod_strings);
					}
					else{
						$loaded_mod_strings = sugarLangArrayMerge($loaded_mod_strings, $mod_strings);
					}
				}
			}
		}


		//great! now that we have loaded all of our vardefs.
		//let's go save them to the cache file.
		if(!empty($loaded_mod_strings))
			LanguageManager::saveCache($module, $lang, $loaded_mod_strings);
	}

	public static function loadModuleLanguage($module, $lang, $refresh=false){
		//here check if the cache file exists, if it does then load it, if it doesn't
		//then call refreshVardef
		//if either our session or the system is set to developerMode then refresh is set to true

		// Retrieve the vardefs from cache.
		$key = self::getLanguageCacheKey($module,$lang);

		if(!$refresh)
		{
			$return_result = sugar_cache_retrieve($key);
			if(!empty($return_result) && is_array($return_result)){
				return $return_result;
			}
		}

		// Some of the vardefs do not correctly define dictionary as global.  Declare it first.
		$cachedfile = sugar_cached('modules/').$module.'/language/'.$lang.'.lang.php';
		if($refresh || !file_exists($cachedfile)){
			LanguageManager::refreshLanguage($module, $lang);
		}

		//at this point we should have the cache/modules/... file
		//which was created from the refreshVardefs so let's try to load it.
		if(file_exists($cachedfile)){
			global $mod_strings;

			require $cachedfile;

			// now that we hae loaded the data from disk, put it in the cache.
			if(!empty($mod_strings))
				sugar_cache_put($key,$mod_strings);
			if(!empty($_SESSION['translation_mode'])){
				$mod_strings = array_map('translated_prefix', $mod_strings);
			}
			return $mod_strings;
		}
	}

    /**
     * Return the cache key for the module language definition
     *
     * @static
     * @param  $module
     * @param  $lang
     * @return string
     */
    public static function getLanguageCacheKey($module, $lang)
	{
         return "LanguageManager.$module.$lang";
	}

    /**
     * Remove any cached js language strings.
     *
     * @static
     * @return void
     */
    public static function removeJSLanguageFiles()
    {
        $jsFiles = array();
        getFiles($jsFiles, sugar_cached('jsLanguage'));
        foreach($jsFiles as $file) {
            unlink($file);
        }

        if( empty($GLOBALS['sugar_config']['js_lang_version']) )
            $GLOBALS['sugar_config']['js_lang_version'] = 1;
        else
            $GLOBALS['sugar_config']['js_lang_version'] += 1;

        write_array_to_file( "sugar_config", $GLOBALS['sugar_config'], "config.php");
    }
}

function translated_prefix($key){
	return '[translated]' . $key;
}
