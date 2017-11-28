<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$userId = (int)$_POST['userid'];

if($userId <= 0) {
	$result = array('success' => false);
	echo json_encode($result);
	die();
}

$result = array('success' => false);

if(wg_data::deleteUser($userId)) {
		$result = array('success' => true, 'id' =>$userId);

		$query = wg_data::query("select * from :albums_table WHERE userid = " . (int)$userId);

		while($row = wg_data::row($query)) {
			if(wg_data::deleteAlbum($row['id'])) {
				// delete all the images
				wg_album::delete_directory(WG_PHOTOS_FOLDER . '/' . $row['id']. '/');
			}
		}

}

echo json_encode($result);
die();