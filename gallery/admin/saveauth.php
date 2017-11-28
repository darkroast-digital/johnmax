<?php
include(dirname(__FILE__) . '/access.php');

if(isset($_GET['code']) ){
	$response = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=".wg_facebook::get('app_id')."&redirect_uri=".urlencode(wg_facebook::get('return_url'))."&client_secret=".wg_facebook::get('app_secret')."&code=" . $_GET['code']);

	$params = null;

	parse_str($response, $params);
	$access_token = $params['access_token'];

	$userinfo = "https://graph.facebook.com/me?access_token=" . $access_token;

	$user = json_decode(file_get_contents($userinfo));

	if(wg_facebook::insertUser($user->id, $user->name, @$user->gender, $access_token)) {
		$_SESSION['new_authorized'] = true;
		$_SESSION['new_authorized_id'] = mysql_insert_id();
		header('Location: index.php');
		die();
	}

	echo "An error occurred while trying to authorize this user. " . wg_data::lastError();
}


