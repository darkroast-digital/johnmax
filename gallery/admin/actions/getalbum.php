<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

set_time_limit(0);

$result = array('success' => true);

 // pad to force the browser to starting parsing/executing
echo str_pad('<html><head></head><body>'."\n", 4098);
@ob_flush();
@flush();



$count = sizeof($_POST['albumids']);
$growth = 100 / $count;
$totalProgress = 0;
$minorProgress = 0;

function updateProgress($progress, $current) {
echo str_pad("\n".'<script type="text/javascript">
self.parent.$(".dialog #progressbar").progressbar({ value: '.$progress .'});
self.parent.$(".dialog #album-downloaded-count").text('.($current+1).');
</script>'."\n"."\n"."\n"."\n"."\n"."\n", 1024);
@ob_flush();
@flush();
}

function updateStatus($status) {
echo str_pad("\n".'<script type="text/javascript">
self.parent.$(".dialog #current-album-status").text("'.$status.'");
</script>'."\n"."\n"."\n"."\n"."\n"."\n", 1024);
@ob_flush();
@flush();
}


updateStatus(wg_lang::get('DownloadingStatusAlbumInfo'));

foreach($_POST['albumids'] as $key=>$albumid) {

$minorProgress = $totalProgress = $growth * $key;

updateProgress($totalProgress, $key);

// get the user info
$userinfo = wg_facebook::getUserInfo($_POST['userid']);

// get album info from Facebook
$album = wg_facebook::getRecord($albumid, $userinfo['token']);

echo str_pad("\n".'<script type="text/javascript">
self.parent.$(".dialog #current-album-downloading").text("'.addslashes($album->name) .'");
</script>'."\n"."\n"."\n"."\n"."\n"."\n", 1024);
@ob_flush();
@flush();
updateStatus(wg_lang::get('DownloadingStatusSavingAlbum'));

// add album to the database
$insertAlbum = array(
	'name' => $album->name,
	'facebookname' => $album->name,
	'added' => $album->created_time,
	'updated' => $album->updated_time,
	'facebookid' => $album->id,
	'coverid' => '0',
	'facebookdesc' => @$album->description,
	'desc' => @$album->description,
	'location' => @$album->location,
	'facebooklocation' => @$album->location,
	'usename' => 'facebook',
	'usedesc' => 'facebook',
	'uselocation' => 'facebook',
	'userid' => $userinfo['id']
);

$numPhotosOrig=  @$album->count;
$numPhotosOrig = (int)$numPhotosOrig;
$numPhotos = $numPhotosOrig + 2; // add a percentage point for album stuff

$growthPerPhoto = ($growth / ($numPhotos));
/*
echo "<!--
\$growthPerPhoto: ".$growthPerPhoto."
\$numPhotos: " . $numPhotos ."
\$key: " . $key ."
\$growth: " . $growth ."
@\$album->count: ". @$album->count ."
-->";
*/
$insertAlbum = wg_data::escapeAll($insertAlbum);

//$addAlbum = wg_facebook::insertAlbum($album->name, $album->created_time, $album->updated_time, $album->id, $album->cover_photo, @$album->description, @$album->location);

$addAlbum = wg_data::insert('albums', $insertAlbum);

if(!$addAlbum) {
	$result =  array('success' => false, 'error' => "Album Error: " . wg_data::lastError());
	echo json_encode($result);
	die();
}

$minorProgress = $minorProgress + $growthPerPhoto;
updateProgress($minorProgress, $key);


// get photos info
$photos = wg_facebook::getRecord($albumid . '/photos', $userinfo['token'], array('limit' => '600', 'offset' => '0'));

$downloads = array();

updateStatus(wg_lang::get('DownloadingStatusSavingPhotos'));

foreach($photos->data as $photo) {
	$thumbUrl = $photo->picture;
	$medium = '';


	foreach($photo->images as $thisThumb) {
		if($thisThumb->source == $thumbUrl){
			$thumb = $thisThumb;
		}

		if($thisThumb->width > 180 &&(empty($medium) OR $medium->width > $thisThumb->width)) {
            $medium = $thisThumb;
        }
	}

	$imageurl_thumbnail = $photo->picture;
	$imageurl_fullsize = $photo->source;
	$imageurl_medium = $medium->source;

	$insertPhoto = array(
		'albumid' => $addAlbum,
		'name' => @$photo->name,
		'position' => (int)$photo->position,
		'facebookname' => @$photo->name,
		'facebookposition' => (int)$photo->position,
		'imagetype' => 'jpg',
		'added' => $photo->created_time,
		'updated' => $photo->updated_time,
		'facebookid' => $photo->id,
		'facebooklink' => $photo->link,
		'height' => (int)$photo->height,
		'width' => (int)$photo->width,
		'thumbheight' => (int)$thumb->height,
		'thumbwidth' => (int)$thumb->width,
		'imageurl_thumbnail' => $imageurl_thumbnail,
		'imageurl_fullsize' => $imageurl_fullsize,
		'imageurl_medium' => $imageurl_medium,
	);

	$insertPhoto = wg_data::escapeAll($insertPhoto);

	$addPhoto = wg_data::insert('photos', $insertPhoto);

	if($addPhoto !== false && wg_settings::get('photo_source') == 'server') {
		$downloads[$addPhoto] = array('thumbnail' => $thumbUrl, 'image' => $photo->source, 'medium' => $medium->source);
	}

	if(!$addPhoto) {
		$result =  array('success' => false, 'error' => "Photo Error: " . wg_data::lastError());
		echo json_encode($result);
		die();
	}
}

$minorProgress = $minorProgress + $growthPerPhoto;
updateProgress($minorProgress, $key);

$albumDir = WG_PHOTOS_FOLDER . '/' . $addAlbum;

@mkdir($albumDir, 0777);
$countPhoto = 1;
foreach($downloads as $id=>$download) {
	updateStatus(wg_lang::get('DownloadingPhotoProgress', array('current_photo_number'=>$countPhoto, 'total_photo_number'=>$numPhotosOrig)));
	file_put_contents($albumDir . '/' . $id . '.jpg', file_get_contents($download['image']));
	file_put_contents($albumDir . '/' . $id . '_thumbnail.jpg', file_get_contents($download['thumbnail']));
	file_put_contents($albumDir . '/' . $id . '_medium.jpg', file_get_contents($download['medium']));
	$minorProgress = $minorProgress + $growthPerPhoto;
	updateProgress($minorProgress, $key);
	$countPhoto++;
}

//verify cover photo exists, otherwise use first photo
if($insertAlbum['coverid'] == 0){
	$coverPhoto = wg_data::row("select * from :photos_table where albumid=" . $addAlbum . " order by position ASC LIMIT 1");
	$updateAlbum = array(
		'coverid' => $coverPhoto['id']
	);

	$updateAlbum = wg_data::escapeAll($updateAlbum);
	$resultAlbum = wg_data::update('albums', $updateAlbum, 'id', $addAlbum);
}

echo str_pad("\n".'<script type="text/javascript">
self.parent.$(".dialog #current-album-downloading").text("");
</script>'."\n"."\n"."\n"."\n"."\n"."\n", 1024);
updateStatus(wg_lang::get('DownloadingStatusAlbumInfo'));

}
echo str_pad('<script type="text/javascript">
self.parent.album.finishAlbums();
</script>'."\n", 1024);
//echo json_encode($result);
echo '</body></html>';
die();
