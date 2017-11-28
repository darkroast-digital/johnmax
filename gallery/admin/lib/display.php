<?php

class wg_display {

	private $template;

	public static $albumId = 0;
	public static $currentURL = '';
	public static $current_album = array();
	public static $catresource;
	public static $qresource;
	static public $albums;
	static public $albums_resource;
	static public $display_mode = '';
	private static $counter = 0;

	static public function init() {
		// get current album id
		$albumid = 0;
		if(isset($_GET['wgaid'])) {
			$albumid = (int)$_GET['wgaid'];
			$albumid = max(0, $albumid);
			if($albumid > 0) {
				self::$current_album = wg_data::row('SELECT a.*, p.id as coverphoto, p.imagetype as coverphototype FROM :albums_table as a LEFT JOIN :photos_table as p on a.coverid = p.facebookid WHERE a.id = ' . (int) $albumid .' ORDER BY a.sortorder ASC ');
			}
		}

		self::$albumId = $albumid;

		// get URL
		$qString = $_SERVER['QUERY_STRING'];
		$qString = preg_replace("/[\&]*wgaid=[0-9]*/ism", "", $qString);
		$qString = preg_replace("/&+/", "&", $qString);
		$qString = preg_replace("/^[\?]+/", "", $qString);
		$request = $_SERVER['REQUEST_URI'];

		if(strpos($request, '?') !== false) {
			$requestParts = explode("?", $request);
			$request = $requestParts[0];
		}

		if(strlen($qString) > 0) {
			self::$currentURL = $request . '?' . str_replace('?', '', $qString);
		} else {
			self::$currentURL = $request;
		}
	}
	static public function getURL($add) {
		if(strpos(self::$currentURL, '?') !== false) {
			$url = str_replace("&&", "&", self::$currentURL . $add);
		}else{
			$url = str_replace("&&", "&", self::$currentURL . '?' . $add);
		}
		$url = str_replace("?&", "?", $url);
		$url = str_replace("??", "?", $url);
		$url = preg_replace("/\&$/", "", $url);
		return $url;
	}

	static public function getalbum() {
		$return = wg_data::row(self::$albums_resource);
		if(is_array($return)){
			if(self::$display_mode == 'slider') {
				$return['link'] = self::getURL('&wgsid=' . $return['id']);
			} else {
				$return['link'] = self::getURL('&wgaid=' . $return['id']);
			}
		}
		return $return;
	}

	static public function getalbums() {
		self::$albums_resource = wg_data::query("SELECT a.*,p.id as coverphoto, p.imagetype as coverphototype, p.imageurl_medium as coverphoto_imageurl_medium FROM :albums_table as a LEFT JOIN :photos_table as p on a.coverid = p.id ORDER BY a.sortorder ASC");
	}

	static public $photos;
	static public $photos_resource;

	static public function getphoto() {
		$return = wg_data::row(self::$photos_resource);
	// if(is_array($return)){
	//	$return['link'] = self::getURL('&wgaid=' . $return['id']);
	// }
		return $return;
	}

	static public function getphotos() {
		self::$photos_resource = wg_data::query("SELECT * FROM :photos_table WHERE albumid = " . (int)self::$albumId . " ORDER BY position ASC");
	}




	static public function isPhoto() {
		if(self::$photoId > 0){
			return true;
		}
		return false;
	}

	static public function isAlbum() {
		if(self::$albumId > 0){
			return true;
		}
		return false;
	}

	static public function getCounter() {
		self::$counter++;
		return self::$counter;
	}
}

class wg_display_parse {

	private $vars = array();
	private $html = '';
	public function __construct ($html, $vars) {
		$this->vars = $vars;
		$this->html = $html;
	}

	public function parse () {
		$newHtml = $this->html;
		foreach($this->vars as $name=>$value) {
			$newHtml = str_replace("{%". $name ."}", $value, $newHtml);
		}


		$newHtml = str_replace("{%BASE_PATH}", WG_INSTALL_LOCATION, $newHtml);

		return $newHtml;
	}
}

