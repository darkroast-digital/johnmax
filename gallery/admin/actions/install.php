<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

if(empty($_POST['username']) || empty($_POST['username'])) {
	$result['error'] = wg_lang::get('InstallEnterUsernameAndPass');
	$result['fullmsg'] = true;
	echo json_encode($result);
	die();
}


function isDirWriteable($dir){
	if(substr($dir, -1) != '/'){ $dir = $dir . '/'; }

	$file = str_replace("//","/", $dir . rand(50000, 1000000). '.txt');

	if(!$fp = @fopen($file, 'w+')){
		return false;
	}

	if(!@fputs($fp, "WRITE TEST")){
		return false;
	}

	if(!@fclose($fp)){
		return false;
	}

	if(!@unlink($file)){
		return false;
	}
	return true;
}

$result = array('success' => false);

if(!isDirWriteable(WG_PHOTOS_FOLDER)) {
	$result['error'] = wg_lang::get('InstallPhotosDirectoryNotWriteable');
	$result['fullmsg'] = true;
	echo json_encode($result);
	die();
}



$queryResult = wg_data::query("CREATE TABLE `" . wg_data::tableName('albums') . "` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
	`facebookname` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
	`added` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
	`updated` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
	`facebookid` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
	`coverid` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
	`desc` text CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
	`location` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
	`sortorder` int(11) NOT NULL,
	`userid` int(11) NOT NULL,
	`facebookdesc` text CHARSET utf8 COLLATE utf8_general_ci  NOT NULL ,
	`facebooklocation` VARCHAR( 255 ) CHARSET utf8 COLLATE utf8_general_ci NOT NULL ,
	`uselocation` VARCHAR( 10 ) CHARSET utf8 COLLATE utf8_general_ci NOT NULL ,
	`usedesc` VARCHAR( 10 ) CHARSET utf8 COLLATE utf8_general_ci NOT NULL ,
	`usename` VARCHAR( 10 ) CHARSET utf8 COLLATE utf8_general_ci NOT NULL,
	KEY `sortorder` (`sortorder`),
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}


$queryResult = wg_data::query("CREATE TABLE `" . wg_data::tableName('authorized') . "` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `token` text CHARSET utf8 COLLATE utf8_general_ci NOT NULL,
  `facebookid` varchar(255) CHARSET utf8 COLLATE utf8_general_ci NOT NULL,
  `gender` varchar(1) CHARSET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult = wg_data::query("CREATE TABLE `" . wg_data::tableName('photos') . "` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `position` int(11) NOT NULL,
  `facebookname` text CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `facebookposition` int(11) NOT NULL,
  `added` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `updated` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `facebookid` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `facebooklink` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `thumbheight` int(11) NOT NULL,
  `thumbwidth` int(11) NOT NULL,
  `albumid` int(11) NOT NULL,
  `imageurl_thumbnail` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `imageurl_fullsize` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `imageurl_medium` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `imagetype` VARCHAR( 4 ) CHARSET utf8 COLLATE utf8_general_ci NOT NULL,
  KEY `albumid` (`albumid`),
  KEY `position` (`position`),
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}


$queryResult = wg_data::query("CREATE TABLE `" . wg_data::tableName('settings') . "` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `value` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  KEY `name` (`name`),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('dbversion', '".WG_DATABASE_VERSION."')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('scriptversion', '".WG_SCRIPT_VERSION."')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}


$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('app_id', '')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('app_secret', '')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('last_refresh', '0')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('viewgallery', '../webgallery.php')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}
$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('install_location', '".WG_INSTALL_LOCATION."')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}



$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('install_time', '".time()."')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('rate_popup', 'no')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('photo_source', 'facebook')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('fb_like', 'on')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::query("INSERT INTO `" . wg_data::tableName('settings') . "` (`name` ,`value`) VALUES ('fb_comment', 'on')");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult = wg_data::query("CREATE TABLE `" . wg_data::tableName('users') . "` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  `password` varchar(255) CHARSET utf8 COLLATE utf8_general_ci  NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$queryResult =  wg_data::addUser($_POST['username'], $_POST['password']);

if(!$queryResult) {
	$result['error'] = mysql_error();
	echo json_encode($result);
	die();
}

$result = array('success' => true);

echo json_encode($result);
die();
