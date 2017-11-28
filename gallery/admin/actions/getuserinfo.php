<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$user = wg_data::row("select * from :users_table where id = " . $_SESSION['user_id']);

$result = array('success' => false);

if(!empty($user)) {
	$result = array('success' => true, 'username' =>$user['username'], 'userid'=>$user['id']);
}

echo json_encode($result);
die();