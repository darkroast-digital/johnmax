var  maxHeight = 30;
var webgallery_init = function () {

	jQuery('.wg_image_desc').each(function() {
		if($(this).height() > maxHeight) {
			maxHeight = $(this).height();
			jQuery('.wg_image_desc').css('height', maxHeight + 'px');
		}
	});

	
	jQuery('#webgallery_image_viewer .large_photo').live('click', function () {
		if(jQuery('#image_viewer_photos img').length == 1) {
			return;
		}
		var current = jQuery('#image_viewer_photos img.current-image');
		jQuery(current).hide().removeClass('current-image');

		var current_info = '#info_' + jQuery(current).attr("id");
		jQuery(current_info).hide().removeClass('current-info');

		var next = jQuery(current).next('img');

		if(next.length > 0) {
			var nextimage = jQuery(next).attr("id");
			nextimage = nextimage.replace('large_photo_', '');
		} else {
			var img = jQuery(current).siblings('img:first');
			var nextimage = jQuery(img).attr("id");
		}
		nextimage = nextimage.replace('large_photo_', '');
		location.hash = '#photo-' + nextimage;
		return false;
	});

	jQuery(window).keydown(function(event){
		if (event.which == 37) {
			// left arrow; go back
			$('#image_viewer_prev').click();
		}else  if (event.which == 39) {
			// right arrow
			$('#image_viewer_next').click();
		}
	});

	jQuery('#webgallery_image_viewer #image_viewer_next').live('click', function () {
		if(jQuery('#image_viewer_photos img').length == 1) {
			return;
		}

		var current = jQuery('#image_viewer_photos img.current-image');
		jQuery(current).hide().removeClass('current-image');

		var current_info = '#info_' + jQuery(current).attr("id");
		jQuery(current_info).hide().removeClass('current-info');

		var next = jQuery(current).next('img');

		if(next.length > 0) {
			var nextimage = jQuery(next).attr("id");
			nextimage = nextimage.replace('large_photo_', '');
		} else {
			var img = jQuery(current).siblings('img:first');
			var nextimage = jQuery(img).attr("id");
		}

		nextimage = nextimage.replace('large_photo_', '');
		location.hash = '#photo-' + nextimage;
		return false;
	});

	jQuery('#webgallery_image_viewer #image_viewer_prev').live('click', function () {
		if(jQuery('#image_viewer_photos img').length == 1) {
			return;
		}
		var current = jQuery('#image_viewer_photos img.current-image');

		jQuery(current).hide().removeClass('current-image');

		var current_info = '#info_' + jQuery(current).attr("id");
		jQuery(current_info).hide().removeClass('current-info');

		var prev = jQuery(current).prev('img');

		if(prev.length > 0) {
			var previmage = jQuery(prev).attr("id");
			previmage = previmage.replace('large_photo_', '');
		} else {
			var img = jQuery(current).siblings('img:last');
			var previmage = jQuery(img).attr("id");
		}

		previmage = previmage.replace('large_photo_', '');
		location.hash = '#photo-' + previmage;
		return false;
	});

	jQuery('#webgallery, #webgallery_image_viewer').each(function() {
		if(jQuery(this).css('font-family') == 'serif') {
			jQuery(this).css('font-family', 'Arial');
			jQuery(this).css('font-size', '0.9em');
		}
	});

	jQuery(window).resize(function() {
		if(jQuery("#webgallery_image_viewer").css('display') != 'none') {
			jQuery("#webgallery_image_viewer").show("fast");

			jQuery('.large_photo').each(function () {
				var attr = jQuery(this).attr('oldheight');

				if (typeof attr !== 'undefined' && attr !== false) {
					 jQuery(this).attr('height',  jQuery(this).attr('oldheight'));
					 jQuery(this).attr('width',  jQuery(this).attr('oldwidth'));
					 jQuery(this).attr('resized',  "no");
				}
			});
			if(jQuery('.current-image').length > 0) { 
					getHeight(jQuery('.current-image'));
			}
		}

	});

	jQuery('#webgallery_image_viewer').click(function(e) {
		if(jQuery(e.target).attr('id') == 'webgallery_image_viewer') {
			location.hash = '';

			jQuery(this).hide();
			if (!e) var e = window.event;
			e.cancelBubble = true;
			if (e.stopPropagation) e.stopPropagation();
		}
	});

	jQuery('#image_viewer_close').click(function(e) {
		jQuery('#webgallery_image_viewer').hide();
		location.hash = '';
	});

	if(typeof wgRefreshGalleries != 'undefined' && wgRefreshGalleries == true) {
		var scriptPath = jQuery('#webgallery_script').attr('src').replace('js/webgallery.js', '');
		jQuery.post(scriptPath + 'wg_refresh.php');
	}
	
	if(wg_page == 'photos') {
		FB.Event.subscribe('xfbml.render',
			function(response) {
			   if(response == 2) {
				window.setTimeout("fixHeight();", 500);
			   }
			}
		);
	}

};


function fixHeight() {
	var viewer_height = parseInt(jQuery('.current-image').outerHeight() + jQuery('#image_viewer_infos').height() + parseInt(jQuery('#webgallery_image_viewer').css('marginTop').replace('px', '')));
	if(viewer_height > jQuery(window).height()) {
		$('#webgallery_image_viewer').css('overflow-y', 'scroll');
	}
}

function getHeight(img){
	if(jQuery(img).attr('height') > jQuery(img).attr('width')) {
		jQuery(img).css('paddingTop', '2px');
		jQuery(img).css('paddingBottom', '2px');
	} else {
		jQuery(img).css('paddingTop', '30px');
		jQuery(img).css('paddingBottom', '30px');
	}

	var imgheight = jQuery(img).attr('height');
	imgheight = imgheight + '';
	imgheight = imgheight.replace('px', '');

	var newheight = (parseInt(imgheight) + parseInt(jQuery(img).css('paddingTop').replace('px', '')) + parseInt(jQuery(img).css('paddingBottom').replace('px', '')) + parseInt(jQuery(	img).css('marginTop').replace('px', '')) + parseInt(jQuery(img).css('marginBottom').replace('px', ''))) + 'px';

	var viewer_height = parseInt(jQuery('.current-image').outerHeight() + jQuery('#image_viewer_infos').height() + parseInt(jQuery('#webgallery_image_viewer').css('marginTop').replace('px', '')));

	if(viewer_height > jQuery(window).height()) {
		if(jQuery('.current-image').attr('resized') != "yes") {
			var difference = viewer_height - jQuery(window).height();
			var oldheight = jQuery('.current-image').height();
			var oldwidth = jQuery('.current-image').width();
			jQuery('.current-image').attr('oldheight', oldheight);
			jQuery('.current-image').attr('resized', "yes");
			jQuery('.current-image').attr('oldwidth', oldwidth);
			var minHeight  = 400;
			var newheight = oldheight - difference;
			if(newheight < minHeight) {
				newheight = minHeight;
				$('#webgallery_image_viewer').css('overflow-y', 'scroll');
			} else {
				$('#webgallery_image_viewer').css('overflow-y', 'hidden');
			}

			var newwidth = (newheight/oldheight) * oldwidth;

			jQuery('.current-image').attr('height', newheight);
			jQuery('.current-image').attr('width', newwidth);
		}
	} else {
		$('#webgallery_image_viewer').css('overflow-y', 'hidden');
	}


	jQuery('#image_viewer_photos').css('height', jQuery('.current-image').outerHeight() + 'px');
	jQuery('#image_viewer_prev').css('height', jQuery('#image_viewer_photos').height() + 'px');
	jQuery('#image_viewer_next').css('height', jQuery('#image_viewer_photos').height() + 'px');

}


function setTotalCount(count) {
	if (typeof jQuery == 'undefined') {
		console.log('postponing');
		var t=setTimeout("setTotalCount("+count+")",200);
		return;
	}
	jQuery('.total_image_count').text(count);
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
