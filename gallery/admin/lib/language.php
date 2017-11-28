<?php

class wg_lang {
	private static $vars;
	
	public static function init () {
		self::$vars = parse_ini_file(WG_ADMIN_DIR . '/language/php_html.ini');
	}
	
	public static function out($name, $replace=false) {
		echo self::get($name, $replace);
	}	
	public static function get($name, $replace=false) {
		if(isset(self::$vars[$name])) {
			
			$return = self::$vars[$name];
			if(!empty($replace) && is_array($replace)) {
				foreach($replace as $n => $v) {
					$return = str_replace(':' . $n . ':', $v, $return);
				}
			}
			return $return;
		}
		return "Unknown language variable [".$name."]";
	}
	
	public static function getJs() {
	 	return parse_ini_file(WG_ADMIN_DIR . '/language/js.ini');
	}
}

wg_lang::init();