<?php

class wg_update {

	static public $updates = array (
		102 => array(
			'add_install_time',
		),
		110 => array(
			'add_settings_fb',
		),
		112 => array(
			'add_settings_position',
		),
	);

	static public function versionFormat($version) {
		$version = (string)$version;

		if(strlen($version) != 3) {
			return $version;
		}

		return substr($version,0, 1) . '.' .substr($version,1, 1) . '.' .substr($version,2, 1);
	}

	static public function updatesAvailable() {
		if(wg_settings::get('dbversion') < WG_DATABASE_VERSION) {
			// need to update
			return true;
		}

		return false;
	}
}