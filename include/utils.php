<?php

/* * *** SPICE-SUGAR-HEADER-SPACEHOLDER **** */

/* * *******************************************************************************

 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * ****************************************************************************** */
require_once('include/SugarObjects/SpiceConfig.php');
require_once('include/utils/security_utils.php');




function get_languages() {
    global $sugar_config;
    $lang = $sugar_config['languages'];
    if (!empty($sugar_config['disabled_languages'])) {
        foreach (explode(',', $sugar_config['disabled_languages']) as $disable) {
            unset($lang[$disable]);
        }
    }
    return $lang;
}


function get_assigned_user_name($assigned_user_id, $is_group = '') {
    static $saved_user_list = null;

    if (empty($saved_user_list)) {
        $saved_user_list = get_user_array(false, '', '', false, null, $is_group);
    }

    if (isset($saved_user_list[$assigned_user_id])) {
        return $saved_user_list[$assigned_user_id];
    }

    return '';
}


//TODO Update to use global cache
/**
 * get_user_array
 *
 * This is a helper function to return an Array of users depending on the parameters passed into the function.
 * This function uses the get_register_value function by default to use a caching layer where supported.
 * This function has been updated return the array sorted by user preference of name display (bug 62712)
 *
 * @param bool $add_blank Boolean value to add a blank entry to the array results, true by default
 * @param string $status String value indicating the status to filter users by, "Active" by default
 * @param string $user_id String value to specify a particular user id value (searches the id column of users table), blank by default
 * @param bool $use_real_name Boolean value indicating whether or not results should include the full name or just user_name, false by default
 * @param String $user_name_filter String value indicating the user_name filter (searches the user_name column of users table) to optionally search with, blank by default
 * @param string $portal_filter String query filter for portal users (defaults to searching non-portal users), change to blank if you wish to search for all users including portal users
 * @param bool $from_cache Boolean value indicating whether or not to use the get_register_value function for caching, true by default
 * @return array Array of users matching the filter criteria that may be from cache (if similar search was previously run)
 */
function get_user_array($add_blank = true, $status = "Active", $user_id = '', $use_real_name = false, $user_name_filter = '', $portal_filter = ' AND portal_only=0 ', $from_cache = true) {
    global $locale, $sugar_config, $current_user;

    if (empty($locale)) {
        $locale = new Localization();
    }

    if ($from_cache) {
        $key_name = $add_blank . $status . $user_id . $use_real_name . $user_name_filter . $portal_filter;
        $user_array = get_register_value('user_array', $key_name);
    }

    if (empty($user_array)) {
        $db = DBManagerFactory::getInstance();
        $temp_result = Array();
        // Including deleted users for now.
        if (empty($status)) {
            $query = "SELECT id, first_name, last_name, user_name from users WHERE 1=1" . $portal_filter;
        } else {
            $query = "SELECT id, first_name, last_name, user_name from users WHERE status='$status'" . $portal_filter;
        }

        if (!empty($user_name_filter)) {
            $user_name_filter = $db->quote($user_name_filter);
            $query .= " AND user_name LIKE '$user_name_filter%' ";
        }
        if (!empty($user_id)) {
            $query .= " OR id='{$user_id}'";
        }

        //get the user preference for name formatting, to be used in order by
        $order_by_string = ' user_name ASC ';
        if (!empty($current_user) && !empty($current_user->id)) {
            $formatString = $current_user->getPreference('default_locale_name_format');

            //create the order by string based on position of first and last name in format string
            $order_by_string = ' user_name ASC ';
            $firstNamePos = strpos($formatString, 'f');
            $lastNamePos = strpos($formatString, 'l');
            if ($firstNamePos !== false || $lastNamePos !== false) {
                //its possible for first name to be skipped, check for this
                if ($firstNamePos === false) {
                    $order_by_string = 'last_name ASC';
                } else {
                    $order_by_string = ($lastNamePos < $firstNamePos) ? "last_name, first_name ASC" : "first_name, last_name ASC";
                }
            }
        }

        $query = $query . ' ORDER BY ' . $order_by_string;
        $GLOBALS['log']->debug("get_user_array query: $query");
        $result = $db->query($query, true, "Error filling in user array: ");

        if ($add_blank == true) {
            // Add in a blank row
            $temp_result[''] = '';
        }

        // Get the id and the name.
        while ($row = $db->fetchByAssoc($result)) {
            if ($use_real_name == true || showFullName()) {
                if (isset($row['last_name'])) { // cn: we will ALWAYS have both first_name and last_name (empty value if blank in db)
                    $temp_result[$row['id']] = $locale->getLocaleFormattedName($row['first_name'], $row['last_name']);
                } else {
                    $temp_result[$row['id']] = $row['user_name'];
                }
            } else {
                $temp_result[$row['id']] = $row['user_name'];
            }
        }

        $user_array = $temp_result;
        if ($from_cache) {
            set_register_value('user_array', $key_name, $temp_result);
        }
    }


    return $user_array;
}


/**
 *
 * based on user pref then system pref
 */
function showFullName() {
    global $sugar_config;
    global $current_user;
    static $showFullName = null;

    if (is_null($showFullName)) {
        $sysPref = !empty($sugar_config['use_real_names']);
        $userPref = (is_object($current_user)) ? $current_user->getPreference('use_real_names') : null;

        if ($userPref != null) {
            $showFullName = ($userPref == 'on');
        } else {
            $showFullName = $sysPref;
        }
    }

    return $showFullName;
}



/**
 * This function retrieves an application language file and returns the array of strings included in the $app_list_strings var.
 *
 * @param string $language specific language to load
 * @return array lang strings
 */
function return_app_list_strings_language($language, $scope = 'all') {
    global $app_list_strings;
    global $sugar_config;

    $cache_key = 'app_list_strings.' . $language;

    // Check for cached value
    if($scope == 'all') {
        $cache_entry = sugar_cache_retrieve($cache_key);
        if (!empty($cache_entry)) {
            return $cache_entry;
        }
    }

    $default_language = $sugar_config['default_language'];
    $temp_app_list_strings = $app_list_strings;

    $langs = array();
    if ($language != 'en_us') {
        $langs[] = 'en_us';
    }
    if ($default_language != 'en_us' && $language != $default_language) {
        $langs[] = $default_language;
    }
    $langs[] = $language;

    $app_list_strings_array = array();

    foreach ($langs as $lang) {
        $app_list_strings = array();
        if($scope == 'all' || $scope == 'global') {
            if (file_exists("include/language/$lang.lang.php")) {
                include("include/language/$lang.lang.php");
                $GLOBALS['log']->info("Found language file: $lang.lang.php");
            }
            if (file_exists("include/language/$lang.lang.override.php")) {
                include("include/language/$lang.lang.override.php");
                $GLOBALS['log']->info("Found override language file: $lang.lang.override.php");
            }
            if (file_exists("include/language/$lang.lang.php.override")) {
                include("include/language/$lang.lang.php.override");
                $GLOBALS['log']->info("Found override language file: $lang.lang.php.override");
            }
        }

        if($scope == 'all' || $scope == 'custom') {
            //check custom
            if (file_exists("custom/include/language/$lang.lang.php")) {
                include("custom/include/language/$lang.lang.php");
                $GLOBALS['log']->info("Found language file: $lang.lang.php");
            }
            if (file_exists("custom/include/language/$lang.lang.override.php")) {
                include("custom/include/language/$lang.lang.override.php");
                $GLOBALS['log']->info("Found override language file: $lang.lang.override.php");
            }
            if (file_exists("custom/include/language/$lang.lang.php.override")) {
                include("custom/include/language/$lang.lang.php.override");
                $GLOBALS['log']->info("Found override language file: $lang.lang.php.override");
            }
        }

        // BEGIN CR1000108 vardefs to db
        if(isset($GLOBALS['sugar_config']['systemvardefs']['domains']) && $GLOBALS['sugar_config']['systemvardefs']['domains']){
            //load sys_app_list_strings
            $sys_app_list_strings = SpiceCRM\modules\SystemVardefs\SystemVardefs::createDictionaryValidationDoms($language);
            // add to app_list_strings
            foreach($sys_app_list_strings as $dom => $lang){
                foreach($lang[$language] as $values => $val){
                    foreach($val as $minvalue => $definition) {
                        $app_list_strings[$dom][$definition['minvalue']] = $definition['translation'];
                    }
                }
            }
        }
        // END

        $app_list_strings_array[] = $app_list_strings;
    }

    $app_list_strings = array();
    foreach ($app_list_strings_array as $app_list_strings_item) {
        $app_list_strings = sugarLangArrayMerge($app_list_strings, $app_list_strings_item);
    }

    if($scope == 'all' || $scope == 'custom') {
        foreach ($langs as $lang) {
            if (file_exists("custom/application/Ext/Language/$lang.lang.ext.php")) {
                $app_list_strings = _mergeCustomAppListStrings("custom/application/Ext/Language/$lang.lang.ext.php", $app_list_strings);
                $GLOBALS['log']->info("Found extended language file: $lang.lang.ext.php");
            }
            if (file_exists("custom/include/language/$lang.lang.php")) {
                include("custom/include/language/$lang.lang.php");
                $GLOBALS['log']->info("Found custom language file: $lang.lang.php");
            }
        }
    }

    if (!isset($app_list_strings)) {
        $GLOBALS['log']->fatal("Unable to load the application language file for the selected language ($language) or the default language ($default_language) or the en_us language");
        return null;
    }

    $return_value = $app_list_strings;
    $app_list_strings = $temp_app_list_strings;

    if($scope != 'all') {
        sugar_cache_put($cache_key, $return_value);
    }

    return $return_value;
}

/**
 * The dropdown items in custom language files is $app_list_strings['$key']['$second_key'] = $value not
 * $GLOBALS['app_list_strings']['$key'] = $value, so we have to delete the original ones in app_list_strings and relace it with the custom ones.
 * @param file string the language that you want include,
 * @param app_list_strings array the golbal strings
 * @return array
 */
//jchi 25347
function _mergeCustomAppListStrings($file, $app_list_strings) {
    $app_list_strings_original = $app_list_strings;
    unset($app_list_strings);
    // FG - bug 45525 - $exemptDropdown array is defined (once) here, not inside the foreach
    //                  This way, language file can add items to save specific standard codelist from being overwritten
    $exemptDropdowns = array();
    include($file);
    if (!isset($app_list_strings) || !is_array($app_list_strings)) {
        return $app_list_strings_original;
    }
    //Bug 25347: We should not merge custom dropdown fields unless they relate to parent fields or the module list.
    // FG - bug 45525 - Specific codelists must NOT be overwritten
    $exemptDropdowns[] = "moduleList";
    $exemptDropdowns[] = "moduleListSingular";
    $exemptDropdowns = array_merge($exemptDropdowns, getTypeDisplayList());

    foreach ($app_list_strings as $key => $value) {
        if (!in_array($key, $exemptDropdowns) && array_key_exists($key, $app_list_strings_original)) {
            unset($app_list_strings_original["$key"]);
        }
    }
    $app_list_strings = sugarArrayMergeRecursive($app_list_strings_original, $app_list_strings);
    return $app_list_strings;
}

/**
 * This function retrieves an application language file and returns the array of strings included.
 *
 * @param string $language specific language to load
 * @return array lang strings
 */
function return_application_language($language) {
    global $app_strings, $sugar_config;

    $cache_key = 'app_strings.' . $language;

    // Check for cached value
    $cache_entry = sugar_cache_retrieve($cache_key);
    if (!empty($cache_entry)) {
        return $cache_entry;
    }

    $temp_app_strings = $app_strings;
    $default_language = $sugar_config['default_language'];

    $langs = array();
    if ($language != 'en_us') {
        $langs[] = 'en_us';
    }
    if ($default_language != 'en_us' && $language != $default_language) {
        $langs[] = $default_language;
    }

    $langs[] = $language;

    $app_strings_array = array();

    foreach ($langs as $lang) {
        $app_strings = array();
        if (file_exists("include/language/$lang.lang.php")) {
            include("include/language/$lang.lang.php");
            $GLOBALS['log']->info("Found language file: $lang.lang.php");
        }
        if (file_exists("include/language/$lang.lang.override.php")) {
            include("include/language/$lang.lang.override.php");
            $GLOBALS['log']->info("Found override language file: $lang.lang.override.php");
        }
        if (file_exists("include/language/$lang.lang.php.override")) {
            include("include/language/$lang.lang.php.override");
            $GLOBALS['log']->info("Found override language file: $lang.lang.php.override");
        }
        if (file_exists("custom/application/Ext/Language/$lang.lang.ext.php")) {
            include("custom/application/Ext/Language/$lang.lang.ext.php");
            $GLOBALS['log']->info("Found extended language file: $lang.lang.ext.php");
        }
        if (file_exists("custom/include/language/$lang.lang.php")) {
            include("custom/include/language/$lang.lang.php");
            $GLOBALS['log']->info("Found custom language file: $lang.lang.php");
        }
        // BEGIN syslanguages
        if (file_exists("custom/application/Ext/Language/$lang.override.ext.php")) {
            global $extlabels;
            include("custom/application/Ext/Language/$lang.override.ext.php");
            $app_strings = array_merge($app_strings, $extlabels);
            $GLOBALS['log']->info("Found extended language file: $lang.override.ext.php");
        }
        //END syslanguages
        $app_strings_array[] = $app_strings;
    }

    $app_strings = array();
    foreach ($app_strings_array as $app_strings_item) {
        $app_strings = sugarLangArrayMerge($app_strings, $app_strings_item);
    }

    if (!isset($app_strings)) {
        $GLOBALS['log']->fatal("Unable to load the application language strings");
        return null;
    }

    // If we are in debug mode for translating, turn on the prefix now!
    if (!empty($sugar_config['translation_string_prefix'])) {
        foreach ($app_strings as $entry_key => $entry_value) {
            $app_strings[$entry_key] = $language . ' ' . $entry_value;
        }
    }
    if (isset($_SESSION['show_deleted'])) {
        $app_strings['LBL_DELETE_BUTTON'] = $app_strings['LBL_UNDELETE_BUTTON'];
        $app_strings['LBL_DELETE_BUTTON_LABEL'] = $app_strings['LBL_UNDELETE_BUTTON_LABEL'];
        $app_strings['LBL_DELETE_BUTTON_TITLE'] = $app_strings['LBL_UNDELETE_BUTTON_TITLE'];
        $app_strings['LBL_DELETE'] = $app_strings['LBL_UNDELETE'];
    }

    // $app_strings['LBL_ALT_HOT_KEY'] = get_alt_hot_key();

    $return_value = $app_strings;
    $app_strings = $temp_app_strings;

    sugar_cache_put($cache_key, $return_value);

    return $return_value;
}

/**
 *
 * @deprecated - Module Language no longer exisats
 *
 * This function retrieves a module's language file and returns the array of strings included.
 *
 * @param string $language specific language to load
 * @param string $module module name to load strings for
 * @param bool $refresh optional, true if you want to rebuild the language strings
 * @return array lang strings
 */
function return_module_language($language, $module, $refresh = false) {

    return [];
}

/** This function retrieves an application language file and returns the array of strings included in the $mod_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_mod_list_strings_language($language, $module) {
    return [];
}

/** If the session variable is defined and is not equal to "" then return it.  Otherwise, return the default value.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function return_session_value_or_default($varname, $default) {
    if (isset($_SESSION[$varname]) && $_SESSION[$varname] != "") {
        return $_SESSION[$varname];
    }

    return $default;
}

/**
 * determines if a passed string matches the criteria for a Sugar GUID
 * @param string $guid
 * @return bool False on failure
 */
function is_guid($guid) {
    if (strlen($guid) != 36) {
        return false;
    }

    if (preg_match("/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/i", $guid)) {
        return true;
    }

    return true;
    ;
}

/**
 * A temporary method of generating GUIDs of the correct format for our DB.
 * @return String contianing a GUID in the format: aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee
 *
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function create_guid() {
    $microTime = microtime();
    list($a_dec, $a_sec) = explode(" ", $microTime);

    $dec_hex = dechex($a_dec * 1000000);
    $sec_hex = dechex($a_sec);

    ensure_length($dec_hex, 5);
    ensure_length($sec_hex, 6);

    $guid = "";
    $guid .= $dec_hex;
    $guid .= create_guid_section(3);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= create_guid_section(4);
    $guid .= '-';
    $guid .= $sec_hex;
    $guid .= create_guid_section(6);

    return $guid;
}

function create_guid_section($characters) {
    $return = "";
    for ($i = 0; $i < $characters; $i++) {
        $return .= dechex(mt_rand(0, 15));
    }
    return $return;
}

function ensure_length(&$string, $length) {
    $strlen = strlen($string);
    if ($strlen < $length) {
        $string = str_pad($string, $length, "0");
    } else if ($strlen > $length) {
        $string = substr($string, 0, $length);
    }
}

function microtime_diff($a, $b) {
    list($a_dec, $a_sec) = explode(" ", $a);
    list($b_dec, $b_sec) = explode(" ", $b);
    return $b_sec - $a_sec + $b_dec - $a_dec;
}


// Check if user is admin for at least one module.
function is_admin_for_any_module($user) {
    if (!isset($user)) {
        return false;
    }
    if ($user->isAdmin()) {
        return true;
    }
    return false;
}


/**
 * Check if user id belongs to a system admin.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 */
function is_admin($user) {
    if (empty($user)) {
        return false;
    }

    return $user->isAdmin();
}




/**
 * Call this method instead of die().
 * We print the error message and then die with an appropriate
 * exit code.
 */
function sugar_die($error_message, $exit_code = 1) {
    global $focus;
    sugar_cleanup();
    throw new Exception( $error_message , 500) ;
}


function translate($string, $mod = '', $selectedValue = '') {

    global $mod_strings, $app_strings, $app_list_strings, $current_language;

    $returnValue = '';

    global $app_strings, $app_list_strings;
    if ( !isset( $app_list_strings )) $app_list_strings = return_app_list_strings_language( $current_language );

    if (isset($mod_strings[$string]))
        $returnValue = $mod_strings[$string];
    else if (isset($app_strings[$string]))
        $returnValue = $app_strings[$string];
    else if (isset($app_list_strings[$string]))
        $returnValue = $app_list_strings[$string];
    else if (isset($app_list_strings['moduleList']) && isset($app_list_strings['moduleList'][$string]))
        $returnValue = $app_list_strings['moduleList'][$string];


    if (empty($returnValue)) {
        return $string;
    }

    // Bug 48996 - Custom enums with '0' value were not returning because of empty check
    // Added a numeric 0 checker to the conditional to allow 0 value indexed to pass
    if (is_array($returnValue) && (!empty($selectedValue) || (is_numeric($selectedValue) && $selectedValue == 0)) && isset($returnValue[$selectedValue])) {
        return $returnValue[$selectedValue];
    }

    return $returnValue;
}


/**
 * Designed to take a string passed in the URL as a parameter and clean all "bad" data from it
 *
 * @param string $str
 * @param string $filter which corresponds to a regular expression to use; choices are:
 * 		"STANDARD" ( default )
 * 		"STANDARDSPACE"
 * 		"FILE"
 * 		"NUMBER"
 * 		"SQL_COLUMN_LIST"
 * 		"PATH_NO_URL"
 * 		"SAFED_GET"
 * 		"UNIFIED_SEARCH"
 * 		"AUTO_INCREMENT"
 * 		"ALPHANUM"
 * @param boolean $dieOnBadData true (default) if you want to die if bad data if found, false if not
 */
function clean_string($str, $filter = "STANDARD", $dieOnBadData = true) {
    global $sugar_config;

    $filters = Array(
        "STANDARD" => '#[^A-Z0-9\-_\.\@]#i',
        "STANDARDSPACE" => '#[^A-Z0-9\-_\.\@\ ]#i',
        "FILE" => '#[^A-Z0-9\-_\.]#i',
        "NUMBER" => '#[^0-9\-]#i',
        "SQL_COLUMN_LIST" => '#[^A-Z0-9\(\),_\.]#i',
        "PATH_NO_URL" => '#://#i',
        "SAFED_GET" => '#[^A-Z0-9\@\=\&\?\.\/\-_~+]#i', /* range of allowed characters in a GET string */
        "UNIFIED_SEARCH" => "#[\\x00]#", /* cn: bug 3356 & 9236 - MBCS search strings */
        "AUTO_INCREMENT" => '#[^0-9\-,\ ]#i',
        "ALPHANUM" => '#[^A-Z0-9\-]#i',
    );

    if (preg_match($filters[$filter], $str)) {
        if (isset($GLOBALS['log']) && is_object($GLOBALS['log'])) {
            $GLOBALS['log']->fatal("SECURITY[$filter]: bad data passed in; string: {$str}");
        }
        if ($dieOnBadData) {
            die("Bad data passed in; <a href=\"{$sugar_config['site_url']}\">Return to Home</a>");
        }
        return false;
    } else {
        return $str;
    }
}



function securexss($value) {
    if (is_array($value)) {
        $new = array();
        foreach ($value as $key => $val) {
            $new[$key] = securexss($val);
        }
        return $new;
    }
    static $xss_cleanup = array("&quot;" => "&#38;", '"' => '&quot;', "'" => '&#039;', '<' => '&lt;', '>' => '&gt;');
    $value = preg_replace(array('/javascript:/i', '/\0/'), array('java script:', ''), $value);
    $value = preg_replace('/javascript:/i', 'java script:', $value);
    return str_replace(array_keys($xss_cleanup), array_values($xss_cleanup), $value);
}



function set_register_value($category, $name, $value) {
    return sugar_cache_put("{$category}:{$name}", $value);
}

function get_register_value($category, $name) {
    return sugar_cache_retrieve("{$category}:{$name}");
}

function display_notice($msg = false) {
    global $error_notice;
    //no error notice - lets just display the error to the user
    if (!isset($error_notice)) {
        echo '<br>' . $msg . '<br>';
    } else {
        $error_notice .= $msg . '<br>';
    }
}


function sugar_cleanup($exit = false) {
    static $called = false;
    if ($called)
        return;
    $called = true;
    set_include_path(realpath(dirname(__FILE__) . '/..') . PATH_SEPARATOR . get_include_path());
    chdir(realpath(dirname(__FILE__) . '/..'));
    global $sugar_config;
    require_once('include/utils/LogicHook.php');
    LogicHook::initialize();
    $GLOBALS['logic_hook']->call_custom_logic('', 'server_round_trip');

    //added this check to avoid errors during install.
    if (empty($sugar_config['dbconfig'])) {
        if ($exit)
            exit;
        else
            return;
    }

    if (!class_exists('Tracker', true)) {
        require_once 'modules/Trackers/Tracker.php';
    }
    Tracker::logPage();
    // Now write the cached tracker_queries
    if (!empty($GLOBALS['savePreferencesToDB']) && $GLOBALS['savePreferencesToDB']) {
        if (isset($GLOBALS['current_user']) && $GLOBALS['current_user'] instanceOf User)
            $GLOBALS['current_user']->savePreferencesToDB();
    }

    if (class_exists('DBManagerFactory')) {
        $db = DBManagerFactory::getInstance();
        $db->disconnect();
        if ($exit) {
            exit;
        }
    }
}

register_shutdown_function('sugar_cleanup');


function display_stack_trace($textOnly = false) {

    $stack = debug_backtrace();

    echo "\n\n display_stack_trace caller, file: " . $stack[0]['file'] . ' line#: ' . $stack[0]['line'];

    if (!$textOnly)
        echo '<br>';

    $first = true;
    $out = '';

    foreach ($stack as $item) {
        $file = '';
        $class = '';
        $line = '';
        $function = '';

        if (isset($item['file']))
            $file = $item['file'];
        if (isset($item['class']))
            $class = $item['class'];
        if (isset($item['line']))
            $line = $item['line'];
        if (isset($item['function']))
            $function = $item['function'];

        if (!$first) {
            if (!$textOnly) {
                $out .= '<font color="black"><b>';
            }

            $out .= $file;

            if (!$textOnly) {
                $out .= '</b></font><font color="blue">';
            }

            $out .= "[L:{$line}]";

            if (!$textOnly) {
                $out .= '</font><font color="red">';
            }

            $out .= "({$class}:{$function})";

            if (!$textOnly) {
                $out .= '</font><br>';
            } else {
                $out .= "\n";
            }
        } else {
            $first = false;
        }
    }

    echo $out;
}

function StackTraceErrorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
    $error_msg = " $errstr occurred in <b>$errfile</b> on line $errline [" . date("Y-m-d H:i:s") . ']';
    $halt_script = true;
    switch ($errno) {
        case 2048: return; //depricated we have lots of these ignore them
        case E_USER_NOTICE:
        case E_NOTICE:
            if (error_reporting() & E_NOTICE) {
                $halt_script = false;
                $type = 'Notice';
            } else
                return;
            break;
        case E_USER_WARNING:
        case E_COMPILE_WARNING:
        case E_CORE_WARNING:
        case E_WARNING:

            $halt_script = false;
            $type = "Warning";
            break;

        case E_USER_ERROR:
        case E_COMPILE_ERROR:
        case E_CORE_ERROR:
        case E_ERROR:

            $type = "Fatal Error";
            break;

        case E_PARSE:

            $type = "Parse Error";
            break;

        default:
            //don't know what it is might not be so bad
            $halt_script = false;
            $type = "Unknown Error ($errno)";
            break;
    }
    $error_msg = '<b>' . $type . '</b>:' . $error_msg;
    echo $error_msg;
    display_stack_trace();
    if ($halt_script) {
        exit - 1;
    }
}

if (isset($sugar_config['stack_trace_errors']) && $sugar_config['stack_trace_errors']) {
    set_error_handler('StackTraceErrorHandler');
}


/**
 * tries to determine whether the Host machine is a Windows machine
 */
function is_windows() {
    static $is_windows = null;
    if (!isset($is_windows)) {
        $is_windows = strtoupper(substr(PHP_OS, 0, 3)) == 'WIN';
    }
    return $is_windows;
}


/**
 * This function will take a string that has tokens like {0}, {1} and will replace
 * those tokens with the args provided
 * @param	$format string to format
 * @param	$args args to replace
 * @return	$result a formatted string
 */
function string_format($format, $args) {
    $result = $format;

    /** Bug47277 fix.
     * If args array has only one argument, and it's empty, so empty single quotes are used '' . That's because
     * IN () fails and IN ('') works.
     */
    if (count($args) == 1) {
        reset($args);
        $singleArgument = current($args);
        if (empty($singleArgument)) {
            return str_replace("{0}", "''", $result);
        }
    }
    /* End of fix */

    for ($i = 0; $i < count($args); $i++) {
        $result = str_replace('{' . $i . '}', $args[$i], $result);
    }
    return $result;
}



require_once('include/utils/db_utils.php');


/**
 * Identical to sugarArrayMerge but with some speed improvements and used specifically to merge
 * language files.  Language file merges do not need to account for null values so we can get some
 * performance increases by using this specialized function. Note this merge function does not properly
 * handle null values.
 *
 * @param $gimp
 * @param $dom
 * @return array
 */
function sugarLangArrayMerge($gimp, $dom) {
    if (is_array($gimp) && is_array($dom)) {
        foreach ($dom as $domKey => $domVal) {
            if (isset($gimp[$domKey])) {
                if (is_array($domVal)) {
                    $tempArr = array();
                    foreach ($domVal as $domArrKey => $domArrVal)
                        $tempArr[$domArrKey] = $domArrVal;
                    foreach ($gimp[$domKey] as $gimpArrKey => $gimpArrVal)
                        if (!isset($tempArr[$gimpArrKey]))
                            $tempArr[$gimpArrKey] = $gimpArrVal;
                    $gimp[$domKey] = $tempArr;
                }
                else {
                    $gimp[$domKey] = $domVal;
                }
            } else {
                $gimp[$domKey] = $domVal;
            }
        }
    }
    // if the passed value for gimp isn't an array, then return the $dom
    elseif (is_array($dom)) {
        return $dom;
    }

    return $gimp;
}

/**
 * like array_merge() but will handle array elements that are themselves arrays;
 * PHP's version just overwrites the element with the new one.
 *
 * @internal Note that this function deviates from the internal array_merge()
 *           functions in that it does does not treat numeric keys differently
 *           than string keys.  Additionally, it deviates from
 *           array_merge_recursive() by not creating an array when like values
 *           found.
 *
 * @param array gimp the array whose values will be overloaded
 * @param array dom the array whose values will pwn the gimp's
 * @return array beaten gimp
 */
function sugarArrayMerge($gimp, $dom) {
    if (is_array($gimp) && is_array($dom)) {
        foreach ($dom as $domKey => $domVal) {
            if (array_key_exists($domKey, $gimp)) {
                if (is_array($domVal)) {
                    $tempArr = array();
                    foreach ($domVal as $domArrKey => $domArrVal)
                        $tempArr[$domArrKey] = $domArrVal;
                    foreach ($gimp[$domKey] as $gimpArrKey => $gimpArrVal)
                        if (!array_key_exists($gimpArrKey, $tempArr))
                            $tempArr[$gimpArrKey] = $gimpArrVal;
                    $gimp[$domKey] = $tempArr;
                } else {
                    $gimp[$domKey] = $domVal;
                }
            } else {
                $gimp[$domKey] = $domVal;
            }
        }
    }
    // if the passed value for gimp isn't an array, then return the $dom
    elseif (is_array($dom))
        return $dom;

    return $gimp;
}

/**
 * Similiar to sugarArrayMerge except arrays of N depth are merged.
 *
 * @param array gimp the array whose values will be overloaded
 * @param array dom the array whose values will pwn the gimp's
 * @return array beaten gimp
 */
function sugarArrayMergeRecursive($gimp, $dom) {
    if (is_array($gimp) && is_array($dom)) {
        foreach ($dom as $domKey => $domVal) {
            if (array_key_exists($domKey, $gimp)) {
                if (is_array($domVal) && is_array($gimp[$domKey])) {
                    $gimp[$domKey] = sugarArrayMergeRecursive($gimp[$domKey], $domVal);
                } else {
                    $gimp[$domKey] = $domVal;
                }
            } else {
                $gimp[$domKey] = $domVal;
            }
        }
    }
    // if the passed value for gimp isn't an array, then return the $dom
    elseif (is_array($dom))
        return $dom;

    return $gimp;
}



function can_start_session() {
    if (!empty($_GET['PHPSESSID'])) {
        return true;
    }
    $session_id = session_id();
    return empty($session_id) ? true : false;
}

function inDeveloperMode() {
    return isset($GLOBALS['sugar_config']['developerMode']) && $GLOBALS['sugar_config']['developerMode'];
}


/**
 *
 */
function unencodeMultienum($string) {
    if (is_array($string)) {
        return $string;
    }
    if (substr($string, 0, 1) == "^" && substr($string, -1) == "^") {
        $string = substr(substr($string, 1), 0, strlen($string) - 2);
    }

    return explode('^,^', $string);
}

function encodeMultienumValue($arr) {
    if (!is_array($arr))
        return $arr;

    if (empty($arr))
        return "";

    $string = "^" . implode('^,^', $arr) . "^";

    return $string;
}

function cmp_beans($a, $b) {
    global $sugar_web_service_order_by;
    //If the order_by field is not valid, return 0;
    if (empty($sugar_web_service_order_by) || !isset($a->$sugar_web_service_order_by) || !isset($b->$sugar_web_service_order_by)) {
        return 0;
    }
    if (is_object($a->$sugar_web_service_order_by) || is_object($b->$sugar_web_service_order_by) || is_array($a->$sugar_web_service_order_by) || is_array($b->$sugar_web_service_order_by)) {
        return 0;
    }
    if ($a->$sugar_web_service_order_by < $b->$sugar_web_service_order_by) {
        return -1;
    } else {
        return 1;
    }
}

function order_beans($beans, $field_name) {
    //Since php 5.2 doesn't include closures, we must use a global to pass the order field to cmp_beans.
    global $sugar_web_service_order_by;
    $sugar_web_service_order_by = $field_name;
    usort($beans, "cmp_beans");
    return $beans;
}


//check to see if custom utils exists
if (file_exists('custom/include/custom_utils.php')) {
    include_once('custom/include/custom_utils.php');
}

//check to see if custom utils exists in Extension framework
if (file_exists('custom/application/Ext/Utils/custom_utils.ext.php')) {
    include_once('custom/application/Ext/Utils/custom_utils.ext.php');
}


/**
 * get_language_header
 *
 * This is a utility function for 508 Compliance.  It returns the lang=[Current Language] text string used
 * inside the <html> tag.  If no current language is specified, it defaults to lang='en'.
 *
 * @return String The lang=[Current Language] markup to insert into the <html> tag
 */
function get_language_header() {
    return isset($GLOBALS['current_language']) ? "lang='{$GLOBALS['current_language']}'" : "lang='en'";
}

/**
 * get_custom_file_if_exists
 *
 * This function handles the repetitive code we have where we first check if a file exists in the
 * custom directory to determine whether we should load it, require it, include it, etc.  This function returns the
 * path of the custom file if it exists.  It basically checks if custom/{$file} exists and returns this path if so;
 * otherwise it return $file
 *
 * @param $file String of filename to check
 * @return $file String of filename including custom directory if found
 */
function get_custom_file_if_exists($file) {
    return file_exists("custom/{$file}") ? "custom/{$file}" : $file;
}

/**
 * Remove vars marked senstitive from array
 * @param array $defs
 * @param SugarBean|array $data
 * @return mixed $data without sensitive fields
 */
function clean_sensitive_data($defs, $data) {
    foreach ($defs as $field => $def) {
        if (!empty($def['sensitive'])) {
            if (is_array($data)) {
                $data[$field] = '';
            }
            if ($data instanceof SugarBean) {
                $data->$field = '';
            }
        }
    }
    return $data;
}

/**
 * Gets the list of "*type_display*".
 * 
 * @return array
 */
function getTypeDisplayList() {
    return array('record_type_display', 'parent_type_display', 'record_type_display_notes');
}

/**
 * get any Relationship between two modules as raw table rows
 *
 * @param unknown $lhs_module        	
 * @param unknown $rhs_module        	
 * @param string $type        	
 * @return array $rels
 */
function findRelationships($lhs_module, $rhs_module, $name = "", $type = "") {
    global $db;

    $rels = array();
    // copied from Relationship module, but needed to modifiy, if there are more than one relationships of that combination
    $sql = "SELECT * FROM relationships
            WHERE deleted = 0
            AND (
            (lhs_module = '" . $lhs_module . "' AND rhs_module = '" . $rhs_module . "')
            OR
            (lhs_module = '" . $rhs_module . "' AND rhs_module = '" . $lhs_module . "')
            )
            ";
    if (!empty($type)) {
        $sql .= " AND relationship_type = '$type'";
    }
    if (!empty($name)) {
        $sql .= " AND relationship_name = '$name'";
    }
    $result = $db->query($sql, true, " Error searching relationships table...");
    while ($row = $db->fetchByAssoc($result)) {
        $rels [] = $row;
    }
    return $rels;
}


function create_date($year=null,$mnth=null,$day=null)
{
    global $timedate;
    $now = $timedate->getNow();
    if ($day==null) $day=$now->day+mt_rand(0,365);
    return $timedate->asDbDate($now->get_day_begin($day, $mnth, $year));
}

/**
 * Create a short url and save it in the DB.
 *
 * @param $route The route (long url).
 * @param int $active 1 (default) or 0 to indicate if the short url is (still/yet) usable.
 * @return false|string Returns the key of the short url or false in case of unsuccessful creation.
 */
function createShorturl( $route, $active = 1 )
{
    global $db;
    $maxAttempts = 100000;
    $route = $db->quote( $route ); // prevent sql injection
    $active *= 1; // prevent sql injection

    # Generate a random key for the short url and (in the hope of uniqueness) try to save it to the DB.
    # Do a specific number of attempts to get a unused key.
    # Concerning the complicated sql, see: https://stackoverflow.com/questions/3164505/mysql-insert-record-if-not-exists-in-table ("Insert record if not exists in table")
    $attemptCounter = 0;
    do {
        $attemptCounter++;
        $key = generateShorturlKey( 6 );
        $guid = create_guid();
        $result = $db->query( sprintf(
            'INSERT INTO sysshorturls ( id, urlkey, route, active ) SELECT * FROM ( SELECT "%s" AS id, "%s" AS urlkey, "%s" AS route, %d AS active) AS tmp WHERE NOT EXISTS ( SELECT urlkey FROM sysshorturls WHERE urlkey = "%s" ) LIMIT 1',
            $guid, $key, $route, $active, $key ));
    } while( $db->getAffectedRowCount( $result ) === 0 and $attemptCounter < $maxAttempts );

    if ( $attemptCounter === $maxAttempts ) {
        $GLOBALS['log']->fatal('Could not create short url, could not get any free key. Did '.$maxAttempts.' attempts. Last unsuccessful attempt with key "'.$key.'" (and GUID "'.$guid.'").');
        return false;
    }
    else return $key;
}

/**
 * Generate a random key for a short url.
 *
 * @param $length The length of the key. Default is 6.
 * @return string The generated key.
 */
function generateShorturlKey( $length = 6 ) {
    // chars to select from (without specific characters to prevent confusion when reading and retyping the password)
    $LOWERCASE = 'abcdefghijkmnopqrstuvwxyz'; // without "l"!
    $NUMBER = '23456789'; // without "0" and "1"!
    $UPPERCASE = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // without "O" and "I"!
    $charBKT = $UPPERCASE . $LOWERCASE . $NUMBER;

    $key = '';
    for ( $i = 0; $i < $length; $i++ ) {  // loop and create password
        $key = $key . substr( $charBKT, rand() % strlen($charBKT), 1 );
    }
    return $key;
}
