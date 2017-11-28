<?php
include(dirname(__FILE__) . '/wg_init.php');
$album = wg_display::$current_album;
wg_display::getphotos();
	?>
	<link href="<?php echo WG_INSTALL_LOCATION; ?>css/galleria.classic.css" rel="stylesheet" type="text/css" />
	<script>
		if(typeof jQuery == "undefined") {
			document.write('<script type="text/javascript" src="<?php echo WG_INSTALL_LOCATION; ?>js/jquery.js"></scr'+'ipt>');
		}
		if(typeof Galleria == "undefined") {
			document.write('<script type="text/javascript" src="<?php echo WG_INSTALL_LOCATION; ?>js/galleria-1.3.5.min.js"></sc'+'ript>');
			document.write('<script type="text/javascript" src="<?php echo WG_INSTALL_LOCATION; ?>js/galleria.classic.min.js"></sc'+'ript>');
		}
	</script>
	
<style>
/* This rule is read by Galleria to define the gallery height: */
#webgallery_<?php echo $album['id']; ?> {
	height: 450px;
}
</style>

<div id="webgallery_<?php echo $album['id']; ?>">
<?php
	while($photo = wg_display::getphoto()):

		if(wg_settings::get('photo_source') == 'server' || $photo['imageurl_medium'] == '')
		{
			$photo_location = WG_PHOTOS_LOCATION . '/' . $photo['albumid'] . '/' . $photo['id'] . '.' . $photo['imagetype'];
			$thumb_location = WG_PHOTOS_LOCATION . '/' . $photo['albumid'] . '/' . $photo['id'] . '_thumbnail.' . $photo['imagetype'];
		}
		else
		{
			$photo_location = $photo['imageurl_fullsize'];
			$thumb_location = $photo['imageurl_thumbnail'];
		}
		?>
		<a href="<?php echo $photo_location; ?>">
			<img title="<?php echo htmlentities($photo['name'], ENT_QUOTES, 'UTF-8'); ?>" src="<?php echo $thumb_location; ?>" />
		</a>
		<?php
	endwhile;
?>
	</div>

<script>
jQuery(function(){

	// Initialize Galleria
	jQuery('#webgallery_<?php echo $album['id']; ?>').galleria({
		lightbox: true
	});

});
</script>