<?php

session_start();

define ('BASE_DIR', dirname(dirname(__FILE__)));
include(dirname(__FILE__) . '/common.php');


$parseArray = array('message' => '');


// Now check that the script is installed2
$checkSettingsTableQuery = "show tables like '" . wg_data::tableName('settings') ."'";
$checkUsersTableQuery = "show tables like '" . wg_data::tableName('users') ."'";

$return = wg_data::row($checkSettingsTableQuery);

$showInstallForm = false;

if(!(is_array($return) && count($return) > 0)) {
	// settings table doesn't exist, show the install form!
	$showInstallForm = true;
}

$return = wg_data::row($checkUsersTableQuery);

if(!(is_array($return) && count($return) > 0)) {
	// users table doesn't exist, show the install form!
	$showInstallForm = true;
}

if($showInstallForm) {
	$_SESSION['install_process'] = true;
	$html = file_get_contents(WG_ADMIN_DIR . '/display/install_form.phtml');
	$jsVars = wg_lang::getJs();
	$js = 'var wg_lang;
	$(document).ready(function() {';
	foreach($jsVars as $name => $value) {
		$js .= "wg_lang['" . $name . "'] = '".str_replace("'", "\\'", $value)."';\n";
	}
	$js .= '});';

	$urlFopen = (bool) ini_get('allow_url_fopen');
	if(!$urlFopen) {
		$js .= "\n$(document).ready(function() { showCriticalError(wg_lang.warning, wg_lang.errorNoUrlFopen); });\n";
	}

	$parseArray['addJs'] = $js;
	$parse = new wg_display_parse($html, $parseArray);
	echo $parse->parse();
	die();
}


if(isset($_SESSION['install_process'])) {
	$_SESSION['install_process'] = false;
	unset($_SESSION['install_process']);
}

if(isset($_SESSION['update_process'])) {
	$_SESSION['update_process'] = false;
	unset($_SESSION['update_process']);
	define('UPDATE_SUCCESS', true);
} else {
	define('UPDATE_SUCCESS', false);
}

if(isset($_SESSION['update_album_refresh'])) {
	if($_SESSION['update_album_refresh']) {
		define('UPDATE_FORCE_ALBUM_REFRESH', true);
	}else {
		define('UPDATE_FORCE_ALBUM_REFRESH', false);
	}
	$_SESSION['update_album_refresh'] = false;
	unset($_SESSION['update_album_refresh']);
} else {
	define('UPDATE_FORCE_ALBUM_REFRESH', false);
}


if(wg_update::updatesAvailable()) {
	$_SESSION['update_process'] = true;
	$_SESSION['update_album_refresh'] = false;
	$parseArray['oldversion'] = wg_update::versionFormat(wg_settings::get('dbversion'));
	$parseArray['newversion'] = wg_update::versionFormat(WG_DATABASE_VERSION);

	$html = file_get_contents(WG_ADMIN_DIR . '/display/update_form.phtml');
	$parse = new wg_display_parse($html, $parseArray);
	echo $parse->parse();
	die();
}


// check if POST action sent for login
if(isset($_POST['submit_login']) && isset($_POST['username']) && isset($_POST['password'])) {
	// user is attempting to log in
	$hashPass = wg_user::hashPass($_POST['password']);

	$user = wg_data::row('SELECT * FROM :users_table WHERE `username` = "' . wg_data::escape($_POST['username']) .'" AND `password` = "'.$hashPass.'"');

	if(!empty($user)) {
		// login successful
		$_SESSION['logged_in'] = true;
		$_SESSION['user_id'] = $user['id'];

		?>
		<script type="text/javascript">
		<!--
			window.location = "index.php?<?php echo rand(5,5000); ?>";
		//-->
		</script>
		<?php
		die();
	} else {
		$parseArray['message'] = 'Sorry, your password and/or username were incorrect.';
	}
}

// if not logged in display login form
if(!isset($_SESSION['logged_in'])
	|| (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !== true))
{
	$html = file_get_contents(WG_ADMIN_DIR . '/display/login_form.phtml');
	$html = str_replace("<head>", "<head>\n\n<!--  Ver: " . WG_SCRIPT_VERSION . " -->\n", $html);
	$parse = new wg_display_parse($html, $parseArray);
	echo $parse->parse();
	die();
}



