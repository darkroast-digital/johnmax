<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$image = wg_data::row("select * from `" . wg_data::tableName('photos') . "` where id = '".(int)$_POST['coverid']."' ");

// add album to the database
$updateAlbum = array(
	'coverid' => (int)$_POST['coverid'],
);

$resultAlbum = wg_data::update('albums', $updateAlbum, 'id', (int)$image['albumid']);

if(!$resultAlbum) {
	$result =  array('success' => false, 'error' => wg_lang::out('AlbumDbError') . wg_data::lastError());
	echo json_encode($result);
	die();
}

$result =  array('success' => true);
echo json_encode($result);
die();