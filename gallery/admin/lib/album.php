<?php

class wg_album {
	static public function delete_directory($dir) {
		$mydir = opendir($dir);
		while(false !== ($file = readdir($mydir))) {
			if($file != "." && $file != "..") {
				@chmod($dir.$file, 0777);
				if(is_dir($dir.$file)) {
					@chdir('.');
					self::delete_directory($dir.$file.'/');
				}
				else{
					@unlink($dir.$file);
				}
			}
		}
		closedir($mydir);
		@rmdir($dir);
	}


}