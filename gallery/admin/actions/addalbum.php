<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }



// add album to the database
$updateAlbum = array(
	'name' => $_POST['name'],
	'desc' => $_POST['desc'],
	'usedesc' => 'self',
	'uselocation' => 'self',
	'usename' => 'self',
	'location' => $_POST['location']
);

$updateAlbum = wg_data::escapeAll($updateAlbum);
$resultAlbum = wg_data::insert('albums', $updateAlbum);

if(!$resultAlbum) {
	$result =  array('success' => false, 'error' => wg_lang::out('AlbumDbError') . wg_data::lastError());
	echo json_encode($result);
	die();
}

$result =  array('success' => true, 'album_id' => $resultAlbum, 'album_name' => $_POST['name']);
echo json_encode($result);
die();