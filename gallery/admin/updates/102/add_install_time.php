<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }
// check if needs to be completed

$install_time = wg_settings::update('settings', array('value' => time()), 'name', 'install_time');

if(!$install_time) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$rate_popup = wg_settings::update('settings', array('value' => 'no'), 'name', 'rate_popup');

if(!$rate_popup) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}
