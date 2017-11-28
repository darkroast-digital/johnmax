<?php

include_once(dirname(__FILE__) . '/json_class.php');

function json_decode ($content, $assoc=false) {
	//	json_decode support for PHP < 5.2
	if ($assoc) {
		$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
	} else {
		$json = new Services_JSON();
	}
	return $json->decode($content);
}
