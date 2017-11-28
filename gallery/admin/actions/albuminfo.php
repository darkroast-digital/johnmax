<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$album = wg_data::row("select * from :albums_table where id = " . (int)$_POST['albumid']);

$result = array('success' => false);

if(!empty($album)) {
	$result = $album;
	$result['success'] = true;
}

echo json_encode($result);
die();