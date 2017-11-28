<?php

if(!defined('BASE_DIR')) { die('Access Denied'); }

define ('WG_DATABASE_VERSION', "123");
define ('WG_SCRIPT_VERSION', "123");
define ('WG_ADMIN_DIR', dirname(__FILE__));
define ('WG_ADMIN_FOLDER', 'admin');
define ('WG_PHOTOS_FOLDER', dirname(dirname(__FILE__)) . '/photos');


require_once(BASE_DIR . '/wg_config.php');
require_once(WG_ADMIN_DIR . '/lib/user.php');
require_once(WG_ADMIN_DIR . '/lib/settings.php');
require_once(WG_ADMIN_DIR . '/lib/facebook.php');
require_once(WG_ADMIN_DIR . '/lib/album.php');
require_once(WG_ADMIN_DIR . '/lib/display.php');
require_once(WG_ADMIN_DIR . '/lib/data.php');
require_once(WG_ADMIN_DIR . '/lib/update.php');
require_once(WG_ADMIN_DIR . '/lib/language.php');

$install_location = wg_settings::get('install_location');

if(empty($install_location)) {
	define ('WG_INSTALL_LOCATION', wg_settings::determineInstallLocation());
} else {
	define ('WG_INSTALL_LOCATION', $install_location);
}
define ('WG_PHOTOS_LOCATION', WG_INSTALL_LOCATION . 'photos');
wg_facebook::init();