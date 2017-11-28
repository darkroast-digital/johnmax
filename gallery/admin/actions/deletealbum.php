<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }
$albumid = (int)$_POST['albumid'];

if($albumid <= 0) {
	$result = array('success' => false);
	echo json_encode($result);
	die();
}

$result = array('success' => false);

if(wg_data::deleteAlbum($albumid)) {
	// delete all the images
	if(is_dir(WG_PHOTOS_FOLDER . '/' . $albumid )) {
		wg_album::delete_directory(WG_PHOTOS_FOLDER . '/' . $albumid . '/');
	}
	$result = array('success' => true, 'id' =>(int)$_POST['albumid']);
}

echo json_encode($result);
die();