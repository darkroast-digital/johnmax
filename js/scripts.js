
;(function($, window) {
	/** Settings **/

	// List of background images to use, the default image will be the first one in the list
	var backgrounds = [
	    '/../images/backgrounds/sports-bar-windsor-4.jpg',
		'/../images/backgrounds/sports-bar-windsor-2.jpg',
		'/../images/backgrounds/sports-bar-windsor.jpg',
		'/../images/backgrounds/sports-bar-windsor-3.jpg'
	],
	
	// Background options - see documentation
	backgroundOptions = {
		slideshowSpeed:3000
	},
	
	// Twitter username
	twitterUsername = 'primarytarget',
		
	// Number tweets to show, set to 0 to disable
	tweetCount = 2,
	
	// The text inside the search field
	searchBoxText = 'Search',
	
	// The text inside the view map button when map is visible
	hideMapButtonText = 'Hide map';
	
	/** End settings **/
	
	
	
	$('html').addClass('js-enabled');
		
	$(document).ready(function() {
		$.fullscreen(
			$.extend(backgroundOptions, {
				backgrounds: window.backgrounds || backgrounds,
				backgroundIndex: window.backgroundIndex
			})
		);

		// Initialise the menu
		$('ul.sf-menu').superfish({ speed: 0 });		
		
		// Bind the search button to show/hide the search field
		$('#search-button').click(function() {
			$('.search-container').fadeToggle();
			return false;
		});
				
		// Bind the social button to show/hide the social icons box
		$('#social-pop-out-trigger').click(function() {
			var $allBoxes = $('.footer-pop-out-box');
			if ($allBoxes.is(':animated')) {
				return false;
			}
					
			var $thisBox = $('.social-pop-out-box');
			if ($thisBox.is(':visible')) {
				$thisBox.slideUp();
			} else {
				if ($allBoxes.is(':visible')) {
					$allBoxes.filter(':visible').slideUp(function() {
						$thisBox.slideDown();
					});
				} else {
					$thisBox.slideDown();
				}
			}
			
			return false;
		});
		
		// Bind the Twitter button to show/hide the Twitter feed box
		$('#twitter-pop-out-trigger').click(function() {
			var $allBoxes = $('.footer-pop-out-box');
			if ($allBoxes.is(':animated')) {
				return false;
			}
					
			var $thisBox = $('.twitter-pop-out-box');		
			if ($thisBox.is(':visible')) {
				$thisBox.slideUp();
			} else {
				if ($allBoxes.is(':visible')) {
					$allBoxes.filter(':visible').slideUp(function() {
						$thisBox.slideDown();
					});
				} else {
					$thisBox.slideDown();
				}
			}
			
			return false;
		});
		
		// Bind the view map button to slide down / up the map
		var $viewMapButton = $('.view-map'),
		$mapImg = $('.hidden-map'),
		$contactInfoWrap = $('.contact-info-wrap'),
		viewMapButtonText = $('.view-map').text();
		
		$viewMapButton.click(function() {
			if (!$mapImg.add($contactInfoWrap).is(':animated')) {
				if (!$mapImg.hasClass('map-visible')) {
					$contactInfoWrap.slideUp(600, function() {
						$mapImg.slideDown(600, function() {
							$mapImg.addClass('map-visible');
							$viewMapButton.text(hideMapButtonText);
						});
					});
				} else {
					$mapImg.removeClass('map-visible').slideUp(600, function() {
						$contactInfoWrap.slideDown(600, function() {
							$viewMapButton.text(viewMapButtonText);
						});
					});
				}
			}
			return false;
		});
		
		// Bind any links with the class 'scroll-top' to animate the scroll to the top
		var scrollElement = 'html, body';
		$('html, body').each(function () {
		    var initScrollTop = $(this).attr('scrollTop');
		    $(this).attr('scrollTop', initScrollTop + 1);
		    if ($(this).attr('scrollTop') == initScrollTop + 1) {
		        scrollElement = this.nodeName.toLowerCase();
		        $(this).attr('scrollTop', initScrollTop);
		        return false;
		    }    
		});
		
		$('a.scroll-top').click(function () {			
			if ($(scrollElement).scrollTop() > 0) {
				$(scrollElement).animate({ scrollTop: 0 }, 1000);
			}			
			return false;
		});
		
		// Make the form inputs and search fields clear value when focused
		$('#search-input').toggleVal({ populateFrom: 'custom', text: searchBoxText });
		$('.toggle-val').toggleVal({ populateFrom: 'label', removeLabels: true });
		
		// Create the gallery rollover effect
		$('li.one-portfolio-item a').append(
			$('<div class="portfolio-hover"></div>').css({ opacity: 0, display: 'block' })
		).hover(function() {
			$(this).find('.portfolio-hover').stop().fadeTo(400, 0.6);
		}, function() {
			$(this).find('.portfolio-hover').stop().fadeTo(400, 0.0);
		});
	}); // End (document).ready
	
	$(window).load(function() {
		// Load the Twitter feed
		if (twitterUsername && tweetCount > 0) {
			(function() {
				var t = document.createElement('script'); t.type = 'text/javascript'; t.src = 'http://twitter.com/statuses/user_timeline/' + twitterUsername + '.json?callback=twitterCallback2&count=' + tweetCount;
				var h = document.getElementsByTagName('head')[0]; h.appendChild(t);
			})();
		}
	}); // End (window).load	

	// Any images to preload
	window.preload([
        'images/nav-a-bg1.png',
 		'images/search1.png',
 		'images/minimise1.png',
 		'images/2-col-hover.png',
 		'images/3-col-hover.png',
 		'images/4-col-hover.png',
 		'images/5-col-hover.png',
 		'images/6-col-hover.png',
 		'images/grid-hover.png',
 		'images/opacity-80-rep.png'
	]);
	
})(jQuery, window);