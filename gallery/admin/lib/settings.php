<?php

class wg_settings {

	private static $settings = false;

	private static function init() {
		$sql = wg_data::query('select * from :settings_table');
		while($row = wg_data::row($sql)) {
			if(!is_array(self::$settings)) {
				self::$settings = array();
			}
			self::$settings[$row['name']] = $row['value'];
		}
	}

	static public function get ($var) {
		self::checkInit();
		if(isset(self::$settings[$var])) {
			return self::$settings[$var];
		}
		return false;
	}

	static public function checkInit() {
		if (self::$settings == false) {
			self::init();
		}
	}

	static public function getSettings() {
		self::checkInit();

		if(!self::$settings) {
			return array();
		}

		return self::$settings;
	}

	static public function hasAppSecret() {
		self::checkInit();

		if(isset(self::$settings['app_secret']) && !empty(self::$settings['app_secret'])) {
			return true;
		}

		return false;
	}

	static public function hasAppId() {
		self::checkInit();

		if(isset(self::$settings['app_id']) && !empty(self::$settings['app_id'])) {
			return true;
		}

		return false;
	}

	static public function update ($table, $update, $field, $value) {

		$count = wg_data::row('select count(id) as row_count FROM `' . wg_data::tableName($table) . '` WHERE `'.$field.'` = "'.wg_data::escape($value).'"');

		if($count['row_count'] > 0) {
			$query =  'UPDATE `' . wg_data::tableName($table) . '` SET ';
			$format = array();
			foreach($update as $key => $val) {
				if(is_int($val)) {
					$format[] = '`' . $key . '` = ' . $val . ' ';
				} else {
					$format[] = '`' . $key . '` = "' . $val . '" ';
				}
			}

			$query .= implode (',', $format);

			$query .= ' WHERE `'.$field.'` = "'.wg_data::escape($value).'"';

			$result = wg_data::query($query);

		} else {
			$update[$field] = $value;
			$result = wg_settings::insert($table, $update);
		}
		return $result;
	}

	static public function determineInstallLocation () {
		$protocol = 'http';
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
			$protocol .= 's';
		}
		$protocol .= '://';
		$host_parts = explode(":", $_SERVER['HTTP_HOST']);
		if(isset($host_parts[1]) && $host_parts[1] == "80") {
			$host = $host_parts[0];
		} else {
			$host = $_SERVER['HTTP_HOST'];
		}
		
		$request_parts = explode("?", $_SERVER['REQUEST_URI']);
		$path = $request_parts[0];

		if(substr($path, 0, 1) != "/") {
			$path = '/' . $path;
		}


		if(substr($path, -9) == "index.php") {
			$path = substr($path, 0, -9);
		}

		if(substr($path, -10) == "action.php") {
			$path = substr($path, 0, -10);
		}

		if(substr($path, -1) != "/") {
			$path .= '/';
		}

		if(substr($path, -6) == "admin/") {
			$path = substr($path, 0, -6);
		}

		if(substr($path, -1) != "/") {
			$path .= '/';
		}

		return $protocol . $host . $path;

	}


	static public function insert ($table, $insert) {
		$query =  'INSERT INTO `' . wg_data::tableName($table) . '` (`';
		$keys = array_keys($insert);
		$query .= implode('`,`', $keys);
		$query .= '`) VALUES ("';
		$query .= implode('","', $insert);
		$query .= '")';

		$result = wg_data::query($query);

		return $result;
	}


}