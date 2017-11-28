<?php
@ignore_user_abort(true);
@set_time_limit(0);
header("Content-Length: 0");
header("Connection: close");
@ob_flush();
@flush();

define ('BASE_DIR', dirname(__FILE__));
define ('WG_REFRESH_TIME', 1800);

include(dirname(__FILE__) . '/admin/common.php');
$_GET = $_COOKIE = $_SESSION = $_POST = array();

$lastRefresh = wg_settings::get('last_refresh');

if(empty($lastRefresh) || ($lastRefresh + WG_REFRESH_TIME) < time()) {

	define('OUTPUT_MODE', false);

	ob_start();
	wg_settings::update('settings', array('value' => time()), 'name', 'last_refresh');
	include_once(dirname(__FILE__) . '/admin/actions/refreshalbums.php');

	ob_end_clean();
}