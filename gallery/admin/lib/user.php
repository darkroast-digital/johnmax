<?php

class wg_user {

	static public function hashPass ($pass)
	{
		return sha1('~~' . $pass . '{}|');
	}

}