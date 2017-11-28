<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$image = wg_data::row("select * from :photos_table where id = " . (int)$_POST['imageid']);

$result = array('success' => false);

if(!empty($image)) {
	$result = $image;
	$result['success'] = true;

	if(wg_settings::get('photo_source') == 'server' || empty($image['facebookid']))
	{
		$albumDir = WG_INSTALL_LOCATION . 'photos/' . $image['albumid'];
		$result['fullsizepath'] = $albumDir . '/' . $image['id'] . '.' . $image['imagetype'];
	}
	else
	{
		$result['fullsizepath'] = $image['imageurl_fullsize'];
	}


}

echo json_encode($result);
die();