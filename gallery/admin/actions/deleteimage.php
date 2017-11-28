<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$imageid = (int)$_POST['imageid'];

$result = array('success' => false);

if($imageid <= 0) {

	echo json_encode($result);
	die();
}


$image = wg_data::row("select * from :photos_table where id = " . $imageid);


if(wg_data::deleteImage($imageid)) {
	// delete all the images
		@unlink($dir.$file);
	$albumDir = WG_PHOTOS_FOLDER . '/' . $image['albumid'];
	@unlink($albumDir . '/' . $id . '.' . $image['imagetype']);
	@unlink($albumDir . '/' . $id . '_orig.' . $image['imagetype']);
	@unlink($albumDir . '/' . $id . '_thumbnail.' . $image['imagetype']);
	@unlink($albumDir . '/' . $id . '_medium.' . $image['imagetype']);

	$result = array('success' => true, 'id' =>$imageid);
}

echo json_encode($result);
die();