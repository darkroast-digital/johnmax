<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$result = array('success' => true);

$userinfo = wg_facebook::getUserInfo($_POST['userid']);
$albums = wg_facebook::getAlbums($_POST['userid']);
if(!isset($albums->data) || sizeof($albums->data) < 1) {
	$result = array('albums' => '', 'error'=>wg_lang::get('UserHasNoAlbums'), 'success'=>false);
	echo json_encode($result);
	die();
}

$html = file_get_contents(WG_ADMIN_DIR . '/display/album_list.phtml');
$albumHTML = '';

$batch = array();
$row = array();
$success = false;

foreach($albums->data as $album){
	$id = $album->id;
	$row = wg_data::row("SELECT * FROM :albums_table where `facebookid`= '" . wg_data::escape($id) . "'");

	if(!empty($row)) {
		continue;
	}

	$success = true;

	$descs = array();
	if(isset($album->location) && !empty($album->location)) {
		$descs[] = $album->location;
	}
	if(isset($album->description) && !empty($album->description)) {
		$descs[] = $album->description;
	}

	$row = array(
		'name'=> $album->name,
		'id'=>$album->id,
		'token'=>$userinfo['token'],
		'desc'=> substr(implode('<br/>', $descs), 0, 200),
		);
	$parse = new wg_display_parse($html, $row);
	$albumHTML .= $parse->parse();
}

if(!$success) {
	$result = array('albums' => '', 'error'=> wg_lang::get('UserHasNoAlbums'), 'success'=>false);
	echo json_encode($result);
	die();
}

$result = array('albums' => $albumHTML, 'success'=>true);


echo json_encode($result);
die();