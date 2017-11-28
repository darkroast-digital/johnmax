<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }
include(dirname(__FILE__) . '/imageupload_function.php');

$_POST['album_id'] = $_POST['a_album_list'];

foreach($_FILES['image_upload_field']['name'] as $key => $val) {
	if(empty($_FILES["image_upload_field"]["tmp_name"][$key])) {
		continue;
	}

	$_FILES['Filedata'] =  array();
	$_FILES['Filedata']['name'] = $_FILES["image_upload_field"]["name"][$key];
	$_FILES['Filedata']['type'] = $_FILES["image_upload_field"]["type"][$key];
	$_FILES['Filedata']['size'] = $_FILES["image_upload_field"]["size"][$key];
	$_FILES['Filedata']['tmp_name'] = $_FILES["image_upload_field"]["tmp_name"][$key];
	$_FILES['Filedata']['error'] = $_FILES["image_upload_field"]["error"][$key];

	ob_start();
	upload_image();
	ob_end_clean();

}

$result =  array('success' => true, 'album_id' => (int)$_POST['a_album_list'], 'album_name' => $albumName);
echo json_encode($result);