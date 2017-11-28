<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }


set_time_limit(0);
// images at 6MB will need a lot of memory when using GD to resize
ini_set('memory_limit', '128M');

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

function upload_image() {
$result = array('success' => true);

$album_id = (int)$_POST['album_id'];
$needsCover = false;

if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
	$result =  array('success' => false, 'error' => wg_lang::get('InvalidUpload'));
	echo json_encode($result);
	die();
}

$albumName = '';

if($album_id < 1) {
	$isNewAlbum = true;
	$albumName = trim(@$_POST['album_name']);
	// add album to the database
	$insertAlbum = array(
		'name' => $albumName,
		'added' => '',
		'updated' => '',
		'facebookid' => '',
		'coverid' => '',
		'desc' => @$_POST['album_desc'],
		'location' => @$_POST['album_location'],
		'userid' => ''
	);

	$insertAlbum = wg_data::escapeAll($insertAlbum);

	$addAlbum = wg_data::insert('albums', $insertAlbum);

	if(!$addAlbum) {
		$result =  array('success' => false, 'error' => wg_lang::get('AlbumDbError') . wg_data::lastError());
		echo json_encode($result);
		die();
	}

	$album_id = $addAlbum;

	if(empty($albumName)) {
		$albumName = wg_lang::get('DefaultAlbumName', array('album_number' => $album_id));
		$updateAlbum = array(
			'name' => $albumName,
		);

		$updateAlbum = wg_data::escapeAll($updateAlbum);
		$updateResult = wg_data::update('albums', $updateAlbum, 'id', $album_id);
	}


	$albumDir = WG_PHOTOS_FOLDER . '/' . $album_id;
	$needsCover = true;
} else {
	$isNewAlbum = false;
	$albumInfo = wg_data::row("select * from :albums_table where id = " . (int)$_POST['album_id']);
	if(empty($albumInfo['coverid'])) {
		$needsCover = true;
	}
}

$albumDir = WG_PHOTOS_FOLDER . '/' . $album_id;
if(!is_dir($albumDir)) {
	@mkdir($albumDir, 0777);
}
$size = $_FILES["Filedata"]["size"];

$image_file_type = strtolower(substr(strrchr($_FILES["Filedata"]['name'], "."), 1));
$imagetype = false;

switch($image_file_type) {
	case 'png':
		$imagetype = 'png';
		break;
	case 'jpg':
	case 'jpeg':
		$imagetype = 'jpg';
		break;
	case 'gif':
		$imagetype = 'gif';
		break;
	default:
		$imagetype = false;
		break;
}

//contents check
$contents = file_get_contents($_FILES["Filedata"]["tmp_name"]);
if(strpos('<?php', $contents) !== false || strpos('<script', $contents) !== false) {
	$imagetype = false;
}


if (!$imagetype) {
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadFileTypeUnknown'));
	echo json_encode($result);
	die();
}



// Get the image and create a thumbnail
switch($imagetype) {
	case 'jpg':
		$img = imagecreatefromjpeg($_FILES["Filedata"]["tmp_name"]);
		break;
	case 'png':
		$img = imagecreatefrompng($_FILES["Filedata"]["tmp_name"]);
		break;
	case 'gif':
		if(!function_exists('imagecreatefromgif')) {
			$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadNoGifSupport'));
			echo json_encode($result);
			die();
		}else {
			$img = imagecreatefromgif($_FILES["Filedata"]["tmp_name"]);
		}
		break;
}


if ($size < 2) {
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadFileEmpty'));
	echo json_encode($result);
	die();
}



if (!$img) {
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadHandleError'));
	echo json_encode($result);
	die();
}

$width = imageSX($img);
$height = imageSY($img);

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


$position = 0;

if(!$isNewAlbum) {
	// we need to work out whether to add the photos to the end or start of the album
	$settings = wg_settings::getSettings();
	if($settings['new_images_pos'] != 'end') {
		// add to the start
		$first_photo = wg_data::row("select position from :photos_table where albumid = " . (int)$album_id . ' ORDER BY position ASC LIMIT 1');
		$position = $first_photo['position'] - 1;
	} else {
		// default to the end
		$last_photo = wg_data::row("select position from :photos_table where albumid = " . (int)$album_id . ' ORDER BY position DESC LIMIT 1');
		$position = $last_photo['position'] + 1;
	}
}


$insertPhoto = array(
	'albumid' => $album_id,
	'name' => '',
	'position' => $position,
	'facebookname' => '',
	'facebookposition' => 0,
	'added' => '',
	'updated' => '',
	'facebookid' => '',
	'facebooklink' => '',
	'imagetype' => $imagetype,
	'height' => (int)$full_height,
	'width' => (int)$full_width,
	'thumbheight' => (int)$thumb_height,
	'thumbwidth' => (int)$thumb_width,
	'imageurl_thumbnail' => '',
	'imageurl_fullsize' => '',
	'imageurl_medium' => '',
);

$insertPhoto = wg_data::escapeAll($insertPhoto);

$addPhoto = wg_data::insert('photos', $insertPhoto);

if(!$addPhoto) {
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadDbError') . $addPhoto . wg_data::lastError());
	echo json_encode($result);
	die();
}

$photo_id = $addPhoto;

move_uploaded_file($_FILES["Filedata"]["tmp_name"], $albumDir . '/' . $photo_id . '_orig.' . $imagetype);

// ------------------------------------------------------------
// Build the fullsize  ----------------------------------------
// ------------------------------------------------------------

$fullsize_img = ImageCreateTrueColor($full_width, $full_height);
if (!@imagefilledrectangle($fullsize_img, 0, 0, $full_width-1, $full_height-1, 0)) {     // Fill the image black
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadGenerate'));
	echo json_encode($result);
	die();
}

if (!@imagecopyresampled($fullsize_img, $img, 0,0, 0, 0, $full_width, $full_height, $width, $height)) {
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
file_put_contents($albumDir . '/' . $photo_id . '.'. $imagetype, $fullsize_img_file);

// ------------------------------------------------------------
// Build the thumbnail ----------------------------------------
// ------------------------------------------------------------

$thumb_img = ImageCreateTrueColor($thumb_width, $thumb_height);
if (!@imagefilledrectangle($thumb_img, 0, 0, $thumb_width-1, $thumb_height-1, 0)) {     // Fill the image black
	$result =  array('success' => false, 'error' => wg_lang::get('ImageUploadGenerate'));
	echo json_encode($result);
	die();
}

if (!@imagecopyresampled($thumb_img, $img, 0,0, 0, 0, $thumb_width, $thumb_height, $width, $height)) {
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
file_put_contents($albumDir . '/' . $photo_id . '_thumbnail.'. $imagetype, $thumb_img_file);

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

if (!@imagecopyresampled($medium_img, $img, 0,0, 0, 0, $new_width, $new_height, $width, $height)) {
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
file_put_contents($albumDir . '/' . $photo_id . '_medium.'. $imagetype, $medium_img_file);

if($needsCover) {
	$updateAlbum = array(
		'coverid' => $addPhoto
	);

	$updateAlbum = wg_data::escapeAll($updateAlbum);
	$resultAlbum = wg_data::update('albums', $updateAlbum, 'id', $album_id);
}


$result =  array('success' => true, 'album_id' => $album_id, 'album_name' => $albumName);
echo json_encode($result);

}