var album = {
	'count': 0,
	'userid': 0,
	'growthAlbum': 0,
	'form_orig': '',
	'form': '',
	'current': 0,
	finishAlbums: function () {
		$addAlbumDialog3.dialog('close');
		$('.ui-dialog-titlebar .ui-dialog-titlebar-close').show();
		showNotification('Success', wg_lang.get('AlbumsImportSuccess') , 'green');
		LoadAlbumList();
	},
	'todo': [],

	getAlbum: function () {
		$.post('action.php', {'wg_action': 'getalbum', 'userid': album.userid, 'albumid': album.albums[album.current] },
				function(json){
					if(!json.success) {
						var msg = wg_lang.get('AlbumImportError');
						if(json.error.length > 0) {
							msg = json.error;
						}
						showNotification("Error", msg, 'red');
						return;
					}
					album.current++;
					var progress = album.growthAlbum * album.current;
					$(".dialog #progressbar").progressbar({ value: progress });
					$('.dialog #album-downloaded-count').text(album.current);
					if(album.current < album.count) {
						album.getAlbum();
					} else {
						$addAlbumDialog3.dialog('close');
						$('.ui-dialog-titlebar .ui-dialog-titlebar-close').show();
						showNotification('Success', wg_lang.get('AlbumsImportSuccess') , 'green');
					}
			}, 'json');
	}
};

var wg_lang = { 'get': function(varname, replace) {
	var lang = wg_lang[varname];
	for(i in replace) {
		lang = lang.replace(':' + i + ':', replace[i]);
	}
	return lang;
	
}};

var hasFlash = false;
var animateTimeout = {};
var $settingsDialog = {};
var $authorizeUserDialog = {};
var $userOptionsDialog = {};
var $addAlbumDialog = {};
var $addAlbumDialog2 = {};
var $addAlbumDialog3 = {};
var $reauthorizeDialog = {};
var $newPageDialog = {};
var $imageUploadProgress = {};
var $scriptInfoDialog = {};
var $addAlbumDialogUpload = {};
var $addAlbumDialogFacebook = {};
var $facebookUsersDialog = {};
var $addPicturesDialog = {};
var $editImageDialog = {};
var $viewImageDialog = {};
var $rateDialog = {};


function showNotification (title, msg, color) {
	if(typeof color === 'undefined') {
		var color = "yellow";
	}

	if(color == "success") {
		color = "green";
	}

	if(color == "failure" || color == "fail" || color == "error")  {
		color = "red";
	}

	$('#notification-inner')
		.removeClass('green')
		.removeClass('red')
		.removeClass('yellow');

	$('#notification-inner').addClass(color);
	$('#notification-inner .message').html(msg);
	$('#notification-inner .title').text(title);
	$('#notification-inner').hide();
	$('#notification-inner').fadeIn();
}

function showCriticalError (title, msg) {
	var color = "yellow";

	$('#notification-error-inner').addClass(color);
	$('#notification-error-inner .message').html(msg);
	$('#notification-error-inner .title').text(title);
	$('#notification-error-inner').hide();
	$('#notification-error-inner').fadeIn();
}

function hideCriticalError () {
	$('#notification-error-inner').fadeOut('fast');
}

function hideNotification () {
	$('#notification-inner').fadeOut('fast');
}

$(function() {

	hasFlash = false;
	
	if(typeof swfobject != 'undefined') {
		var playerVersion = swfobject.getFlashPlayerVersion();

		if(playerVersion.major > 8) {
			hasFlash = true;
		}
	}
	
	if(typeof appIdSet != "undefined"){
		if((!appIdSet || !appSecret) && userCount < 1) {
		}
	}

	$('.dialog #insert_album_select').live('change', function(){
		if($(this).val() > 0){
			$('.dialog #insert_album_id').text($(this).val());
			$('.dialog #insert_album_code').show().css('display', 'block');
		} else {
			$('.dialog #insert_album_id').text('');
			$('.dialog #insert_album_code').hide();
		}
	});

	$('.dialog #insert_slider_select').live('change', function(){
		if($(this).val() > 0){
			$('.dialog #insert_slider_id').text($(this).val());
			$('.dialog #insert_slider_code').show().css('display', 'block');
		} else {
			$('.dialog #insert_slider_id').text('');
			$('.dialog #insert_slider_code').hide();
		}
	});


	$('.album-row-dialog').live('mouseover', function(){
		$(this).addClass('hover');
	});
	
	$('.album-row-dialog').live('mouseout', function(){
		$(this).removeClass('hover');
	});

	$('.album-checkbox').live('click', function(e){
		var parent = $(this).parents('.album-row-dialog');
		if($(parent).hasClass('selected')) {
			$(parent).removeClass('selected');
			$(this).prop("checked", false);
		}else {
			$(parent).addClass('selected');
			$(this).prop("checked", true);
		}
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();

	});

	$('.album-row-dialog').live('click', function(){
		if($(this).hasClass('selected')) {
			$(this).removeClass('selected');
			$(this).find('input:checkbox').prop("checked", false);
		}else {
			$(this).addClass('selected');
			$(this).find('input:checkbox').prop("checked", true);
		}
	});

	$('#auth-user-2').live('click', function(){
		window.location = authURL;
	});

	$('#save-user').live('click', function(){
		$.post('action.php', {'wg_action':'saveuserinfo', 
			'username': $('.dialog #u_username').val(), 
			'password_old': $('.dialog #u_password_old').val(), 
			'password_new': $('.dialog #u_password_new').val(), 
			'password_new_confirm': $('.dialog #u_password_new_confirm').val()
			}, function(json) {
			if(json.success) {
				showNotification("Success", wg_lang.get('UserInfoUpdatedSuccess'), "green");
				$userOptionsDialog.dialog('close');
				
			} else {
				var msg = wg_lang.get('UserInfoUpdatedError');
				if(json.error.length > 0) {
					msg = json.error;
				}
//				showNotification("Error", msg, "red");
				alert(msg);
			}
			resetButton('.dialog #save-user');
		}, 'json');
	});
	
	$('#save-settings').live('click', function(){

		if($('.dialog #s_source_facebook_dialog').attr('checked')) {
			var photo_source = 'facebook';
		} else {
			var photo_source = 'server';
		}

		if($('.dialog #s_source_original').val() == 'facebook' && photo_source == 'server') {
			if(!confirm(wg_lang.get('AlertChangingPhotoSource'))) {
				return false;
			}
		}
		
		if($('.dialog #s_fb_like_dialog').attr('checked')) {
			var fb_like = 'on';
		} else {
			var fb_like = 'off';
		}

		if($('.dialog #s_fb_comment_dialog').attr('checked')) {
			var fb_comment = 'on';
		} else {
			var fb_comment = 'off';
		}

		if($('.dialog #s_images_end_dialog').attr('checked')) {
			var new_images_pos = 'end';
		} else {
			var new_images_pos = 'start';
		}

		$.post('action.php', {'wg_action':'savesettings', 
			'app_id': $('.dialog #s_appid').val(), 
			'app_secret': $('.dialog #s_appsecret').val(),
			'photo_source': photo_source,
			'fb_comment': fb_comment,
			'fb_like': fb_like,
			'new_images_pos': new_images_pos,
			'viewgallery': $('.dialog #s_viewgallery').val()
			}, function(json) {
			if(json.success) {
				showNotification("Success", wg_lang.get('SettingsUpdatedSuccess'), "green");
				$settingsDialog.dialog('close');
				window.location = "index.php";
				return false;
			} else {
				var msg = wg_lang.get('SettingsUpdateError');
				if(json.error.length > 0) {
					msg = json.error;
				}
//				showNotification("Error", msg, "red");
				alert(msg);
			}
			resetButton('.dialog #save-settings');
		}, 'json');
	});

	$('#notification-close').click(function () {
		hideNotification();
	});

	$('#notification-error-close').click(function () {
		hideCriticalError();
	});
	
	
	$('.unauthorized-user').live('click', function () {
		var name = $(this).parent().text();
		$reauthorizeDialog.dialog('open');
		$('.reauthorize-user-name').text(name);
	});
	
	$('#verify-button').live('click',function () {
		showBigLoader();
		$.post('action.php', {'wg_action': 'verifyusers' },
			function(json){
				hideBigLoader();

				if(!json.success) {
					alert(wg_lang.get('UnableToVerifyUsers'));
					return;
				}
				
				if(json.authorizedcount == json.total) {
					alert(wg_lang.get('AllUsersAuthorized'));
				}

				if(json.notauthorized.length > 0) {
					for (i in json.notauthorized) {
						$(".dialog #authorized_row_" + json.notauthorized[i] + " .unauthorized-user").show();
					}

					alert(wg_lang.get('SomeUsersNotAuthorized'));
				}
				resetButton('.dialog #verify-button');
		}, 'json');
	});

	$('#verify-wrapper, #refresh-wrapper').mouseover(function () {
		$(this).addClass('hover-dark-bg');
	});

	$('#verify-wrapper, #refresh-wrapper').mouseout(function () {
		$(this).removeClass('hover-dark-bg');
	});

	$('.album_item').live('mouseover', function () {
		$(this).addClass('album_item_over');
		$(this).find('.delete-box').addClass('delete-box-bg');
		$(this).find('.edit-box').addClass('edit-box-bg');
		$(this).find('.refresh-album-box').addClass('refresh-bg');
	});

	$('.album_item').live('mouseout', function () {
		$(this).removeClass('album_item_over');
		$(this).find('.delete-box').removeClass('delete-box-bg');
		$(this).find('.refresh-album-box').removeClass('refresh-bg');
		$(this).find('.edit-box').removeClass('edit-box-bg');
	});

	$('.user').live('mouseover', function () {
		$(this).addClass('user_over');
	});

	$('.user').live('mouseout', function () {
		$(this).removeClass('user_over');
	});

	$('.user-name').live('mouseover', function () {
		$(this).find('>.delete-box-user').addClass('delete-box-bg');
	});

	$('.user-name').live('mouseout', function () {
		$(this).find('>.delete-box-user').removeClass('delete-box-bg');
	});

	$('.delete-box').live('click', function () {
		var aid = $(this).attr('id').replace('delete_', '');
		if(confirm(wg_lang.get('ConfirmAlbumDelete'))) {
			showBigLoader();
			$.post('action.php', {'wg_action': 'deletealbum', 'albumid': aid },
			function(json){

				hideBigLoader();

				if(!json.success) {
					var msg = wg_lang.get('AlbumDeleteError');
						if(typeof json.error != "undefined") {
							msg = json.error;
						}
					showNotification("Error", msg, 'red');
					return;
				}

				showNotification("Success", wg_lang.get('AlbumDeletedSuccess'), 'green');
				$("#album_row_" + json.id).fadeOut('fast').remove();

				if($('.album_item').size() == 0) {
					$('#no_albums').show();
				}
				$('#picture-list-ul li').remove();
				$('#picture-list-ul').hide();
				$('#no_album').show();
				$('#no_pictures').hide();
				$('#images-for').hide();
			}, 'json');
		}
		return false;
	});

	$('.album_item').live('click', function () {
		var albumid = $(this).attr('id').replace('album_row_', '');
		loadImages(albumid);
	});


	$('.dialog #use_name_fb').live('change', function () {
		var attr = $(this).attr('checked');
		if (attr == 'checked' || attr == 'CHECKED') {
			$('.dialog #a_album_name').val($('.dialog #a_album_fbname').val());
			$('.dialog #a_album_name').attr('readonly', 'readonly');
			$('.dialog #a_album_name').attr('disabled', 'disabled');
		} else {
			$('.dialog #a_album_name').removeAttr('readonly').removeAttr('disabled');
		}
	});


	$('.dialog #use_desc_fb').live('change', function () {
		var attr = $(this).attr('checked');
		if (attr == 'checked' || attr == 'CHECKED') {
			$('.dialog #a_album_desc').val($('.dialog #a_album_fbdesc').val());
			$('.dialog #a_album_desc').attr('readonly', 'readonly');
			$('.dialog #a_album_desc').attr('disabled', 'disabled');
		} else {
			$('.dialog #a_album_desc').removeAttr('readonly').removeAttr('disabled');
		}
	});

	$('.dialog #use_location_fb').live('change', function () {
		var attr = $(this).attr('checked');
		if (attr == 'checked' || attr == 'CHECKED') {
			$('.dialog #a_album_location').val($('.dialog #a_album_fblocation').val());
			$('.dialog #a_album_location').attr('readonly', 'readonly');
			$('.dialog #a_album_location').attr('disabled', 'disabled');
		} else {
			$('.dialog #a_album_location').removeAttr('readonly').removeAttr('disabled');
		}
	});


	$('#save-album').live('click', function () {
		var usedesc = 'no';
		var usename = 'no';
		var uselocation = 'no';
		
		var use_desc_attr = $('.dialog #use_desc_fb').attr('checked');
		if (use_desc_attr == 'checked' || use_desc_attr == 'CHECKED') {
			usedesc = 'yes';
		}
		
		var use_name_attr = $('.dialog #use_name_fb').attr('checked');
		if (use_name_attr == 'checked' || use_name_attr == 'CHECKED') {
			usename = 'yes';
		}

		var use_location_attr = $('.dialog #use_location_fb').attr('checked');
		if (use_location_attr == 'checked' || use_location_attr == 'CHECKED') {
			uselocation = 'yes';
		}

		$.post('action.php', {'wg_action': 'updatealbum', 
			'albumid': $('.dialog #a_album_id').val(),
			'name': $('.dialog #a_album_name').val(),
			'location': $('.dialog #a_album_location').val(),
			'uselocation': uselocation,
			'usename': usename,
			'usedesc': usedesc,
			'desc': $('.dialog #a_album_desc').val()
			},
			function(json){
				
				hideBigLoader();
			
				if(!json.success) {
					var msg = wg_lang.get('UpdateAlbumError');
						if(typeof json.error != "undefined") {
							msg = json.error;
						}
					showNotification("Error", msg, 'red');
					resetButton('.dialog #save-album');
					$addAlbumDialogUpload.dialog('close');
					return;
				}
				showNotification("Success", wg_lang.get('AlbumUpdateSuccess'), 'green');
				resetButton('.dialog #save-album');
				$addAlbumDialogUpload.dialog('close');
				LoadAlbumList();

		}, 'json');
	});

	$('.edit-box').live('click', function () {
		showBigLoader();
		var aid = $(this).attr('id').replace('edit_', '');

		$('.dialog .swfupload-control').hide();
		$('.dialog #save-album').show();
		$('.dialog #save-new-album').hide();

		
		$.post('action.php', {'wg_action': 'albuminfo', 'albumid': aid },
		function(json){
			
			hideBigLoader();
		
			if(!json.success) {
				var msg = wg_lang.get('LoadAlbumError');
					if(typeof json.error != "undefined") {
						msg = json.error;
					}
				showNotification("Error", msg, 'red');
				$addAlbumDialogUpload.dialog('close');
				return;
			}
			$addAlbumDialogUpload.dialog('open');
			
			$('.dialog #before_use_location_fb').attr('id', 'use_location_fb');
			$('.dialog #before_use_desc_fb').attr('id', 'use_desc_fb');
			$('.dialog #before_use_name_fb').attr('id', 'use_name_fb');

			$('.dialog #a_album_name').removeAttr('readonly').removeAttr('disabled');
			$('.dialog #a_album_desc').removeAttr('readonly').removeAttr('disabled');
			$('.dialog #a_album_location').removeAttr('readonly').removeAttr('disabled');
			$('.dialog #use_name_fb').removeAttr('checked');
			$('.dialog #use_desc_fb').removeAttr('checked');
			$('.dialog #use_location_fb').removeAttr('checked');

			if(json.facebookid == '') {
				$('.dialog .fb_row').hide();
				$addAlbumDialogUpload.dialog({ height: 360 });
				$('.dialog #a_album_name').val(json.name);
				$('.dialog #a_album_desc').val(json.desc);
				$('.dialog #a_album_location').val(json['location']);

			} else {
				$('.dialog .fb_row').show();
				$addAlbumDialogUpload.dialog({ height: 460 });

				if(json.usename == 'facebook') {
					$('.dialog #a_album_name').val(json.facebookname);
					$('.dialog #a_album_name').attr('readonly', 'readonly');
					$('.dialog #a_album_name').attr('disabled', 'disabled');
					$('.dialog #use_name_fb').attr('checked', 'checked');
				} else {
					$('.dialog #a_album_name').val(json.name);
				}

				if(json.usedesc == 'facebook') {
					$('.dialog #a_album_desc').val(json.facebookdesc);
					$('.dialog #a_album_desc').attr('readonly', 'readonly');
					$('.dialog #a_album_desc').attr('disabled', 'disabled');
					$('.dialog #use_desc_fb').attr('checked', 'checked');
				} else {
					$('.dialog #a_album_desc').val(json.desc);
				}

				if(json.uselocation == 'facebook') {
					$('.dialog #a_album_location').val(json.facebooklocation);
					$('.dialog #a_album_location').attr('readonly', 'readonly');
					$('.dialog #a_album_location').attr('disabled', 'disabled');
					$('.dialog #use_location_fb').attr('checked', 'checked');
				} else {
					$('.dialog #a_album_location').val(json['location']);
				}

				$('.dialog #a_album_fbname').val(json.facebookname);
				$('.dialog #a_album_fbdesc').val(json.facebookdesc);
				$('.dialog #a_album_fblocation').val(json.facebooklocation);
			}

			$('.dialog #a_album_id').val(json.id);

			}, 'json');
		return false;
	});

	$('.delete-box-user').live('click', function (e) {
		var uid = $(this).parent().parent().attr('id').replace('authorized_row_', '');
		var uname = $(this).parent().text();
		if(confirm(wg_lang.get('ConfirmRemoveAuthorizedUser', {'username': uname}))) {
			showBigLoader();
			$.post('action.php', {'wg_action': 'deleteuser', 'userid': uid },
				function(json){
					hideBigLoader();
					if(!json.success) {
						var msg = wg_lang.get('DeleteAuthUserError');
						if(typeof json.error != "undefined") {
							msg = json.error;
						}
						alert(msg);
						return;
					}
					
					showNotification("Success", wg_lang.get('UserDeleteSuccess'), 'green');

					$("#authorized_row_" + json.id).fadeOut('fast').remove();

					LoadAuthorizedList();
					LoadAlbumList();
			}, 'json');
		}

		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
	});

	$('.refresh-user').live('click', function (e) {
		var uid = $(this).parent().parent().attr('id').replace('authorized_row_', '');
		var uname = $(this).parent().text();
		showBigLoader();
		$.post('action.php', {'wg_action': 'deleteuser', 'userid': uid },
			function(json){
				hideBigLoader();
				if(!json.success) {
					showNotification("Error", wg_lang.get('VerifyUsersError'), 'red');
					return;
				}
				
				showNotification("Success", wg_lang.get('UserIsAuthorized'), 'green');

				$("#authorized_row_" + json.id).fadeOut('fast').remove();

				LoadAuthorizedList();
				LoadAlbumList();
		}, 'json');

		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
	});


	$('.delete-box-user, .refresh-album-box').live('mouseover', function () {
		$(this).addClass('hover-box-on');
	});

	$('.delete-box-user, .refresh-album-box').live('mouseout', function () {
		$(this).removeClass('hover-box-on');
	});

	$('.image_box').live('mouseover', function () {
		clearTimeout(animateTimeout);
		$(this).siblings('.image_name_box').animate({opacity: 0.2 });
	});

	$('.image_box').live('mouseout', function () {
		clearTimeout(animateTimeout);
		animateTimeout = setTimeout("$('#"+$(this).attr('id') + "').siblings('.image_name_box').animate({opacity: 0.8 });", 175);
	});

	$('.image_item').live('mouseover', function () {
		$(this).find('.image_box').show();
		var text = $(this).find('.image_name_box').text();
		if(text != '') {
			$(this).find('.image_name_box').show();
		}
	});

	$('.image_item').live('mouseout', function () {
		$(this).find('.image_box').hide();
		$(this).find('.image_name_box').hide();
	});

	$('.edit-box').live('mouseover', function () {
		$(this).addClass('hover-box-on');
			var position = $(this).position();

		$('#action-edit').css('position', 'absolute')
			.css('top', (position.top) + 'px')
			.css('left', (position.left - 2-$('#action-edit').outerWidth())  + 'px')
			.show();

	});

	$('.edit-box').live('mouseout', function () {
		$('#action-edit').hide();
		$(this).removeClass('hover-box-on');
	});

	$('.image_edit_box').live('mouseover', function () {
		$(this).css('display', 'block').addClass('image_box_hover');
		var position = $(this).position();
		
		var boxOuterHeight = $(this).outerHeight()+(parseInt($(this).css('marginTop').replace('px', ''))*2)+parseInt($(this).css('marginBottom').replace('px', ''));

		$('#action-image-edit').css('position', 'absolute')
			.css('top', (position.top + boxOuterHeight) + 'px')
			.css('left', (position.left - $('#action-image-edit').outerWidth() + 2)  + 'px')
			.show();

	});

	$('.image_edit_box').live('mouseout', function () {
		$('#action-image-edit').hide();
		$(this).removeClass('image_box_hover');
	});

	$('.image_delete_box').live('mouseover', function () {
		$(this).css('display', 'block').addClass('image_box_hover');
		var position = $(this).position();
		
		$('#action-image-delete').css('position', 'absolute')
			.css('top', (position.top + 4) + 'px')
			.css('left', (position.left - $('#action-image-delete').outerWidth() + 2)  + 'px')
			.show();

	});

	$('.image_delete_box').live('mouseout', function () {
		$('#action-image-delete').hide();
		$(this).removeClass('image_box_hover');
	});

	
	$('.image_delete_box').live('click', function () {
		var imageid = $(this).attr('id').replace('image_delete_', '');

		if(confirm(wg_lang.get('ConfirmDeleteImage'))) {
			showBigLoader();
			$.post('action.php', {'wg_action': 'deleteimage', 'imageid': imageid },
			function(json){

				hideBigLoader();

				if(!json.success) {
					var msg = wg_lang.get('DeleteImageError');
						if(typeof json.error != "undefined") {
							msg = json.error;
						}
					showNotification("Error", msg, 'red');
					return;
				}

				showNotification("Success", wg_lang.get('DeleteImageSuccess'), 'green');
				$("#image_row_" + json.id).fadeOut('fast').remove();

				if($('.image_item').size() == 0) {
					$('#no_pictures').show();
				}

			}, 'json');
		}
		return false;
	});

	$('.image_cover_box').live('mouseover', function () {
		$(this).css('display', 'block').addClass('image_box_hover');
		var position = $(this).position();
		
		var boxOuterHeight = 2+$(this).outerHeight()+(parseInt($(this).css('marginTop').replace('px', '')))+parseInt($(this).css('marginBottom').replace('px', ''));

		$('#action-image-cover').css('position', 'absolute')
			.css('top', (position.top + (boxOuterHeight*2)) + 'px')
			.css('left', (position.left - $('#action-image-cover').outerWidth() + 2)  + 'px')
			.show();

	});

	$('.image_cover_box').live('mouseout', function () {
		$('#action-image-cover').hide();
		$(this).removeClass('image_box_hover');
	});


	$('.image_zoom_box').live('mouseover', function () {
		$(this).css('display', 'block').addClass('image_box_hover');
		var position = $(this).position();
		
		var boxOuterHeight = $(this).outerHeight()+(parseInt($(this).css('marginTop').replace('px', '')))+parseInt($(this).css('marginBottom').replace('px', ''));

		$('#action-image-zoom').css('position', 'absolute')
			.css('top', (position.top + (boxOuterHeight*3)) + 'px')
			.css('left', (position.left - $('#action-image-zoom').outerWidth() + 2)  + 'px')
			.show();

	});

	$('.image_zoom_box').live('mouseout', function () {
		$('#action-image-zoom').hide();
		$(this).removeClass('image_box_hover');
	});



	$('.image_rotateleft_box').live('mouseover', function () {
		$(this).css('display', 'block').addClass('image_box_hover');
		var position = $(this).position();
		
		var boxOuterHeight = $(this).outerHeight()+(parseInt($(this).css('marginTop').replace('px', '')))+parseInt($(this).css('marginBottom').replace('px', ''));

		$('#action-image-rotateleft').css('position', 'absolute')
			.css('top', (position.top + (boxOuterHeight*4)) + 'px')
			.css('left', (position.left - $('#action-image-rotateleft').outerWidth() + 2)  + 'px')
			.show();

	});

	$('.image_rotateleft_box').live('mouseout', function () {
		$('#action-image-rotateleft').hide();
		$(this).removeClass('image_box_hover');
	});


	$('.image_rotateright_box').live('mouseover', function () {
		$(this).css('display', 'block').addClass('image_box_hover');
		var position = $(this).position();
		
		var boxOuterHeight = $(this).outerHeight()+(parseInt($(this).css('marginTop').replace('px', '')))+parseInt($(this).css('marginBottom').replace('px', ''));

		$('#action-image-rotateright').css('position', 'absolute')
			.css('top', (position.top + (boxOuterHeight*5)) + 'px')
			.css('left', (position.left - $('#action-image-rotateright').outerWidth() + 2)  + 'px')
			.show();

	});

	$('.image_rotateright_box').live('mouseout', function () {
		$('#action-image-rotateright').hide();
		$(this).removeClass('image_box_hover');
	});


	$('.image_zoom_box').live('click', function () {
		showBigLoader();
		var imageid = $(this).attr('id').replace('image_zoom_', '');
		$.post('action.php', {'wg_action': 'imageinfo','imageid': imageid },
			function(json){
				if(!json.success) {
					var msg = wg_lang.get('LoadImageInfoError');
					if(json.error.length > 0) {
						msg = json.error;
					}
					showNotification("Error", msg, 'red');
					hideBigLoader();
					return;
				}
				hideBigLoader();

				var viewtitle = wg_lang.get('ViewingImageTitle');
				if(json.name != '') {
					viewtitle = viewtitle + ' - ' + json.name;
				}
				$viewImageDialog = $('<div class="dialog" style="text-align: center;"></div>')
						.html('<img src="'+json.fullsizepath+'" width="'+json.width+'" height="'+json.height+'" />')
						.dialog({
							autoOpen: false,
							title: viewtitle,
							modal: true,
							draggable: true,
							resizable: true,
							height: (parseInt(json.height)+70),
							width: (parseInt(json.width)+40)
					});
				$viewImageDialog.dialog('open');

			},
		'json');
	});

	$('.image_cover_box').live('click', function () {
		showBigLoader();
		var coverid = $(this).attr('id').replace('image_cover_', '');

		$.post('action.php', {'wg_action': 'savecoverimage','coverid':coverid },
		function(json){
			if(!json.success) {
				var msg = wg_lang.get('ChangeCoverError');
				if(json.error.length > 0) {
					msg = json.error;
				}
				showNotification("Error", msg, 'red');
				return;
			}
			hideBigLoader();

			showNotification("Success", wg_lang.get('CoverImageUpdatedSuccess'), 'green');
			LoadAlbumList();

		}, 'json');
	});

	$('.image_edit_box').live('click', function () {
		showBigLoader();
		var imageid = $(this).attr('id').replace('image_edit_', '');

		$.post('action.php', {'wg_action': 'imageinfo','imageid': imageid },
		function(json){
			if(!json.success) {
				var msg = wg_lang.get('LoadImageInfoError');
				if(json.error.length > 0) {
					msg = json.error;
				}
				showNotification("Error", msg, 'red');
				hideBigLoader();
				return;
			}
			hideBigLoader();
			$editImageDialog.dialog('open');

			$('.dialog #i_image_id').val(json.id);
			$('.dialog #i_image_name').val(json.name);

		}, 'json');
	});
	
	
	$('.image_rotateleft_box').live('click', function () {
		showBigLoader();
		var imageid = $(this).attr('id').replace('image_rotateleft_', '');

		$.post('action.php', {'wg_action': 'rotateimage','imageid': imageid, 'direction': 'left' },
		function(json){
			if(!json.success) {
				var msg = wg_lang.get('ImageNotRotated');
				if(json.error.length > 0) {
					msg = json.error;
				}
				showNotification("Error", msg, 'red');
				hideBigLoader();
				return;
			}
			
			$('#image_' + json.imageid).css('background-image', 'url("' + json.imageurl + '")');
			
			hideBigLoader();
			showNotification("Success", wg_lang.get('ImageRotated'), 'green');
			
		}, 'json');
	});



	
	$('.image_rotateright_box').live('click', function () {
		showBigLoader();
		var imageid = $(this).attr('id').replace('image_rotateright_', '');

		$.post('action.php', {'wg_action': 'rotateimage','imageid': imageid, 'direction': 'right' },
		function(json){
			if(!json.success) {
				var msg = wg_lang.get('ImageNotRotated');
				if(json.error.length > 0) {
					msg = json.error;
				}
				showNotification("Error", msg, 'red');
				hideBigLoader();
				return;
			}
				$('#image_' + json.imageid).css('background-image', 'url("' + json.imageurl + '")');
			hideBigLoader();
			showNotification("Success", wg_lang.get('ImageRotated'), 'green');
			
		}, 'json');
	});
	
	$('#save-image').live('click', function () {
		$.post('action.php', {'wg_action': 'updateimage',
			'imageid': $('.dialog #i_image_id').val(),
			'name': $('.dialog #i_image_name').val()
			},
		function(json){
			if(!json.success) {
				var msg = wg_lang.get('SaveImageError');
				if(json.error.length > 0) {
					msg = json.error;
				}
				$editImageDialog.dialog('close');
				showNotification("Error", msg, 'red');
				hideBigLoader();
				return;
			}
			hideBigLoader();
			$editImageDialog.dialog('close');
			showNotification("Success", wg_lang.get('SaveImageSuccess'), 'green');
			resetButton('.dialog #save-image');
			loadImages($('#current_album').val());

		}, 'json');
	});


	$('.delete-box').live('mouseover', function () {
		$(this).addClass('hover-box-on');
		var position = $(this).position();

		$('#action-delete').css('position', 'absolute')
			.css('top', (position.top) + 'px')
			.css('left', (position.left - 2-$('#action-delete').outerWidth())  + 'px')
			.show();
	});

	$('.refresh-album-box').live('mouseover', function () {
		var position = $(this).position();
		$('#action-refresh').css('position', 'absolute')
			.css('top', (position.top) + 'px')
			.css('left', (position.left - 10-$('#action-delete').outerWidth())  + 'px')
			.show();
	});


	$('.refresh-album-box').live('mouseout', function () {
		$('#action-refresh').hide();
	});


	$('.delete-box').live('mouseout', function () {
		$('#action-delete').hide();
		$(this).removeClass('hover-box-on');
	});

	$('#auth-user').live('click', function () {
		if($(this).hasClass('button-disabled')) {
			showNotification('Set up',  wg_lang.get('FacebookAppSetup1')+'<a href="help/index.html" target="_blank">' + wg_lang.get('FacebookAppSetup2')+ '</a>', 'yellow');
			$facebookUsersDialog.dialog('close');
			return;
		}
		$facebookUsersDialog.dialog('close');
		$authorizeUserDialog.dialog('open');
		
		resetButton('.dialog #auth-user');
		// prevent the default action, e.g., following a link
		return false;
	});

	$('#page-user').live('click', function () {
		$facebookUsersDialog.dialog('close');
		$newPageDialog.dialog('open');
		
		resetButton('.dialog #page-user');
		// prevent the default action, e.g., following a link
		return false;
	});
	
	$authorizeUserDialog = $('<div class="dialog"></div>')
		.html($('#auth_user').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleAuthorizeUser'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 300,
			width: 550,
	});

	$newPageDialog = $('<div class="dialog"></div>')
		.html($('#page_user').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleAddFacebookPage'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 200,
			width: 550,
	});

	$addAlbumDialog = $('<div class="dialog"></div>')
		.html($('#add_album').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleAddAlbum'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 220,
			width: 350
	});

	$addAlbumDialogFacebook = $('<div class="dialog"></div>')
		.html($('#add_album_facebook').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleAddAlbum'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 175	,
			width: 350
	});

	$addAlbumDialogUpload = $('<div class="dialog"></div>')
		.html($('#add_album_upload').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleUploadPics'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 360,
			width: 560
	});

	$editImageDialog = $('<div class="dialog"></div>')
		.html($('#edit_image').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleEditImage'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 170,
			width: 560
	});

	$facebookUsersDialog = $('<div class="dialog"></div>')
		.html($('#facebook_users').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleFacebookUsers'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 300,
			width: 480
	});
		$rateDialog = $('<div class="dialog"></div>')
		.html($('#ratepopup').html())
		.dialog({
			autoOpen: false,
			title: 'One week since you installed Website Gallery',
			modal: true,
			draggable: true,
			resizable: false,
			height: 260,
			width: 550
	});
	if(hasFlash) {
		var addpicturesHeight = 180;
	} else {
		var addpicturesHeight = 300;
	}

	$addPicturesDialog = $('<div class="dialog"></div>')
		.html($('#add_pictures_upload').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleAddPictures'),
			modal: true,
			draggable: true,
			resizable: false,
			height: addpicturesHeight,
			width: 400
	});

	$imageUploadProgress = $('<div class="dialog"></div>')
		.html($('#upload_image_progress').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleUploadingPhotos'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 150,
			width: 510
	});

	$addAlbumDialog2 = $('<div class="dialog"></div>')
		.html($('#add_album_list').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleAddAlbum'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 550,
			width: 510
	});

	$addAlbumDialog3 = $('<div class="dialog"></div>')
		.html($('#loading_albums').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleLoadingAlbums'),
			modal: true,
			draggable: true,
			closeOnEscape: false,
			resizable: false,
			height: 150,
			width: 510
	});


	$userOptionsDialog = $('<div class="dialog"></div>')
		.html($('#user_options').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleUserOptions'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 400,
			width: 550
	});

	$settingsDialog = $('<div class="dialog"></div>')
		.html($('#view_settings').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleSettings'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 535,
			width: 550
	});

	$scriptInfoDialog = $('<div class="dialog"></div>')
		.html($('#script_info').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleScriptInfo'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 350,
			width: 570
	});

	$reauthorizeDialog = $('<div class="dialog"></div>')
		.html($('#reauthorize').html())
		.dialog({
			autoOpen: false,
			title: wg_lang.get('DialogTitleReauthUser'),
			modal: true,
			draggable: true,
			resizable: false,
			height: 350,
			width: 550
	});


	$('#user_options_link').click(function () {
		showBigLoader();
		$.post('action.php', {'wg_action': 'getuserinfo' },
		function(json){
			if(!json.success) {
				var msg = wg_lang.get('EditAdminUserError');
				if(json.error.length > 0) {
					msg = json.error;
				}
				showNotification("Error", msg, 'red');
				return;
			}
			hideBigLoader();

			$userOptionsDialog.dialog('open');

			// prevent the default action, e.g., following a link
			$('.dialog #u_username').val(json.username);
			$('.dialog #u_uid').val(json.userid);

			$('.dialog #u_password_old').val('');
			$('.dialog #u_password_new').val('');
			$('.dialog #u_password_new_confirm').val('');
			resetButton('#save-user');
		}, 'json');
		
		return false;
	
	});

	$('#settings_link').click(function () {
		showBigLoader();
		$.post('action.php', {'wg_action': 'getsettings' },
		function(json){
			if(!json.success) {
				var msg = wg_lang.get('LoadSettingsError');
				if(json.error.length > 0) {
					msg = json.error;
				}
				hideBigLoader();
				showNotification("Error", msg, 'red');
				return;
			}
			hideBigLoader();

			$settingsDialog.dialog('open');

			// prevent the default action, e.g., following a link
			$('.dialog #s_appid').val(json.app_id);
			$('.dialog #s_appsecret').val(json.app_secret);
			$('.dialog #s_viewgallery').val(json.viewgallery);
			$('.dialog #s_source_facebook').attr('id', 's_source_facebook_dialog');
			$('.dialog #s_source_server').attr('id', 's_source_server_dialog');
			$('.dialog #s_fb_like').attr('id', 's_fb_like_dialog');
			$('.dialog #s_fb_comment').attr('id', 's_fb_comment_dialog');
			$('.dialog #s_images_start').attr('id', 's_images_start_dialog');
			$('.dialog #s_images_end').attr('id', 's_images_end_dialog');
			

			if(json.photo_source == 'facebook') {
				$('.dialog #s_source_original').val('facebook');
				$('.dialog #s_source_facebook_dialog').attr('checked', 'checked');
			} else {
				$('.dialog #s_source_original').val('server');
				$('.dialog #s_source_server_dialog').attr('checked', 'checked');
			}

			if(json.new_images_pos == 'end') {
				$('.dialog #s_images_end_dialog').attr('checked', 'checked');
			} else {
				$('.dialog #s_images_start_dialog').attr('checked', 'checked');
			}

			if(json.fb_like == 'on') {
				$('.dialog #s_fb_like_dialog').attr('checked', 'checked');
			} else {
				$('.dialog #s_fb_like_dialog').removeAttr('checked', 'checked');
			}

			if(json.fb_comment == 'on') {
				$('.dialog #s_fb_comment_dialog').attr('checked', 'checked');
			} else {
				$('.dialog #s_fb_comment_dialog').removeAttr('checked', 'checked');
			}

			resetButton('#save-settings');
		}, 'json');
		return false;
	});

	$('#script_info_link').click(function () {
		$scriptInfoDialog.dialog('open');
		$('.dialog #script_info_tabs').tabs();

		return false;
	});

	$('#facebook_users_link').click(function () {
		$.post('action.php', {'wg_action':'authorizedlist' }, function(json) {
			$facebookUsersDialog.dialog('open');
			if(json.user_html != "") {
				$('.dialog #no_authorized_users').hide();
				$('.dialog #authorized-users-ul').html(json.user_html);
				$('.a_authorized').html(json.user_list);
			} else {
				$('#no_authorized_users').show();
			}
		}, 'json');
		return false;
	});

	$('#add-album-continue').live('click', function () {
		var userid = $('.dialog #a_authorized').val();
		$addAlbumDialogFacebook.dialog('close');
		showBigLoader();
		$.post('action.php', {'wg_action': 'getalbums', 'userid': userid },
		function(json){
			if(!json.success) {
				var msg = wg_lang.get('LoadAlbumsForUserError');
				if(typeof json.error  != "undefined" && json.error.length > 0) {
					msg = json.error;
				}
				hideBigLoader();
				showNotification("Error", msg, 'red');
				return;
			}
			hideBigLoader();
			resetButton('.dialog #add-album-continue');
			$addAlbumDialog2.dialog('open');
			// prevent the default action, e.g., following a link
			$('.dialog .album-list').html(json.albums);
			$('.dialog #current-userid').val(userid);
		}, 'json');
	});
	
	$('#add-page-user').live('click', function () {
		var pageUrl = $('.dialog #page_url').val();
		$newPageDialog.dialog('close');
		showBigLoader();
		$.post('action.php', {'wg_action': 'savepage', 'pageUrl': pageUrl },
		function(json){
			hideBigLoader();
			if(!json.success) {
				if(json.invalid) {
					var msg = wg_lang.get('InvalidPageUrl');
				}else {
					var msg = wg_lang.get('LoadPageError');
				}
				if(json.error.length > 0) {
					msg = json.error;
				}
				showNotification("Error", msg, 'red');
				return;
			}
			hideBigLoader();
			resetButton('.dialog #add-page-user');
			showNotification("Success", wg_lang.get('PageAddedSuccess'), 'green');
			LoadAuthorizedList();
			$('#add-album').removeClass('button-disabled').removeClass('button-new-album-disabled').addClass('button-new-album');
		}, 'json');
	});
	
function refreshAlbums(albumid) {
		var form = "<form id='refresh_album_form' action='action.php' method='post' target='statusFrame'>";
		form += "<input type='hidden' name='wg_action' value='refreshalbums' />";

		if(typeof albumid !== "undefined") {
			form += "<input type='hidden' name='albumid' value='" + albumid + "' />";
		}

		form += "</form>";
		
		$addAlbumDialog3.dialog('open');
		$('.ui-dialog-titlebar .ui-dialog-titlebar-close').hide();
		
		$('.dialog #album-downloaded-count').text('1');
		$('.dialog #album-total').text('?');

		$(".dialog #progressbar").progressbar({ value: 0 });

		$('#refresh_album_form').remove();

		$('<iframe id="statusFrame" name="statusFrame"></iframe>').appendTo('body').hide();
		$(form).appendTo('body').hide();

		$('#refresh_album_form').submit();

}
	$('#refresh-wrapper').live('click', function () {
		refreshAlbums();
	});

	$('.refresh-album-box').live('click', function () {
		var id = $(this).attr('id').replace('refresh_', '');
		refreshAlbums(id);
	});

	$('#add-albums').live('click', function () {
		var form = "<form id='get_album_form' action='action.php' method='post' target='statusFrame'>";
		form += "<input type='hidden' name='wg_action' value='getalbum' />";
		form += "<input type='hidden' name='userid' value='" + $('.dialog #current-userid').val() + "' />";
		
		album.albums = [];
		
		$('.dialog .album-list input:checked').each(function() {
			album.albums.push($(this).val());
			form += "<input type='hidden' name='albumids[]' value='" + $(this).val() + "' />";
		});
		
		form += "</form>";

		$addAlbumDialog2.dialog('close');
		$addAlbumDialog3.dialog('open');
		$('.ui-dialog-titlebar .ui-dialog-titlebar-close').hide();
		resetButton('.dialog #add-albums');

		$('.dialog #album-downloaded-count').text('1');
		$('.dialog #album-total').text(album.albums.length);

		$(".dialog #progressbar").progressbar({ value: 0 });

		$('#get_album_form').remove();

		$('<iframe id="statusFrame" name="statusFrame"></iframe>').appendTo('body').hide();
		$(form).appendTo('body').hide();

		$('#get_album_form').submit();
	});

	$('#add-album').bind('buttonClicked', function () {

		$addAlbumDialog.dialog('open');

		resetButton('#add-album');
		// prevent the default action, e.g., following a link
		return false;
	});

	$('#add-pictures').bind('buttonClicked', function () {

		$addPicturesDialog.dialog('open');
		
		if(hasFlash) {
			addPicturesUploadWithFlash();
		} else{
			addPicturesUploadNoFlash();
		}

		resetButton('#add-pictures');
		// prevent the default action, e.g., following a link
		return false;
	});

	$('#add-album-facebook').live('buttonClicked', function () {
		if($(this).hasClass('button-disabled')) {
			showNotification(wg_lang.get('UserOrPageRequired'), wg_lang.get('UserOrPageRequiredDesc'), 'yellow');
			return false;
		}
		$addAlbumDialog.dialog('close');
		showBigLoader();
		
		$.post('action.php', {'wg_action':'checkfacebook' }, function(json) {
			if(json.hasusers) {
				$addAlbumDialogFacebook.dialog('open');
				$('.a_authorized').html(json.user_list);
			} else {
				showNotification('Set up',  wg_lang.get('FacebookAppSetup1') + '<a href="help/index.html" target="_blank">' + wg_lang.get('FacebookAppSetup2') + ' </a>', 'yellow');
			}
			hideBigLoader();
		}, 'json');
	

		resetButton('.dialog #add-album-facebook');
		// prevent the default action, e.g., following a link
		return false;
	});
	
	$('#upload-pics').live('click', function () {
		$('.dialog #image_multi_upload_form').submit();
	});

	$('#save-new-album').live('click', function () {
		$.post('action.php', {'wg_action': 'addalbum', 
			'name': $('.dialog #a_album_name').val(),
			'location': $('.dialog #a_album_location').val(),
			'desc': $('.dialog #a_album_desc').val()
			},
			function(json){
				
				hideBigLoader();
			
				if(!json.success) {
					var msg = wg_lang.get('AddAlbumError');
						if(typeof json.error != "undefined") {
							msg = json.error;
						}
					showNotification("Error", msg, 'red');
					resetButton('.dialog #save-new-album');
					$addAlbumDialogUpload.dialog('close');
					return;
				}
				showNotification("Success", wg_lang.get('AlbumAddSuccess'), 'green');
				resetButton('.dialog #save-new-album');
				$addAlbumDialogUpload.dialog('close');
				$addPicturesDialog.dialog('open');

				$('.dialog #a_album_list').append('<option value="' + json.album_id + '">'+ json.album_name + '</option>');
				$('.dialog #a_album_list').val(json.album_id);
				$('.dialog .swfupload-control-small').hide();
				$('.dialog #upload-pics').show();
				$('.dialog .upload-control').show();

				$('.dialog #image_upload_field').MultiFile({ 
					list: '.dialog #file_upload_list'
				}); 

				$('.dialog #image_multi_upload_form').ajaxForm({
					'success': function() { 
					$addPicturesDialog.dialog('close');
					LoadAlbumList();
					return false;
				},
					'error': function (blah) {
				}
				}); 

		}, 'json');
	});

	$('#add-album-upload').live('buttonClicked', function () {

		$addAlbumDialog.dialog('close');
		$addAlbumDialogUpload.dialog('open');
		$addAlbumDialogUpload.dialog({ height: 360 });
		
		$('.dialog #a_album_id').val('0');
		$('.dialog #a_album_name').val('');
		$('.dialog #a_album_location').val('');
		$('.dialog #a_album_desc').val('');
		$('.dialog #a_album_name').removeAttr('readonly').removeAttr('disabled');
		$('.dialog #a_album_desc').removeAttr('readonly').removeAttr('disabled');
		$('.dialog #a_album_location').removeAttr('readonly').removeAttr('disabled');

		$('.dialog .fb_row').hide();
		
		resetButton('.dialog #add-album-upload');

		if(hasFlash) {
			addAlbumHasFlash();
			return false;
		}

		addAlbumNoFlash();
		return false;
	});


	$('#no_album').show();
	$('#no_pictures').hide();
	$('#picture-list-ul').hide();
	$('#images-for').hide();
	$('#loading_pictures').hide();

	LoadAuthorizedList();

	if(typeof forceRefreshAlbums == "undefined" || forceRefreshAlbums == false) {
		LoadAlbumList();
	} else {
		refreshAlbums();
	}

});


function LoadAuthorizedList() {
	/*$.post('action.php', {'wg_action':'authorizedlist' }, function(json) {
		if(json.user_html != "") {
			$('#no_users').hide();
			$('#user-list-ul').html(json.user_html);
			$('.a_authorized').html(json.user_list);
		} else {
			$('#no_users').show();
		}
	}, 'json');*/
}

function LoadAlbumList() {
	$('#loading_albums_main').show();
	$('#no_albums').hide();

	$.post('action.php', {'wg_action':'albumlist' }, function(json) {
		if(json.album_html != "") {
			$('#loading_albums_main').hide();
			$('#album-list-ul').html(json.album_html);
			$('.a_album_list').html(json.album_list);
			$('.a_album_list_select').prepend('<option value="0" selected="selected">Select an album</option>');

			if($('#album-list-ul li').size() > 0) {
				$('#album-list-ul').show();
			} else {
				$('#album-list-ul').hide();
				$('#no_albums').show();
			}

			$('#album-list-ul').sortable({ update: function  (event, ui) {
				showBigLoader();
				var result = $('#' + $(ui.item).parent().attr('id')).sortable('serialize');
				result += '&wg_action=sortalbums';

				$.post('action.php', result, function(json) {
					hideBigLoader();
					if(json.success) {
						showNotification("Success",wg_lang.get('AlbumOrderSuccess'), 'green');
					} else {
						showNotification("Error", wg_lang.get('AlbumOrderError'), 'red');
					}
				}, 'json');
			},
			start: function () {
			//	$('.album-action-box').hide();
			},
				handle: '.img-drag' });

		} else {
			$('#no_albums').show();
			$('#album-list-ul').hide();
			$('#loading_albums_main').hide();
		}
	}, 'json');
}


function loadImages(albumid) {
	var albumname = $('#album_row_' + albumid+ ' .album-info-box .album-name').text();
	$('#no_pictures').hide();
	$('#no_album').hide();
	$('#loading_pictures').find('.album-name').text(albumname);
	$('#loading_pictures').show();
	$('#images-for .images-for-album-name').text(albumname);
	$('#images-for').show();
	
	$.post('action.php', {'wg_action':'imagelist', 'albumid': albumid }, function(json) {
		if(json.album_html != "") {
			$('#loading_pictures').hide();
			$('#picture-list-ul').html(json.album_html);
			$('#current_album').val(json.albumid);

			if($('#picture-list-ul li').size() > 0) {
				$('#picture-list-ul').show();
			} else {
				$('#picture-list-ul').hide();
				$('#no_pictures').show();
			}

			$('#picture-list-ul').sortable({ update: function  (event, ui) {
				showBigLoader();
				var result = $('#' + $(ui.item).parent().attr('id')).sortable('serialize');
				result += '&wg_action=sortpictures';

				$.post('action.php', result, function(json) {
					hideBigLoader();
					if(json.success) {
						showNotification("Success",wg_lang.get('ImageOrderSuccess'), 'green');
					} else {
						showNotification("Error", wg_lang.get('ImageOrderError'), 'red');
					}
				}, 'json');
			},
			placeholder: 'image_placeholder',
			start: function () {
				$('.image_box').hide();
				$('.image_name_box').hide();
			}
			});

		} else {
			$('#current_album').val("0");
			$('#no_pictures').show();
			$('#picture-list-ul').hide();
			$('#loading_pictures').hide();
		}
	}, 'json');
}


var pgLoader = false;
function showBigLoader() {
	$("#pg_loader").fadeIn("fast");
}

function hideBigLoader() {
		var loader = $("#pg_loader");
		loader.stop();
		loader.fadeOut("fast");
}



function addAlbumNoFlash() {
	$('.dialog #save-album').hide();
	$('.dialog .swfupload-control').hide();
	$('.dialog #save-new-album').show();
}

function addAlbumHasFlash() {
	
	$('.dialog .swfupload-control').show();
	$('.dialog .swfupload-control').attr('id', 'swfupload-control');
	$('.dialog .swfupload-control > span').attr('id', 'spanButtonPlaceholder');
	$('.dialog #save-album').hide();
	$('.dialog #save-new-album').hide();
	
	if(isMac) {
		var upload_img = "upload_img_mac.png";
	} else {
		var upload_img = "upload_img.png";
	}
	 $('#swfupload-control').swfupload({
		// Backend Settings
		upload_url: "action.php",    // Relative to the SWF file (or you can use absolute paths)
		
		// File Upload Settings
		file_size_limit : "102400", // 100MB
		file_types : "*.jpg;*.png;*.gif;*.jpeg",
		file_types_description : "Image Files",
		file_upload_limit : "500",
		file_queue_limit : "0",

		// Button Settings
		button_image_url : "images/" + upload_img, // Relative to the SWF file
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: 258,
		button_height: 35,
		post_params: {
			"PHPSESSID": wg_session_id, 

			'album_id': $('.dialog #a_album_id').val(),
			'album_name': $('.dialog #a_album_name').val(),
			'album_location': $('.dialog #a_album_location').val(),
			'album_desc': $('.dialog #a_album_desc').val(),

			'wg_action': 'imageupload'
			},
		// Flash Settings
		flash_url : "js/swfupload/swfupload.swf"
		
	});

	$('#swfupload-control').unbind('fileDialogComplete').unbind('fileQueued').unbind('uploadStart').unbind('uploadSuccess').unbind('uploadProgress').unbind('uploadComplete');

	// assign our event handlers
	$('#swfupload-control')
		
		.bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){ 
			if(numFilesQueued > 0) {
				$(this).swfupload('removePostParam', 'album_name');
				$(this).swfupload('addPostParam', 'album_name', $('.dialog #a_album_name').val());

				$(this).swfupload('removePostParam', 'album_id');
				$(this).swfupload('addPostParam', 'album_id', $('.dialog #a_album_id').val());

				$(this).swfupload('removePostParam', 'album_location');
				$(this).swfupload('addPostParam', 'album_location', $('.dialog #a_album_location').val());

				$(this).swfupload('removePostParam', 'album_desc');
				$(this).swfupload('addPostParam', 'album_desc', $('.dialog #a_album_desc').val());


				$imageUploadProgress.dialog('open');
				$('.ui-dialog-titlebar .ui-dialog-titlebar-close').hide();
				
				$('.dialog #image-total').text(numFilesSelected);
				$('.dialog #image-downloaded-count').text('0');
			}
		})

		.bind('fileQueued', function(event, file){  
			 // start the upload since it's queued  
			$(this).swfupload('startUpload'); 
		})


		.bind('uploadStart', function(event, file){ 
			$(".dialog #image-progressbar").progressbar({ value: 0 });
			$('.dialog #current-image-downloading').text(file.name);
			var count = parseInt($('.dialog #image-downloaded-count').text());
			count = count+1;
			$('.dialog #image-downloaded-count').text(count);
			
		})

		.bind('uploadSuccess', function(event, file, serverData){
			var json = jQuery.parseJSON(serverData);
			$('.dialog #a_album_id').val(json.album_id);
			if(json.album_name != '') {
				$('.dialog #a_album_name').val(json.album_name);
			}
			$(this).swfupload('removePostParam', 'album_id');
			$(this).swfupload('addPostParam', 'album_id', json.album_id);
			$(this).swfupload('startUpload');
		})



		.bind('uploadProgress', function(event, file, bytesLoaded){ 
			//Show Progress 

			var percentage=Math.round((bytesLoaded/file.size)*100); 
			$(".dialog #image-progressbar").progressbar({ value: percentage });
		})

		.bind('uploadComplete', function(event, file){
			var stats = $.swfupload.getInstance('#swfupload-control').getStats();
			if (stats.files_queued == 0) {
				// start the upload (if more queued) once an upload is complete
				$imageUploadProgress.dialog('close');
				$addAlbumDialogUpload.dialog('close');
				$('.ui-dialog-titlebar .ui-dialog-titlebar-close').show();
				LoadAlbumList();
			}
		});
	
	// prevent the default action, e.g., following a link
	return false;
}


function addPicturesUploadWithFlash() {
	
	$('.dialog .upload-control').hide();
	$('.dialog #upload-pics').hide();

	$('.dialog .swfupload-control-small').show();
	$('.dialog .swfupload-control-small').attr('id', 'swfupload-control-small');
	$('.dialog .swfupload-control-small > span').attr('id', 'spanButtonPlaceholder');
	
	if(isMac) {
		var upload_img = "upload_img_small_mac.png";
	} else {
		var upload_img = "upload_img_small.png";
	}
	 $('#swfupload-control-small').swfupload({
		// Backend Settings
		upload_url: "action.php",    // Relative to the SWF file (or you can use absolute paths)
		
		// File Upload Settings
		file_size_limit : "102400", // 100MB
		file_types : "*.jpg;*.png;*.gif;*.jpeg",
		file_types_description : "Image Files",
		file_upload_limit : "500",
		file_queue_limit : "0",

		// Button Settings
		button_image_url : "images/" + upload_img, // Relative to the SWF file
		button_placeholder_id : "spanButtonPlaceholder",
		button_width: 155,
		button_height: 35,
		post_params: {
			"PHPSESSID": wg_session_id, 

			'album_id': $('.dialog #a_album_list').val(),

			'wg_action': 'imageupload'
			},
		// Flash Settings
		flash_url : "js/swfupload/swfupload.swf"
		
	});

	$('#swfupload-control-small').unbind('fileDialogComplete').unbind('fileQueued').unbind('uploadStart').unbind('uploadSuccess').unbind('uploadProgress').unbind('uploadComplete');

	// assign our event handlers
	$('#swfupload-control-small')
		
		.bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){ 
			if(numFilesSelected == 0) {
				return false;
			}

			$(this).swfupload('removePostParam', 'album_id');
			$(this).swfupload('addPostParam', 'album_id', $('.dialog #a_album_list').val());

	
			$imageUploadProgress.dialog('open');
			$('.ui-dialog-titlebar .ui-dialog-titlebar-close').hide();
			
			$('.dialog #image-total').text(numFilesSelected);
			$('.dialog #image-downloaded-count').text('0');
		})

		.bind('fileQueued', function(event, file){  
			 // start the upload since it's queued  
			$(this).swfupload('startUpload'); 
		})


		.bind('uploadStart', function(event, file){ 
			$(".dialog #image-progressbar").progressbar({ value: 0 });
			$('.dialog #current-image-downloading').text(file.name);
			var count = parseInt($('.dialog #image-downloaded-count').text());
			count = count+1;
			$('.dialog #image-downloaded-count').text(count);
			
		})

		.bind('uploadSuccess', function(event, file, serverData){ 
			var json = jQuery.parseJSON(serverData);
			$('.dialog #a_album_id').val(json.album_id);
			$(this).swfupload('removePostParam', 'album_id');
			$(this).swfupload('addPostParam', 'album_id', json.album_id);
			$(this).swfupload('startUpload');
		})



		.bind('uploadProgress', function(event, file, bytesLoaded){ 
			//Show Progress 

			var percentage=Math.round((bytesLoaded/file.size)*100); 
			$(".dialog #image-progressbar").progressbar({ value: percentage });
		})

		.bind('uploadComplete', function(event, file){
			var stats = $.swfupload.getInstance('#swfupload-control-small').getStats();
			if (stats.files_queued == 0) {
			// start the upload (if more queued) once an upload is complete
				loadImages($('.dialog #a_album_list').val());
				$imageUploadProgress.dialog('close');
				$addPicturesDialog.dialog('close');
				$('.ui-dialog-titlebar .ui-dialog-titlebar-close').show();
				LoadAlbumList();
			}
		});
}

function addPicturesUploadNoFlash () {
		$('.dialog .swfupload-control-small').hide();
		$('.dialog #upload-pics').show();
		$('.dialog .upload-control').show();

		$('.dialog #image_upload_field').MultiFile({ 
			list: '.dialog #file_upload_list',
			max: 5
		}); 

		$('.dialog #image_multi_upload_form').ajaxForm({
			'success': function() { 
			$addPicturesDialog.dialog('close');
			LoadAlbumList();
			return false;
		},
			'error': function (blah) {
		}
		}); 
}