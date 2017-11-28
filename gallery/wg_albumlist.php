<?php
wg_display::getalbums();
$albums_per_row = 3;
$album_count = 0;
?>
<div id="webgallery_albums">
	<ul><?php
	while($album = wg_display::getalbum()):
		$album_count++;
		if($album_count >= $albums_per_row) {
			$last_in_row = true;
		} else {
			$last_in_row = false;
		}

		if(wg_settings::get('photo_source') == 'server' || $album['coverphoto_imageurl_medium'] == '')
		{
			$photo_location = WG_PHOTOS_LOCATION . '/' . $album['id'] . '/' . $album['coverphoto'] . '_medium.' . $album['coverphototype'];
		}
		else
		{
			$photo_location = $album['coverphoto_imageurl_medium'];
		}

		?><li><a href="<?php echo $album['link']; ?>" class="album_wrapper"><div style="background-image: url('<?php echo $photo_location; ?>');" class="album_image <?php if($last_in_row) { echo "album_last_child"; } else if($album_count == 1) { echo "album_first_child"; } ?>"></div><div class="wg_image_desc"><?php echo $album['name']; ?></div></a></li><?php
		if($album_count >= $albums_per_row):
			$album_count = 0;
		endif;
	endwhile;
	?></ul>
	<div class="clearrow"></div>
</div>
<script>
var wg_page = 'album';
jQuery(document).ready(function() {
	webgallery_init();
});
</script>