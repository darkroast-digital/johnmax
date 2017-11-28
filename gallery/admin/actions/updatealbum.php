<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }



// add album to the database
$updateAlbum = array(
	'name' => $_POST['name'],
	'desc' => $_POST['desc'],
	'location' => $_POST['location']
);

if($_POST['usename'] == 'yes') {
	$updateAlbum['usename'] = 'facebook';
} else {
	$updateAlbum['usename'] = 'self';
}

if($_POST['usedesc'] == 'yes') {
	$updateAlbum['usedesc'] = 'facebook';
} else {
	$updateAlbum['usedesc'] = 'self';
}

if($_POST['uselocation'] == 'yes') {
	$updateAlbum['uselocation'] = 'facebook';
} else {
	$updateAlbum['uselocation'] = 'self';
}

$updateAlbum = wg_data::escapeAll($updateAlbum);
$resultAlbum = wg_data::update('albums', $updateAlbum, 'id', (int)$_POST['albumid']);

if(!$resultAlbum) {
	$result =  array('success' => false, 'error' => wg_lang::out('AlbumDbError') . wg_data::lastError());
	echo json_encode($result);
	die();
}

$result =  array('success' => true);
echo json_encode($result);
die();