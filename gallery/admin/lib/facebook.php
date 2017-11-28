<?php

class wg_facebook {

	private static $appId = '';
	private static $appSecret = '';
	private static $returnUrl = '';

	public static function init() {
		$settings = wg_settings::getSettings();
		if(is_array($settings) && isset($settings['app_id'])  && isset($settings['app_secret'])) {
			self::$appId = $settings['app_id'];
			self::$appSecret = $settings['app_secret'];
		}

		self::$returnUrl = WG_INSTALL_LOCATION . WG_ADMIN_FOLDER . '/saveauth.php';

	}

	public static function requestAuth() {
		return "https://www.facebook.com/dialog/oauth?client_id=" . self::$appId . "&redirect_uri=" . urlencode(self::$returnUrl) . "&scope=offline_access,user_photos";
	}

	public static function get ($var) {
		if($var == 'app_id') {
			return self::$appId;
		}

		if($var == 'return_url') {
			return self::$returnUrl;
		}

		if($var == 'app_secret') {
			return self::$appSecret;
		}

		return false;
	}

	public static function insertAlbum($name, $created, $updated, $fbid, $cover, $desc, $location) {

		$query =  'INSERT INTO `' . wg_data::tableName('albums') . '` ';
		$query .= '(`name`,`facebookid`, `added`, `updated`, `coverid`, `desc`, `location`) VALUES ';
		$query .= '("'.wg_data::escape($name) .'","'.wg_data::escape($fbid) .'","'.wg_data::escape($created) .'","'.wg_data::escape($updated) .'","'.wg_data::escape($cover) .'","'.wg_data::escape($desc) .'","'.wg_data::escape($location) .'")';

		$result = wg_data::query($query);

		return $result;
	}

	public static function insertUser($fbid, $name, $gender, $access_token) {

		$userinfo = wg_data::row('SELECT * FROM :authorized_table WHERE `facebookid`= "' . wg_data::escape($fbid) . '"');

		if(empty($userinfo)) {

			$newgender = 'u';

			if($gender == 'male') {
				$newgender = 'm';
			} elseif ($gender == 'female') {
				$newgender = 'f';
			} elseif ($gender == 'page') {
				$newgender = 'p';
			}

			$query =  'INSERT INTO `' . wg_data::tableName('authorized') . '` ';
			$query .= '(`name`, `token`, `facebookid`, `gender`) VALUES ';
			$query .= '("'.wg_data::escape($name) .'","'.wg_data::escape($access_token) .'","'.wg_data::escape($fbid) .'","'.wg_data::escape($newgender) .'")';

			$result = wg_data::query($query);

		} else {
			$query =  'UPDATE `' . wg_data::tableName('authorized') . '` ';
			$query .= ' SET `name` = "'.wg_data::escape($name) .'", `token` ="'.wg_data::escape($access_token) .'" WHERE `facebookid`= "' . wg_data::escape($fbid) . '"';
			$result = wg_data::query($query);
		}

		return $result;
	}

	public static function verifyAuthorizedUsers() {

		$users = wg_data::query('SELECT * FROM :authorized_table where gender != "p"');

		$authorized = array();
		$notauthorized = array();

		while($user = wg_data::row($users)) {
			$info = self::getRecord('me', $user['token']);

			// build array of people
			if(isset($info->id) && is_string($info->id)) {
				$authorized[] = $user['id'];
			} else {
				$notauthorized[] = $user['id'];
			}
		}

		return array('authorized' => $authorized, 'notauthorized' => $notauthorized);
	}

	public static function getUserInfo($userid) {
		return wg_data::row('SELECT * FROM :authorized_table WHERE id=' . (int)$userid);
	}

	public static function getRecord($id, $token,$params = false) {
		$addQuery = '';
		if(!empty($params) && is_array($params)) {
			$addQuery = '&' . http_build_query($params);
		}
		$url = "https://graph.facebook.com/".$id."?access_token=" . $token . $addQuery ;
		$return = @json_decode(@file_get_contents($url));
		return $return;
	}

	public static function getAlbums($userid) {
		$userinfo = wg_data::row('SELECT * FROM :authorized_table WHERE id=' . (int)$userid);

		if($userinfo['gender'] == 'p') {
			$url = "https://graph.facebook.com/".$userinfo['facebookid'] ."/albums?limit=500&fields=id,name,cover_photo,location,description,updated_time,created_time";
		}else {
			$url = "https://graph.facebook.com/me/albums?limit=500&fields=id,name,cover_photo,location,description,updated_time,created_time&access_token=" . $userinfo['token'];
		}

		$albums = json_decode(@file_get_contents($url));
		return $albums;
	}

	public static function getBatch ($token, $batch) {
		$url = "https://graph.facebook.com";

		$tokenpart = "access_token=" . $token;

		$newbatch = array ();
		foreach($batch as $batchurl) {
			$batch[] = array ( "method" => "GET", "relative_url" => $batchurl);
		}

		$fields = $tokenpart . "&batch=" . json_encode($batch);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		return json_decode($result);
	}
}

