<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

if(
(empty($_POST['password_new']) || empty($_POST['password_new_confirm']) || empty($_POST['password_old']))
&&
(!empty($_POST['password_new']) || !empty($_POST['password_new_confirm']) || !empty($_POST['password_old']))
) {
	$result = array('success' => false, 'error' => wg_lang::out('UpdateUserErrorBlankFields'));
	echo json_encode($result);
	die();
}

if(!empty($_POST['password_new']) && $_POST['password_new'] != $_POST['password_new_confirm'])
{
	$result = array('success' => false, 'error' => wg_lang::out('UpdateUserErrorPasswords'));
	echo json_encode($result);
	die();
}

$pass = false;
if(!empty($_POST['password_new']) && !empty($_POST['password_new_confirm']) && !empty($_POST['password_old'])) {
	$pass = $_POST['password_new'];
}

$user_update = wg_data::updateUser($_SESSION['user_id'], $_POST['username'], $pass);

$result = array('success' => true);

if(!$user_update) {
	$result = array('success' => false, 'error' => wg_lang::out('UpdateUserError') . "\n\n" . wg_data::lastError());
}

echo json_encode($result);
die();