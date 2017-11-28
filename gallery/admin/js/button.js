function returnFalse() {
	return false;
}

function buttonDown (button) {
		if(!$(button).hasClass('button-disabled')) {
			$(button).addClass('button-pushed');
			$(button).find('.button-icon').hide();
			$(button).find('.button-icon-loading').show();
		}
		$(button).trigger('buttonClicked');
}

function closeMenu () {
	$('.button .button-popup-menu').hide();
}

function resetButton(id) {
	$(id).removeClass('button-pushed')
		.find('.button-icon').show();
	$(id).find('.button-icon-loading').hide();

	$(id).css('background-position', "0 0")
	.children('.right-side-button').css('background-position', "-342px 0");
}
var menuTimeout = '';
	
$(document).ready(function () {
	
	$('body').live('click', closeMenu);
	$('body').append('<img src="images/loader.gif" id="loader_preload"/>');
	$('#loader_preload').load(function() {
		$(this).remove();
	});

	$(".button .button-icon-loading").css('background-image', "url('images/loader.gif')");
//	$(".button .button-icon-loading").css('background-image', "url('images/loader.gif?" +new Date().getTime()+ "')");
	
	$('.button .button-popup-menu a').live('mouseover',  function() {
		clearTimeout(menuTimeout);
		return false;
	});
	
	$('.button .button-popup-menu a').live('mouseout',  function() {
		menuTimeout = setTimeout("closeMenu()",500);
		return false;
	});
	
	$('.button .button-popup-menu a').live('mousedown',  returnFalse);
	
	$('.button .button-popup-menu a').live('mouseup',  returnFalse);
	
	$('.button').each(function () {
		// correct the width for all buttons on the page
		var width = 49;
		width = ($(this).find('.label').text().length * 7) + width;
		//$(this).css('width', width + 'px');
		
		// add drop down menus to any that need it
		if($(this).hasClass('button-menu')) {
			$(this).find('.right-side-button').html("|&nbsp;&#9662;");
		}
	});
	
	$('.button-menu .right-side-button').live('mouseover',  function() {
		if($(this).parent().hasClass('button-pushed')) {
			return false;
		}
		$(this).css('background-position', "-342x -35px");
		return false;
	});
	
	$('.button-menu .right-side-button').live('mouseout',  function() {
		if($(this).parent().hasClass('button-pushed')) {
			return false;
		}
		$(this).css('background-position', "-342px 0");
		return false;
	});
	
	$('.button-menu .right-side-button').live('mousedown',  function() {
		if($(this).parent().hasClass('button-pushed')) {
			return false;
		}
		$(this).css('background-position', "-342px -70px");
		return false;
	});
	
	$('.button-menu .right-side-button').live('mouseup',  function() {
		if($(this).parent().hasClass('button-pushed')) {
			return false;
		}
		$(this).css('background-position', "-342px -35px");
		return false;
	});
	
	$('.button-menu .right-side-button').live('click',  function() {
		var parent = $(this).parent();
		var offset = $(parent).offset();
		
		$(parent).find('.button-popup-menu')
			.css('width', ($(parent).width()-8) + 'px')
			.css('top', (offset.top + 34) + 'px')
			.css('left', (offset.left + 14) + 'px')
			.show();
			
		return false;
		
	});
	
	$('.button').live('mouseover',  function() {
		if($(this).hasClass('button-pushed')) {
			return;
		}
		$(this).css('background-position', "0 -35px");
		$(this).children('.right-side-button').css('background-position', "-342px -35px");
	});
	
	$('.button').live('mouseout',  function() {
		if($(this).hasClass('button-pushed')) {
			return;
		}
		$(this).css('background-position', "0 0");
		$(this).children('.right-side-button').css('background-position', "-342px 0");
	});
	
	$('.button').live('mousedown',  function() {
		if($(this).hasClass('button-pushed')) {
			return;
		}
		$(this).css('background-position', "0 -70px");
		$(this).children('.right-side-button').css('background-position', "-342px -70px");
		$(this).children('.label').css('padding-top', "2px").css('padding-left', "2px");
	});
	
	$('.button').live('mouseup',  function() {
		if($(this).hasClass('button-pushed')) {
			return;
		}
		$(this).children('.label').css('padding-top', "0").css('padding-left', "0");
		$(this).css('background-position', "0 -35px");
		$(this).children('.right-side-button').css('background-position', "-342px -35px");
	});
	
	
	$('.button').live('click', function() {
		buttonDown(this);

		if(this.id) {
			$("#" + this.id).trigger('button_click');
		}
		return false;
		
	});
	
	//
});