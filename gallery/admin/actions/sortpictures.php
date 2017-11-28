<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

foreach($_POST['image_row'] as $key=>$id) {
	$id = (int)$id;
	$queryResult = wg_data::updateImageOrder($id, $key);
	if(!$queryResult) {
		break;
	}
}


$result = array('success' => false);

if($queryResult) {
	$result = array('success' => true);
}

echo json_encode($result);
die();
