<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }
// check if needs to be completed

$new_images_pos = wg_settings::update('settings', array('value' => 'end'), 'name', 'new_images_pos');

if(!$new_images_pos) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

