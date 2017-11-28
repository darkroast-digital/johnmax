<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }


set_time_limit(0);


$result = array('success' => true);

if(isset($_POST['albumid']) && (int)$_POST['albumid'] > 0) {
	$albumId = (int)$_POST['albumid'];
	$query = wg_data::query("SELECT *, a.id as albumid, a.facebookid as facebookid FROM :albums_table as a INNER JOIN :authorized_table as u ON a.userid=u.id WHERE a.id= " . $albumId);
	$count = 1;
} else {
	$countQuery = wg_data::row("SELECT count(id) as album_count FROM :albums_table");
	$count = (int)$countQuery['album_count'];
	$query = wg_data::query("SELECT *, a.id as albumid, a.facebookid as facebookid FROM :albums_table as a INNER JOIN :authorized_table as u ON a.userid=u.id ");
}

 // pad to force the browser to starting parsing/executing
if(OUTPUT_MODE) {
	echo str_pad('<html><head></head><body>'."\n", 4098);
	@ob_flush();
	@flush();
}

$growth = 100 / $count;
$totalProgress = 0;
$minorProgress = 0;

function updateProgress($progress, $current) {
	if(OUTPUT_MODE) {
		echo str_pad("\n".'<script type="text/javascript">
			self.parent.$(".dialog #progressbar").progressbar({ value: '.$progress .'});
			self.parent.$(".dialog #album-downloaded-count").text('.($current+1).');
			</script>'."\n"."\n"."\n"."\n"."\n"."\n", 1024);
		@ob_flush();
		@flush();
	}
}


function updateStatus($status) {
	if(OUTPUT_MODE) {
		echo str_pad("\n".'<script type="text/javascript">
		self.parent.$(".dialog #current-album-status").text("'.$status.'");
		</script>'."\n"."\n"."\n"."\n"."\n"."\n", 1024);
		@ob_flush();
		@flush();
	}
}

if(OUTPUT_MODE) {
	echo str_pad("\n".'<script type="text/javascript">
	self.parent.$(".dialog #album-total").text("'.$count.'");
	</script>'."\n"."\n"."\n"."\n"."\n"."\n", 1024);
}

updateStatus(wg_lang::get('DownloadingStatusAlbumInfo'));

$key = 0;
// foreach($_POST['albumids'] as $key=>$albumid) {
while ($row = wg_data::row($query)) {

	$albumid = $row['facebookid'];

	$minorProgress = $totalProgress = $growth * $key;

	updateProgress($totalProgress, $key);

	// get album info from Facebook
	$album = wg_facebook::getRecord($albumid, $row['token']);

	if(OUTPUT_MODE) {
		echo str_pad("\n".'<script type="text/javascript">
		self.parent.$(".dialog #current-album-downloading").text("'.addslashes($album->name) .'");
		</script>'."\n"."\n"."\n"."\n"."\n"."\n", 1024);
		@ob_flush();
		@flush();
	}
	updateStatus(wg_lang::get('DownloadingStatusSavingAlbum'));

	$numPhotosOrig=  @$album->count;
	$numPhotosOrig = (int)$numPhotosOrig;
	$numPhotos = $numPhotosOrig + 2; // add a percentage point for album stuff

	$growthPerPhoto = ($growth / ($numPhotos));

	// add album to the database
	$updateAlbum = array(
		'facebookname' => $album->name,
		'facebooklocation' => @$album->location,
		'facebookdesc' => @$album->description,
		'added' => $album->created_time,
		'updated' => $album->updated_time,
		'desc' => @$album->description,
		'location' => @$album->location
	);

	if($row['usename'] == 'facebook') {
		$updateAlbum['name'] = $album->name;
	}

	if($row['usedesc'] == 'facebook') {
		$updateAlbum['desc'] = @$album->description;
	}

	if($row['uselocation'] == 'facebook') {
		$updateAlbum['location'] = @$album->location;
	}

	$updateAlbum = wg_data::escapeAll($updateAlbum);
	$resultAlbum = wg_data::update('albums', $updateAlbum, 'id', $row['albumid']);

	if(!$resultAlbum) {
		$result =  array('success' => false, 'error' => wg_lang::get('AlbumError') . wg_data::lastError());
		echo json_encode($result);
		die();
	}

	$minorProgress = $minorProgress + $growthPerPhoto;
	updateProgress($minorProgress, $key);

	$currentPhotos = array();

	$photosQuery = wg_data::query("SELECT * FROM :photos_table WHERE `albumid` =" . (int)$row['albumid']);

	while($photoRow = wg_data::row($photosQuery)) {
		$currentPhotos[$photoRow['facebookid']] = $photoRow;
	}

	// get photos info
	$photos = wg_facebook::getRecord($albumid . '/photos', $row['token'], array('limit' => '600', 'offset' => '0'));

	$downloads = array();

	updateStatus("Saving photos...");

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


		if(isset($currentPhotos[$photo->id])) {
			$currentPhoto = &$currentPhotos[$photo->id];
		} else {
			$currentPhoto = false;
		}

		$downloadPhotos = true;

		if(is_array($currentPhoto)) {

			if(($currentPhoto['updated'] == $photo->updated_time && wg_settings::get('photo_source') == 'facebook') ||
				($currentPhoto['updated'] == $photo->updated_time && wg_settings::get('photo_source') == 'server' && is_file(WG_PHOTOS_FOLDER . '/' . $currentPhoto['albumid'] . '/' . $currentPhoto['id'] . '.jpg'))){
				$downloadPhotos = false;
			}

			$updatePhoto = array(
				'facebookname' => @$photo->name,
				'facebookposition' => (int)$photo->position,
				'added' => $photo->created_time,
				'updated' => $photo->updated_time,
				'facebooklink' => $photo->link,
				'height' => (int)$photo->height,
				'width' => (int)$photo->width,
				'thumbheight' => (int)$thumb->height,
				'thumbwidth' => (int)$thumb->width,
				'imageurl_thumbnail' => $imageurl_thumbnail,
				'imageurl_fullsize' => $imageurl_fullsize,
				'imageurl_medium' => $imageurl_medium,
			);

			if($currentPhoto['name'] == $currentPhoto['facebookname']) {
				$updatePhoto['name'] = @$photo->name;
			}

			if($currentPhoto['position'] == $currentPhoto['facebookposition']) {
				$updatePhoto['position'] = @$photo->position;
			}

			$updatePhoto = wg_data::escapeAll($updatePhoto);

			$resultPhoto = wg_data::update('photos', $updatePhoto, 'id', $currentPhoto['id']);
			$photoId = $currentPhoto['id'];
		} else {
			$insertPhoto = array(
				'albumid' => $row['albumid'],
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

			$resultPhoto = wg_data::insert('photos', $insertPhoto);
			$photoId = $resultPhoto;
		}



		if($downloadPhotos !== false && wg_settings::get('photo_source') == 'server' ) {
			$downloads[$photoId] = array('thumbnail' => $thumbUrl, 'image' => $photo->source, 'medium' => $medium->source);
		}

		if(!$resultPhoto) {
			$result =  array('success' => false, 'error' => wg_lang::get('PhotoDbError') . wg_data::lastError());
			echo json_encode($result);
			die();
		}
	}

	$minorProgress = $minorProgress + $growthPerPhoto;
	updateProgress($minorProgress, $key);

	$albumDir = WG_PHOTOS_FOLDER . '/' . $row['albumid'];
	if(!is_dir($albumDir)){
		@mkdir($albumDir, 0777);
		@chmod($albumDir, 0777);
	}
	$countPhoto = 1;
	$numDownloads = count($downloads);
	foreach($downloads as $id=>$download) {
		updateStatus(wg_lang::get('DownloadingPhotoProgress', array('current_photo_number'=>$countPhoto, 'total_photo_number'=>$numDownloads)));
		@unlink($albumDir . '/' . $id . '.jpg');
		@unlink($albumDir . '/' . $id . '_thumbnail.jpg');
		@unlink($albumDir . '/' . $id . '_medium.jpg');

		file_put_contents($albumDir . '/' . $id . '.jpg', file_get_contents($download['image']));
		file_put_contents($albumDir . '/' . $id . '_thumbnail.jpg', file_get_contents($download['thumbnail']));
		file_put_contents($albumDir . '/' . $id . '_medium.jpg', file_get_contents($download['medium']));
		$minorProgress = $minorProgress + $growthPerPhoto;
		updateProgress($minorProgress, $key);
		$countPhoto++;
	}

	//verify cover photo exists, otherwise use first photo
	if(isset($row['coverid'])) {
		$coverPhoto = wg_data::row("select * from :photos_table where id=" . $row['coverid']);
	} else {
		$coverPhoto = false;
	}

	if(empty($coverPhoto)) {
		$coverPhoto = wg_data::row("select * from :photos_table where albumid=" . (int)$row['albumid'] . " order by position ASC LIMIT 1");
		$updateAlbum2 = array(
			'coverid' => $coverPhoto['id']
		);

		$updateAlbum2 = wg_data::escapeAll($updateAlbum2);
		$resultAlbum = wg_data::update('albums', $updateAlbum2, 'id',(int)$row['albumid']);
	}

	if(OUTPUT_MODE) {
		echo str_pad("\n".'<script type="text/javascript">
		self.parent.$(".dialog #current-album-downloading").text("");
		</script>'."\n"."\n"."\n"."\n"."\n"."\n", 1024);
	}

	updateStatus(wg_lang::get('DownloadingStatusAlbumInfo'));
	++$key;
}

if(OUTPUT_MODE) {
	echo str_pad('<script type="text/javascript">
	self.parent.album.finishAlbums();
	</script>'."\n", 1024);
	//echo json_encode($result);
	echo '</body></html>';
}
die();
