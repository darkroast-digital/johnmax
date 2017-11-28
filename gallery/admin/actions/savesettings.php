<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }


if($_POST['photo_source'] == 'server') {
	$photo_source = 'server';
} else {
	$photo_source = 'facebook';
}

if($_POST['fb_like'] == 'off') {
	$fb_like = 'off';
} else {
	$fb_like = 'on';
}

if($_POST['fb_comment'] == 'off') {
	$fb_comment = 'off';
} else {
	$fb_comment = 'on';
}


if($_POST['new_images_pos'] == 'end') {
	$new_images_pos = 'end';
} else {
	$new_images_pos = 'start';
}

$update_app_secret = wg_settings::update('settings', array('value' => wg_data::escape(trim($_POST['app_id']))), 'name', 'app_id');
$update_app_id = wg_settings::update('settings', array('value' => wg_data::escape(trim($_POST['app_secret']))), 'name', 'app_secret');
$viewgallery = wg_settings::update('settings', array('value' => wg_data::escape(trim($_POST['viewgallery']))), 'name', 'viewgallery');
$fb_like = wg_settings::update('settings', array('value' => wg_data::escape($fb_like)), 'name', 'fb_like');
$fb_comment = wg_settings::update('settings', array('value' => wg_data::escape($fb_comment)), 'name', 'fb_comment');
$new_images_pos = wg_settings::update('settings', array('value' => wg_data::escape($new_images_pos)), 'name', 'new_images_pos');

if(wg_settings::get('photo_source') == 'facebook' && $photo_source == 'server') {
	$_SESSION['update_album_refresh'] = true;
}

$source = wg_settings::update('settings', array('value' => wg_data::escape($photo_source)), 'name', 'photo_source');

if($update_app_secret && $update_app_id && $viewgallery) {
	$result = array('success' => true);
} else {
	$result = array('success' => false, 'error' => wg_lang::out('SettingsUpdateError') . "\n\n" . wg_data::lastError());
}

echo json_encode($result);
die();