<?php

if(isset($_POST['PHPSESSID']) && isset($_POST['wg_action']) && $_POST['wg_action'] == 'imageupload' && isset($_SERVER['HTTP_USER_AGENT']) && (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "shockwave flash") !== false || strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "adobe flash") !== false)) {
	session_id($_POST['PHPSESSID']);
}

session_start();

define ('BASE_DIR', dirname(dirname(__FILE__)));

include(dirname(__FILE__) . '/common.php');

// if not logged in display login form
if((!isset($_SESSION['logged_in']) && !isset($_SESSION['install_process']) && !isset($_SESSION['update_process'])) || (isset($_SESSION['install_process']) && $_SESSION['install_process'] !== true)
    || (isset($_SESSION['update_process']) && $_SESSION['update_process'] !== true)
	|| (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] !== true))
{
	echo "Access denied.";
	die();
}



if(!isset($_POST['wg_action'])) {
	die('No action');
}

$validActions = array(
	'addalbum',
	'imageuploadmulti',
	'rotateimage',
	'getsettings',
	'savesettings',
	'saveuserinfo',
	'authorizedlist',
	'getalbums',
	'getalbum',
	'albumlist',
	'sortalbums',
	'getuserinfo',
	'deletealbum',
	'deleteuser',
	'savepage',
	'verifyusers',
	'imageupload',
	'refreshalbums',
	'albuminfo',
	'updatealbum',
	'imagelist',
	'savecoverimage',
	'imageinfo',
	'updateimage',
	'sortpictures',
	'deleteimage',
	'checkfacebook',
);

if(isset($_SESSION['update_process'])) {
	$validActions[] = 'update';
}


if(isset($_SESSION['install_process'])) {
	$validActions[] = 'install';
}

$action = str_replace(array('/', '.', 'php', 'phtml', "\\"), '', $_POST['wg_action']);

if(in_array(strtolower($action), $validActions)) {
	include_once(dirname(__FILE__) . '/actions/' . $action . '.php');
}