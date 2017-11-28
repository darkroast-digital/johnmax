<?php
$album = wg_display::$current_album;
wg_display::getphotos();
$largePhotos = '';
$largePhotoInfos = '';
$photos_per_row = 3;
$photo_count_total = $photo_count = 0;
?>
<div id="fb-root"></div>
<?php if(wg_settings::get('fb_comment') == 'on' || wg_settings::get('fb_like') == 'on'): ?>
	<script src="http://connect.facebook.net/en_US/all.js#appId=113196602108386&amp;xfbml=1"></script>
<?php endif; ?>
<script type="text/javascript">

var wg_fb_like = '<?php echo wg_settings::get('fb_like'); ?>';
var wg_fb_comment = '<?php echo wg_settings::get('fb_comment'); ?>';
var wg_page = 'photos';

jQuery(document).ready(function() {
	jQuery("#webgallery_image_viewer").appendTo('body');

	webgallery_init();

	// Bind an event to window.onhashchange that, when the hash changes, gets the
	// hash and adds the class "selected" to any matching nav link.
	jQuery(window).hashchange( function(){
		var hash = location.hash.toString();
		hash = hash.replace('#photo-', '');

		if(!isNumber(hash)) {
			return false;
		}

		jQuery('.current-image').hide().removeClass('current-image');
		jQuery('.current-info').hide().removeClass('current-info');

		var img = '#large_photo_' + hash;
		var info = '#info_large_photo_' + hash;
		jQuery(img).show().addClass('current-image');
		jQuery(info).show().addClass('current-info');

		if(wg_fb_like == 'on' || wg_fb_comment == 'on') {
			var facebookLike = '<div id="fb-root"></div>';
			if(wg_fb_like == 'on') {
				facebookLike += '<fb:like href="' + location.toString() + '" send="true" width="450" show_faces="true" font=""></fb:like>';
			}

			if(wg_fb_comment == 'on') {
				facebookLike += '<fb:comments href="' + location.toString() + '" num_posts="5" width="500"></fb:comments>';
			}

			jQuery(info).find('.facebook_like').html(facebookLike);
			FB.XFBML.parse();
		}

		jQuery("#webgallery_image_viewer").show();
		getHeight(img);
	});

	// Since the event is only triggered when the hash changes, we need to trigger
	// the event now, to handle the hash the page may have loaded with.
	jQuery(window).hashchange();

});

</script>

<div id="webgallery_album_photos">

<!-- <h3> --><?php //echo $album['name']; ?>
<!-- <?php //if(!WG_ALBUM_INCLUDE): ?><span style="float: right"><small><a href="<?php //echo wg_display::$currentURL; ?>">Back to Albums</a></small></span><?php //endif; ?> </h3>-->

<p><?php if(!empty($album['location'])) { echo  "Location: " . $album['location'] . "<br />"; }  ?>
<?php echo $album['desc'];  ?></p>

<ul><?php
	while($photo = wg_display::getphoto()):
		$photo_count++;
		$photo_count_total++;
		if($photo_count >= $photos_per_row) {
			$last_in_row = true;
		} else {
			$last_in_row = false;
		}

		if(wg_settings::get('photo_source') == 'server' || $photo['imageurl_medium'] == '')
		{
			$photo_location = WG_PHOTOS_LOCATION . '/' . $photo['albumid'] . '/' . $photo['id'] . '_medium.' . $photo['imagetype'];
			$largePhotos .= "<img src='". WG_PHOTOS_LOCATION . '/' . $photo['albumid'] . '/' . $photo['id'] . ".". $photo['imagetype']."' width='" . $photo['width'] . "' height='" . $photo['height'] . "' id='large_photo_" . $photo['id'] . "' class='large_photo' />";
		}
		else
		{
			$photo_location = $photo['imageurl_medium'];
			$largePhotos .= "<img src='". $photo['imageurl_fullsize'] ."' width='" . $photo['width'] . "' height='" . $photo['height'] . "' id='large_photo_" . $photo['id'] . "' class='large_photo' />";
		}

		$largePhotoInfos .= "<div id='info_large_photo_" . $photo['id'] . "' class='large_photo_info'>Photo " . $photo_count_total . " of <span class='total_image_count'></span><br />" . $photo['name'] . "<br /><span class='facebook_like'></span></div>";

		?><li><a href="#photo-<?php echo $photo['id']; ?>" class="album_image_wrapper" id="photo_<?php echo $photo['id']; ?>"><div style="background-image: url('<?php echo $photo_location; ?>');" class="album_image <?php if($last_in_row) { echo "album_image_last_child"; } ?>"></div><div class="wg_image_desc"><?php echo $photo['name']; ?></div></a></li><?php

		if($photo_count >= $photos_per_row):
			$photo_count = 0;
		endif;


	endwhile; ?></ul>
</div>
	<div class="clearrow"></div>
<div id="webgallery_image_viewer">
	<div id="image_viewer_main"><a href="#" id="image_viewer_close"></a><div id="image_viewer_photos"><a href="#" id="image_viewer_next"></a><a href="#" id="image_viewer_prev"></a><div id="photo_wrapper"><?php echo $largePhotos; ?></div></div><div id="image_viewer_infos"><?php echo $largePhotoInfos; ?></div></div>
</div>

<script>
	jQuery('.total_image_count').text('<?php echo $photo_count_total; ?>');
</script>