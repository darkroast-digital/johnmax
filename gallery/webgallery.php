<?php

if(isset($_GET['wgsid']) && (int)$_GET['wgsid'] > 0) {
	$webgallery_album_id = (int)$_GET['wgsid'];
	include(dirname(__FILE__) . '/webgallery_slider.php');
	return;
}

include(dirname(__FILE__) . '/wg_init.php');

?>
<script type="text/javascript">
	var wgRefreshGalleries = <?php if(WG_AJAX_REFRESH) { echo "true"; } else { echo "false"; } ?>;
</script>
<link href="<?php echo WG_INSTALL_LOCATION; ?>css/webgallery.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo WG_INSTALL_LOCATION; ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo WG_INSTALL_LOCATION; ?>js/jquery.hashchange.js"></script>
<script type="text/javascript" src="<?php echo WG_INSTALL_LOCATION; ?>js/webgallery.js" id="webgallery_script"></script>
<div id="webgallery">
<?php
if(wg_display::isAlbum()) {
	include(dirname(__FILE__) . '/wg_photolist.php');
} else{
	include(dirname(__FILE__) . '/wg_albumlist.php');
}
?>
<!-- <div id="faq-powered-by">
	<a href="http://johnmax.ca/photo-gallery.php?i">John Max Sports and Wings Website Photo Gallery</a>
</div> -->
</div>
