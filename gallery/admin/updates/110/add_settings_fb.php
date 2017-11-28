<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }
// check if needs to be completed

$fb_like = wg_settings::update('settings', array('value' => 'on'), 'name', 'fb_like');

if(!$fb_like) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}


$fb_comment = wg_settings::update('settings', array('value' => 'on'), 'name', 'fb_comment');

if(!$fb_comment) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}
