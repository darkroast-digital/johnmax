<?php

if(isset($webgallery_album_id) && (int)$webgallery_album_id > 0) {
	$_GET['wgaid'] = (int)$webgallery_album_id;
}

if(!defined('BASE_DIR')) {
	define ('BASE_DIR', dirname(__FILE__));
}

if(!defined('WG_ADMIN_DIR')) {
	define ('WG_ADMIN_DIR', dirname(__FILE__) . '/admin/');
}

if(!defined('WG_ALBUM_INCLUDE')) {
	define ('WG_ALBUM_INCLUDE', false);
}

@header("Content-Type: text/html; charset=utf-8");

require_once(BASE_DIR . '/wg_config.php');
require_once(WG_ADMIN_DIR . '/lib/user.php');
require_once(WG_ADMIN_DIR . '/lib/settings.php');
require_once(WG_ADMIN_DIR . '/lib/facebook.php');
require_once(WG_ADMIN_DIR . '/lib/album.php');
require_once(WG_ADMIN_DIR . '/lib/display.php');
require_once(WG_ADMIN_DIR . '/lib/data.php');

$install_location = wg_settings::get('install_location');

if(!defined('WG_INSTALL_LOCATION')) {
	if(empty($install_location)) {
		define ('WG_INSTALL_LOCATION', wg_settings::determineInstallLocation());
	} else {
		define ('WG_INSTALL_LOCATION', $install_location);
	}
}

if(!defined('WG_PHOTOS_LOCATION')) {
	define ('WG_PHOTOS_LOCATION', WG_INSTALL_LOCATION . 'photos');
}

$lastRefresh = wg_settings::get('last_refresh');
if(!defined('WG_REFRESH_TIME')) {
	define ('WG_REFRESH_TIME', 1800);
}

if(!defined('WG_AJAX_REFRESH')) {
	if(empty($lastRefresh) || ($lastRefresh + WG_REFRESH_TIME) < time()) {
		define ('WG_AJAX_REFRESH', true);
	} else {
		define ('WG_AJAX_REFRESH', false);
	}
}

wg_display::init();
