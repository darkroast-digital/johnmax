<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }



// update image in the database
$updateImage = array(
	'name' => $_POST['name'],
);

$updateImage = wg_data::escapeAll($updateImage);
$resultImage = wg_data::update('photos', $updateImage, 'id', (int)$_POST['imageid']);

if(!$resultImage) {
	$result =  array('success' => false, 'error' => wg_lang::out('PhotoDbError'). wg_data::lastError());
	echo json_encode($result);
	die();
}

$result =  array('success' => true);
echo json_encode($result);
die();