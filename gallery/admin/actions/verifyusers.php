<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$result = array('success' => false);

$users = wg_facebook::verifyAuthorizedUsers();

$result = array(
'authorizedcount' => count($users['authorized']),
'notauthorizedcount' => count($users['notauthorized']),
'notauthorized' => $users['notauthorized'],
'total' => (int)(count($users['notauthorized'])+count($users['authorized'])),
'success' => true
);

echo json_encode($result);
die();