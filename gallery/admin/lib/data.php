<?php
if (!function_exists('json_decode')) {
	include(dirname(__FILE__)  . '/json_compat_decode.php');
}

if (!function_exists('json_encode')) {
	include(dirname(__FILE__)  . '/json_compat_encode.php');
}


class wg_data {

	static public $errors = array();
	static public $connection;

	static public function initDb () {
		$connection = self::connect();

		if(!$connection) {
			die('Unable to connect to database. Mysql said: ' . self::lastError());
		}

		self::$connection = $connection;

		self::query("SET NAMES 'utf8'");
	}

	static public function lastError() {
		$sizeOf = sizeof(self::$errors);
		if($sizeOf == 0) {
			return '';
		}
		$sizeOf = $sizeOf - 1;
		return self::$errors[$sizeOf];
	}


	static public function connect () {
		$link = @mysql_connect(WG_DB_HOST, WG_DB_USER, WG_DB_PASS);
		if (!$link) {
			self::logError();
			echo "Please ensure your database username, password, host name and database name are correct. The database must already exist.";
			die();
		}

		$dbSelected = @mysql_select_db(WG_DB_NAME, $link);
		if (!$dbSelected) {
			self::logError();
			echo "The database '" . htmlentities(WG_DB_NAME) . "' doesn't appear to exist. Please create this database and try again.";
			die();
		}

		return $link;
	}


	static public function query ($query) {
		$query = str_replace(':settings_table', '`' . self::tableName('settings') . '`', $query);
		$query = str_replace(':photos_table', '`' . self::tableName('photos') . '`', $query);
		$query = str_replace(':users_table', '`' . self::tableName('users') . '`', $query);
		$query = str_replace(':authorized_table', '`' . self::tableName('authorized') . '`', $query);
		$query = str_replace(':albums_table', '`' . self::tableName('albums') . '`', $query);

		$queryResult = mysql_query($query);
		if(!$queryResult) {
			self::logError();
			return false;
		}
		return $queryResult;
	}

	static public function row($query) {
		$result = false;

		if(is_string($query)) {
			$result = self::query($query);
		} else if (is_resource($query)) {
			$result = $query;
		}

		if(!$result) {
			return false;
		}

		return mysql_fetch_assoc($result);
	}


	static public function update ($table, $update, $field, $value) {
		$query =  'UPDATE `' . self::tableName($table) . '` SET ';
		$format = array();
		foreach($update as $key => $val) {
			if(is_int($val)) {
				$format[] = '`' . $key . '` = ' . $val . ' ';
			} else {
				$format[] = '`' . $key . '` = "' . $val . '" ';
			}
		}

		$query .= implode (',', $format);

		$query .= ' WHERE `'.$field.'` = "'.$value.'"';

		$result = wg_data::query($query);

		return $result;
	}

	static public function updateUser ($userId, $username, $password) {
		if((int)$userId <= 0) {
			return false;
		}

		$sql = 'UPDATE :users_table SET ';
		$sql .= '`username` = "' . self::escape($username) . '"';
		if($password !== false) {
			$sql .= ',`password` = "' . wg_user::hashPass($password) . '"';
		}
		$sql .= ' where `id` = ' . (int)$userId;

		return self::query($sql);
	}

	static public function deleteAlbum ($albumId) {
		if((int)$albumId <= 0) {
			return false;
		}

		$sql = 'DELETE FROM `' . self::tableName('albums') . '`';
		$sql .= ' where `id` = ' . (int)$albumId;

		if(self::query($sql)) {
			$sql = 'DELETE FROM `' . self::tableName('photos') . '`';
			$sql .= ' where `albumid` = ' . (int)$albumId;
			return self::query($sql);
		}

		return false;
	}


	static public function deleteUser ($userId) {
		if((int)$userId <= 0) {
			return false;
		}

		$sql = 'DELETE FROM :authorized_table';
		$sql .= ' where `id` = ' . $userId;

		$return = self::query($sql);
		return $return;
	}


	static public function deleteImage ($imageid) {
		if((int)$imageid <= 0) {
			return false;
		}

		$sql = 'DELETE FROM :photos_table';
		$sql .= ' where `id` = ' . $imageid;

		$return = self::query($sql);
		return $return;
	}

	static public function updateAlbumOrder ($albumId, $orderNumber) {
		if((int)$albumId <= 0) {
			return false;
		}

		$sql = 'UPDATE `' . self::tableName('albums') . '`';
		$sql .= ' SET `sortorder` ="' . (int)$orderNumber . '"';
		$sql .= ' where `id` = ' . (int)$albumId;

		return self::query($sql);
	}

	static public function updateImageOrder ($imageId, $orderNumber) {
		if((int)$imageId <= 0) {
			return false;
		}

		$sql = 'UPDATE `' . self::tableName('photos') . '`';
		$sql .= ' SET `position` ="' . (int)$orderNumber . '"';
		$sql .= ' where `id` = ' . (int)$imageId;

		return self::query($sql);
	}

	static public function insert ($table, $insert) {
		$query =  'INSERT INTO `' . wg_data::tableName($table) . '` (`';
		$keys = array_keys($insert);
		$query .= implode('`,`', $keys);
		$query .= '`) VALUES ("';
		$query .= implode('","', $insert);
		$query .= '")';

		$result = wg_data::query($query);

		if($result) {
			return mysql_insert_id();
		}

		return false;
	}

	static public function addUser ($username, $password) {

		$sql = 'INSERT INTO `' . self::tableName('users') . '` (`username`, `password`) ';
		$sql .= ' VALUES (';
		$sql .= '"' . self::escape($username) . '",';
		$sql .= '"' . wg_user::hashPass($password) . '"';
		$sql .= ')';

		return self::query($sql);
	}

	static public function single ($query) {
		return mysql_result(self::query($query), 0, 0);
	}

	static public function escapeAll ($array) {
		foreach($array as $key=>$val) {
			if(!is_int($val)) {
				$array[$key] = self::escape($val);
			}
		}
		return $array;
	}

	static public function escape ($string) {
		if (get_magic_quotes_gpc()) {
			$string = stripslashes($string);
		}
		return mysql_real_escape_string($string);
	}


	static public function tableName ($table) {
		return WG_DB_PREFIX . $table;
	}

	static private function error($error) {
		self::$errors[] = $error;
	}

	static private function logError() {
		self::$errors[] = mysql_error();
	}


}

wg_data::initDb();
