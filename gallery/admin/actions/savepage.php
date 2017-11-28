<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }


$pageUrl = $_POST['pageUrl'];

$result = array('success' => false);

if(strpos($pageUrl, 'facebook.com/') === false) {
	$result['invalid'] = true;
	echo json_encode($result);
	die();
}

preg_match("#facebook.com/([^\?/]*)#is", $pageUrl, $match);
$match[1] = trim($match[1]);
if($match[1] == 'pages') {
	preg_match("#facebook.com/pages/[^\?/]*/([^\?/]*)#is", $pageUrl, $match);
}
$match[1] = trim($match[1]);
$user = @json_decode(@file_get_contents("https://graph.facebook.com/".$match[1]));

if(is_object($user) && isset($user->id)) {
	if(wg_facebook::insertUser($user->id, $user->name, 'page', '')) {
		$result = array('success' => true);
	}
}


echo json_encode($result);
die();

