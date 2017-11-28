<?php

include_once(dirname(__FILE__) . '/json_class.php');

function json_encode ($content) {
	$json = new Services_JSON();
	return $json->encode($content);
}

