<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$result = array('success' => false);
$startVersion = (int)wg_settings::get('dbversion');
$updatesDir = WG_ADMIN_DIR . '/updates';
$updatesToApply = array();

foreach(wg_update::$updates as $version => $update) {
	if($version > $startVersion) {
		foreach($update as $file) {
			$filePath = $updatesDir . '/' . $version .'/' . $file . '.php';
			$filePath = str_replace('..','', $filePath);
			if(is_file($filePath)) {
				include_once($filePath);
			}
		}
	}
}

$dbversion = wg_settings::update('settings', array('value' => WG_DATABASE_VERSION), 'name', 'dbversion');

if(!$dbversion) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$scriptversion = wg_settings::update('settings', array('value' => WG_SCRIPT_VERSION), 'name', 'scriptversion');

if(!$scriptversion) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$result = array('success' => true);

echo json_encode($result);
die();
