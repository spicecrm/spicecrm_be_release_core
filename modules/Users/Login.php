<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

/*********************************************************************************

 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/** @var AuthenticationController $authController */
$authController->authController->pre_login();

global $current_language, $mod_strings, $app_strings;
if(isset($_REQUEST['login_language'])){
    $lang = $_REQUEST['login_language'];
    $_REQUEST['ck_login_language_20'] = $lang;
	$current_language = $lang;
    $_SESSION['authenticated_user_language'] = $lang;
    $mod_strings = return_module_language($lang, "Users");
    $app_strings = return_application_language($lang);
}
$sugar_smarty = new Sugar_Smarty();
echo '<link rel="stylesheet" type="text/css" media="all" href="'.getJSPath('modules/Users/login.css').'">';

// SpiceTheme include custom login css
if(file_exists('themes/'.$GLOBALS['sugar_config']['default_theme'] . '/login/login.css'))
	echo '<link rel="stylesheet" type="text/css" media="all" href="themes/'.$GLOBALS['sugar_config']['default_theme'] . '/login/login.css">';
// end SpiceTheme

echo '<script type="text/javascript" src="'.getJSPath('modules/Users/login.js').'"></script>';
global $app_language, $sugar_config;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;

// Get the login page image
if ( sugar_is_file('custom/themes/default/images/company_logo.png') ) {
    $size = getimagesize('custom/themes/default/images/company_logo.png');
    $width = $size[0];
    $height= $size[1];
    if($size[0] > 400){
        $width = 400;
        $height = round($size[1] * ($width/$size[0]), 0);
    }
    $login_image = '<IMG src="custom/themes/default/images/company_logo.png" alt="Sugar" width="'.$width.'" height="'.$height.'">';
}
elseif ( sugar_is_file('custom/include/images/sugar_md.png') ) {
    $login_image = '<IMG src="custom/include/images/sugar_md.png" alt="Sugar" width="340" height="25">';
}
else {
    $login_image = '<IMG src="themes/SpiceTheme/images/spice_md.png" alt="Sugar" width="418" style="margin: 5px 0;">';
}
$sugar_smarty->assign('LOGIN_IMAGE',$login_image);

//Check if Rewrite is working for KREST
global $sugar_config;
$options = array(
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,         // return web page
    CURLOPT_HEADER => false,        // don't return headers
    CURLOPT_VERBOSE => 1                //
);
$url = $sugar_config['site_url'];
if (substr($sugar_config['site_url'], -1) === "/") {
    $url .= "KREST/sysinfo";
} else {
    $url .= "/KREST/sysinfo";
}
$ch = curl_init($url);
curl_setopt_array($ch, $options);
$content = curl_exec($ch);
$err = curl_errno($ch);
$errmsg = curl_error($ch);
$header = curl_getinfo($ch);
curl_close($ch);

$header['errno'] = $err;
$header['errmsg'] = $errmsg;
$header['content'] = $content;
$res = json_decode($content);
if(!is_object($res) || empty($res->version)){
    $sugar_smarty->assign('REWRITE',false);
    $sugar_smarty->assign('REWRITE_ERROR','<h2 style="color:red">Url rewrite is not working. Please ensure you have enabled and configured url rewriting on this webserver.</h2>');
}else{
    $sugar_smarty->assign('REWRITE',true);
}

// See if any messages were passed along to display to the user.
if(isset($_COOKIE['loginErrorMessage'])) {
    if ( !isset($_REQUEST['loginErrorMessage']) ) {
        $_REQUEST['loginErrorMessage'] = $_COOKIE['loginErrorMessage'];
    }
    SugarApplication::setCookie('loginErrorMessage', '', time()-42000, '/');
}
if(isset($_REQUEST['loginErrorMessage'])) {
    if (isset($mod_strings[$_REQUEST['loginErrorMessage']])) {
        echo "<p align='center' class='error' > ". $mod_strings[$_REQUEST['loginErrorMessage']]. "</p>";
    } else if (isset($app_strings[$_REQUEST['loginErrorMessage']])) {
        echo "<p align='center' class='error' > ". $app_strings[$_REQUEST['loginErrorMessage']]. "</p>";
    }
}

$lvars = $GLOBALS['app']->getLoginVars();
$sugar_smarty->assign("LOGIN_VARS", $lvars);
foreach($lvars as $k => $v) {
    $sugar_smarty->assign(strtoupper($k), $v);
}

// Retrieve username from the session if possible.
if(isset($_SESSION["login_user_name"])) {
	if (isset($_REQUEST['default_user_name']))
		$login_user_name = $_REQUEST['default_user_name'];
	else
		$login_user_name = $_SESSION['login_user_name'];
} else {
	if(isset($_REQUEST['default_user_name'])) {
		$login_user_name = $_REQUEST['default_user_name'];
	} elseif(isset($_REQUEST['ck_login_id_20'])) {
		$login_user_name = get_user_name($_REQUEST['ck_login_id_20']);
	} else {
		$login_user_name = $sugar_config['default_user_name'];
	}
	$_SESSION['login_user_name'] = $login_user_name;
}
$sugar_smarty->assign('LOGIN_USER_NAME', $login_user_name);

$mod_strings['VLD_ERROR'] = $GLOBALS['app_strings']["\x4c\x4f\x47\x49\x4e\x5f\x4c\x4f\x47\x4f\x5f\x45\x52\x52\x4f\x52"];

// Retrieve password from the session if possible.
if(isset($_SESSION["login_password"])) {
	$login_password = $_SESSION['login_password'];
} else {
	$login_password = $sugar_config['default_password'];
	$_SESSION['login_password'] = $login_password;
}
$sugar_smarty->assign('LOGIN_PASSWORD', $login_password);

if(isset($_SESSION["login_error"])) {
	$sugar_smarty->assign('LOGIN_ERROR', $_SESSION['login_error']);
}
if(isset($_SESSION["waiting_error"])) {
    $sugar_smarty->assign('WAITING_ERROR', $_SESSION['waiting_error']);
}

if(isset($GLOBALS['kdeploymentmw_now']) && $GLOBALS['kdeploymentmw_now']['disable_login'] > 0){
    global $timedate;
    $admin = new User();
    $admin->retrieve('1');
    $to_date = $timedate->fromDb($GLOBALS['kdeploymentmw_now']['to_date']);
    $sugar_smarty->assign('MAINTEN_ERROR', "System is currently in Deployment Maintenance Window till ".$timedate->asUser($to_date,$admin)." !");
}
if(isset($GLOBALS['kdeploymentmw_near']) && $GLOBALS['kdeploymentmw_near']['disable_login'] > 0){
    global $timedate;
    $admin = new User();
    $admin->retrieve('1');
    $to_date = $timedate->fromDb($GLOBALS['kdeploymentmw_near']['to_date']);
    $from_date = $timedate->fromDb($GLOBALS['kdeploymentmw_near']['from_date']);
    $sugar_smarty->assign('MAINTEN_ERROR', "Deployment Maintenance Window from ".$timedate->asUser($from_date,$admin)." till ".$timedate->asUser($to_date,$admin)." !");
}

if (isset($_REQUEST['ck_login_language_20'])) {
	$display_language = $_REQUEST['ck_login_language_20'];
} else {
	$display_language = $sugar_config['default_language'];
}

if (empty($GLOBALS['sugar_config']['passwordsetting']['forgotpasswordON']))
	$sugar_smarty->assign('DISPLAY_FORGOT_PASSWORD_FEATURE','none');

$the_languages = get_languages();
if ( count($the_languages) > 1 )
    $sugar_smarty->assign('SELECT_LANGUAGE', get_select_options_with_id($the_languages, $display_language));
$the_themes = SugarThemeRegistry::availableThemes();
if ( !empty($logindisplay) )
	$sugar_smarty->assign('LOGIN_DISPLAY', $logindisplay);;

// RECAPTCHA

	$admin = new Administration();
	$admin->retrieveSettings('captcha');
	$captcha_privatekey = "";
	$captcha_publickey="";
	$captcha_js = "";
	$Captcha='';

	// if the admin set the captcha stuff, assign javascript and div
	if(isset($admin->settings['captcha_on'])&& $admin->settings['captcha_on']=='1' && !empty($admin->settings['captcha_private_key']) && !empty($admin->settings['captcha_public_key'])){

			$captcha_privatekey = $admin->settings['captcha_private_key'];
			$captcha_publickey = $admin->settings['captcha_public_key'];
			$captcha_js .="<script type='text/javascript' src='" . getJSPath('cache/include/javascript/sugar_grp1_yui.js') . "'></script><script type='text/javascript' src='" . getJSPath('cache/include/javascript/sugar_grp_yui2.js') . "'></script>
			<script type='text/javascript' src='http://api.recaptcha.net/js/recaptcha_ajax.js'></script>
			<script>
			function initCaptcha(){
			Recaptcha.create('$captcha_publickey' ,'captchaImage',{theme:'custom'});
			}
			window.onload=initCaptcha;

			var handleFailure=handleSuccess;
			var handleSuccess = function(o){
				if(o.responseText!==undefined && o.responseText =='Success'){
					generatepwd();
					Recaptcha.reload();
				}
				else{
					if(o.responseText!='')
						document.getElementById('generate_success').innerHTML =o.responseText;
					Recaptcha.reload();
				}
			}
			var callback2 ={ success:handleSuccess, failure: handleFailure };

			function validateAndSubmit(){
					var form = document.getElementById('form');
					var url = '&to_pdf=1&module=Home&action=index&entryPoint=Changenewpassword&recaptcha_challenge_field='+Recaptcha.get_challenge()+'&recaptcha_response_field='+ Recaptcha.get_response();
					YAHOO.util.Connect.asyncRequest('POST','index.php',callback2,url);
			}</script>";
		$Captcha.="<tr>
			<td scope='row' width='20%'>".$mod_strings['LBL_RECAPTCHA_INSTRUCTION'].":</td>
		    <td width='70%'><input type='text' size='26' id='recaptcha_response_field' value=''></td>

		</tr>
		<tr>

		 	<td colspan='2'><div style='margin-left:2px'class='x-sqs-list' id='recaptcha_image'></div></td>
		</tr>
		<tr>
			<td colspan='2' align='right'><a href='javascript:Recaptcha.reload()'>".$mod_strings['LBL_RECAPTCHA_NEW_CAPTCHA']."</a>&nbsp;&nbsp;
			 		<a class='recaptcha_only_if_image' href='javascript:Recaptcha.switch_type(\"audio\")'>".$mod_strings['LBL_RECAPTCHA_SOUND']."</a>
			 		<a class='recaptcha_only_if_audio' href='javascript:Recaptcha.switch_type(\"image\")'> ".$mod_strings['LBL_RECAPTCHA_IMAGE']."</a>
		 	</td>
		</tr>";
		$sugar_smarty->assign('CAPTCHA', $Captcha);
		echo $captcha_js;

	}else{
		echo "<script>
		function validateAndSubmit(){generatepwd();}
		</script>";
	}

$sugar_smarty->display('modules/Users/login.tpl');