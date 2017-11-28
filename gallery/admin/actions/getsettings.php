<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$settings = wg_settings::getSettings();

$result = array('success' => false);

if(!empty($settings)) {
	$result = $settings;
	$result['success'] = true;
}

echo json_encode($result);
die();