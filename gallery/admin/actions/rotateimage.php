<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

set_time_limit(0);
// images at 6MB will need a lot of memory when using GD to resize
ini_set('memory_limit', '256M');
if(!isset($_POST['direction']) || $_POST['direction'] == 'left') {
	$direction = 'left';

} else {
	$direction = 'right';
}


function createimage($gd_img, $imagetype) {
	switch($imagetype) {
		case 'jpg':
			$img = imagejpeg($gd_img);
			break;
		case 'png':
			$img = imagepng($gd_img);
			break;
		case 'gif':
			if(!function_exists('imagegif')) {
				$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadNoGifSupport'));
				echo json_encode($result);
				die();
			}else {
				$img = imagegif($gd_img);
			}
			break;
	}
}


$image = wg_data::row("select * from `" . wg_data::tableName('photos') . "` where id = '".(int)$_POST['imageid']."' ");

	if(wg_settings::get('photo_source') == 'server' || $image['imageurl_medium'] == '')
		{

			$photo_location = WG_PHOTOS_FOLDER . '/' . $image['albumid'] . '/' . $image['id'];

		}
		else
		{
			$result =  array('success' => false, 'error' => wg_lang::get('CantRotateFacebookPicture'));
			echo json_encode($result);
			die();
		}

// Rotate
if($direction == 'left') {
	$degrees = 90;
} else {
	$degrees = 270;
}

$pic = $photo_location . '_orig.' . $image['imagetype'];

switch($image['imagetype']) {
	case 'jpg':
		$img = imagecreatefromjpeg($pic);
		break;
	case 'png':
		$img = imagecreatefrompng($pic);
		break;
	case 'gif':
		if(!function_exists('imagecreatefromgif')) {
			$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadNoGifSupport'));
			echo json_encode($result);
			die();
		}else {
			$img = imagecreatefromgif($pic);
		}
		break;
}

$imagetype = $image['imagetype'];

ob_start();
$rotate = imagerotate($img, $degrees, 0);
createimage($rotate, $image['imagetype']);
$img_file = ob_get_contents();
ob_end_clean();
@imagedestroy($img);

file_put_contents($pic, $img_file);



$width = imageSX($rotate);
$height = imageSY($rotate);

if (!$width || !$height) {
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadInvalidDimensions'));
	echo json_encode($result);
	die();
}


if ($width == $height) {
	$thumb_width = 130;
	$thumb_height = 130;
} elseif ($width > $height) {
	$thumb_width = 130;
	$img_ratio = $height / $width;
	$thumb_height = round((130 * $img_ratio));
} else {
	$thumb_height = 130;
	$img_ratio = $width / $height;
	$thumb_width = round((130 * $img_ratio));
}

if ($width == $height) {
	$full_width = 720;
	$full_height = 720;
} elseif ($width > $height) {
	$full_width = 720;
	$img_ratio = $height / $width;
	$full_height = round((720 * $img_ratio));
} else {
	$full_height = 720;
	$img_ratio = $width / $height;
	$full_width = round((720 * $img_ratio));
}


$fullsize_img = ImageCreateTrueColor($full_width, $full_height);
if (!@imagefilledrectangle($fullsize_img, 0, 0, $full_width-1, $full_height-1, 0)) {     // Fill the image black
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadGenerate'));
	echo json_encode($result);
	die();
}

if (!@imagecopyresampled($fullsize_img, $rotate, 0,0, 0, 0, $full_width, $full_height, $width, $height)) {
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadCantResize'));
	echo json_encode($result);
	die();
}

// Use a output buffering to load the image into a variable
ob_start();
createimage($fullsize_img, $imagetype);
$fullsize_img_file = ob_get_contents();
ob_end_clean();
imagedestroy($fullsize_img);
file_put_contents($photo_location  . '.'. $imagetype, $fullsize_img_file);

// ------------------------------------------------------------
// Build the thumbnail ----------------------------------------
// ------------------------------------------------------------

$thumb_img = ImageCreateTrueColor($thumb_width, $thumb_height);
if (!@imagefilledrectangle($thumb_img, 0, 0, $thumb_width-1, $thumb_height-1, 0)) {     // Fill the image black
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadGenerate'));
	echo json_encode($result);
	die();
}

if (!@imagecopyresampled($thumb_img, $rotate, 0,0, 0, 0, $thumb_width, $thumb_height, $width, $height)) {
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadCantResize'));
	echo json_encode($result);
	die();
}

if (!isset($_SESSION["file_info"])) {
	$_SESSION["file_info"] = array();
}

// Use a output buffering to load the image into a variable
ob_start();
createimage($thumb_img, $imagetype);
$thumb_img_file = ob_get_contents();
ob_end_clean();
imagedestroy($thumb_img);
file_put_contents($photo_location  . '_thumbnail.'. $imagetype, $thumb_img_file);

// ------------------------------------------------------------
// Build the medium -------------------------------------------
// ------------------------------------------------------------

$fullScaleThumbnail = false;

if($fullScaleThumbnail) {
	if ($width == $height) {
		$new_width = 180;
		$new_height = 180;
	} elseif ($width > $height) {
		$new_width = 180;
		$img_ratio = $height / $width;
		$new_height = round((180 * $img_ratio));
	} else {
		$new_height = 180;
		$img_ratio = $width / $height;
		$new_width = round((180 * $img_ratio));
	}
} else {
	$new_width = 180;
	$img_ratio = $height / $width;
	$new_height = round(($new_width * $img_ratio));
}

$medium_img = ImageCreateTrueColor($new_width, $new_height);
if (!@imagefilledrectangle($medium_img, 0, 0, $new_width-1, $new_height-1, 0)) {     // Fill the image black
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadGenerate'));
	echo json_encode($result);
	die();
}

if (!@imagecopyresampled($medium_img, $rotate, 0,0, 0, 0, $new_width, $new_height, $width, $height)) {
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadCantResize'));
	echo json_encode($result);
	die();
}

if (!isset($_SESSION["file_info"])) {
	$_SESSION["file_info"] = array();
}

// Use a output buffering to load the image into a variable
ob_start();
createimage($medium_img,$imagetype);
$medium_img_file = ob_get_contents();
ob_end_clean();
imagedestroy($medium_img);
file_put_contents($photo_location  . '_medium.'. $imagetype, $medium_img_file);

@imagedestroy($rotate);



$imageupdate = wg_data::query("update `" . wg_data::tableName('photos') . "` set height='".$image['width']."',width='".$image['height']."',thumbheight='".$image['thumbwidth']."',thumbwidth='".$image['thumbheight']."' where id = '".(int)$_POST['imageid']."' ");


$result =  array('success' => true, 'imageid' => $image['id'], 'imageurl' =>   WG_INSTALL_LOCATION . 'photos/'.$image['albumid'].'/'.$image['id'].'_medium.' . $image['imagetype'] . "?" . rand(1000,10000) );
echo json_encode($result);
die();