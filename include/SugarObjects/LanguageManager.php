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
     * @deprectaed
     *
     * @todo : check if we shoudl add caching for retrieved results
     *
     * @param $module
     * @param $lang
     * @param $loaded_mod_strings
     * @param array $additonal_objects
     */
	public static function saveCache($module,$lang, $loaded_mod_strings, $additonal_objects= array()){

	    if($module != 'Administration') return;

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

}
