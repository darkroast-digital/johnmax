<?php
include(dirname(__FILE__) . '/access.php');
$settings = wg_settings::getSettings();
$users = wg_data::row("select count(id) as count_rows from :authorized_table");
$user_count = 0;
if(!empty($users)) {
	$user_count = (int)$users['count_rows'];
}

?><!doctype html>
<html>
<head>
	<title><?php wg_lang::out('AdminTitle'); ?></title>
	<link href="css/main.css" rel='stylesheet' type='text/css' />
	<link href="css/button.css" rel='stylesheet' type='text/css' />
	<link type="text/css" rel="stylesheet" href="css/jquery-ui.css" />
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/swfupload/swfobject.js"></script>
	<script type="text/javascript" src="js/swfupload/swfupload.js"></script>
	<script type="text/javascript" src="js/swfupload/swfupload.queue.js"></script>
	<script type="text/javascript" src="js/jquery.swfupload.js"></script>
	<script type="text/javascript" src="js/jquery.multifile.js"></script>
	<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript">

<?php
$jsVars = wg_lang::getJs();
echo '$(document).ready(function() {';
foreach($jsVars as $name => $value) {
	echo "wg_lang['" . $name . "'] = '".str_replace("'", "\\'", $value)."';\n";
}
echo '});';

if(wg_settings::hasAppId()) {
	echo "var appIdSet = true;\n";
}else{
	echo "var appIdSet = false;\n";
}

if(wg_settings::hasAppSecret()) {
	echo "var appSecret = true;\n";
}else{
	echo "var appSecret = false;\n";
}

echo "var userCount = ".$user_count.";\n";

echo "var authURL = '".wg_facebook::requestAuth() ."';\n";

if(isset($_SESSION['new_authorized']) && $_SESSION['new_authorized'] == true) {
	$_SESSION['new_authorized'] = false;
	unset($_SESSION['new_authorized']);
	echo '$(document).ready(function() { showNotification("Success", wg_lang.userAuthorizedSuccess, "green"); });';
}

if(UPDATE_SUCCESS) {
	echo '$(document).ready(function() { showNotification("Success", wg_lang.get("versionUpdated", {version_number: "'.wg_update::versionFormat(WG_SCRIPT_VERSION).'"}), "green"); });';
}

if(UPDATE_FORCE_ALBUM_REFRESH) {
	echo 'var forceRefreshAlbums = true;'."\n";
} else {
	echo 'var forceRefreshAlbums = false;'."\n";
}

echo 'var wg_session_id = "'.session_id().'";'."\n";
if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mac os") !== false) {
	echo 'var isMac = true;'."\n";
}else {
	echo 'var isMac = false;'."\n";
}


$urlFopen = (bool) ini_get('allow_url_fopen');

if(!$urlFopen) {
	echo "$(document).ready(function() { showCriticalError(wg_lang.warning, wg_lang.errorNoUrlFopen); });\n";
}
?>
</script>

	<script type="text/javascript" src="js/button.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
<?php

if(isset($settings['install_time']) && isset($settings['rate_popup']) && $settings['rate_popup'] == "no" && ($settings['install_time']+604800) < time()){
	$rate_popup = wg_settings::update('settings', array('value' => 'yes'), 'name', 'rate_popup');
}
?>
</head>

<body>
<div id="container1">
<div id="top-menu"><a href="<?php echo @$settings['viewgallery']; ?>" target="_blank" id="gallery_link" style="border-right: 1px solid #C0C0C0;"><?php wg_lang::out('MenuViewGallery'); ?></a><a href="" id="script_info_link" style="border-right: 1px solid #C0C0C0;"><?php wg_lang::out('MenuScriptInfo'); ?></a><a href="" id="facebook_users_link" style="border-right: 1px solid #C0C0C0;"><?php wg_lang::out('MenuFacebookUsersPages'); ?></a><a href="" id="settings_link" style="border-right: 1px solid #C0C0C0;"><?php wg_lang::out('MenuSettings'); ?></a><a href="" id="user_options_link" style="border-right: 1px solid #C0C0C0;"><?php wg_lang::out('MenuUserOptions'); ?></a><a href="help/index.html" target="_blank" id="help_link" style="border-right: 1px solid #C0C0C0;"><?php wg_lang::out('MenuHelp'); ?></a><a href="logout.php"><?php wg_lang::out('MenuLogout'); ?></a></div>
	<div id="header">
		<img src="images/logo.png" alt="<?php wg_lang::out('WebGalleryLogoAlt'); ?>" /> <small id="current_version"><?php wg_lang::out('VersionNumber', array('number' => wg_update::versionFormat(WG_SCRIPT_VERSION))); ?></small>
	</div>

	<div id="new-buttons">
		<div class="button button-new-album"  id="add-album"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonTopAddAnAlbum'); ?></div><div class="right-side-button"></div></div>

		<div class="button button-add-pictures"  id="add-pictures"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonTopUploadPictures'); ?></div><div class="right-side-button"></div></div>


	</div>

	<div id="notification-area">

		<div id="notification-outer">
			<div id="notification-inner" style="display: none" class="yellow">
				<span class="title"></span>
				<span class="message"></span>
				<a href="#" id="notification-close"><img src="images/notification_close.png" width="18" height="18" border="0" alt=""></a>
			</div>
		</div>

		<div id="notification-error-outer">
			<div id="notification-error-inner" style="display: none" class="red_big">
				<a href="#" id="notification-error-close"><img src="images/notification_close.png" width="18" height="18" border="0" alt=""></a>
				<span class="title"></span>
				<span class="message"></span>
			</div>
		</div>
	</div>
</div>
<div id="container2">
	<div id="content">
		<div id="content-header">
			<div id="users-header">
				<?php wg_lang::out('AlbumsColumnHeading'); ?>

			</div>
			<div id="albums-header">
					<?php wg_lang::out('PicturesForAlbum', array('album_name'=> '"<span class="images-for-album-name"></span>"', 'if_album_selected' => '<span id="images-for">', 'end_if' => '</span>')); ?>
			</div>

		</div>

		<div id="content-main">
			<div id="album-list">
						<span class="action-desc " id="action-edit"><span class="action-desc-text"><?php wg_lang::out('EditAlbum'); ?></span></span>
						<span class="action-desc" id="action-delete"><span class="action-desc-text"><?php wg_lang::out('DeleteAlbum'); ?></span></span>
						<span class="action-desc" id="action-refresh"><span class="action-desc-text"><?php wg_lang::out('RefreshAlbum'); ?></span></span>
				<ul id="album-list-ul" class="sortable">

				</ul>
				<div class="list_msg" id="no_albums">
					<div class="message-body"><?php wg_lang::out('NoAlbums'); ?></div>
				</div>
				<div class="list_msg" id="loading_albums_main">
					<div class="message-body"><img src="images/loader.gif" /> <?php wg_lang::out('LoadingAlbums'); ?></div>
				</div>
			</div>

			<div id="picture-list">
				<input type="hidden" id="current_album" name="current_album" value="0" />
				<div style="clear:both;">
				</div>

				<div class="list_msg" id="no_pictures">
					<div class="message-body"><?php wg_lang::out('AlbumHasNoPictures'); ?></div>
				</div>

				<div class="list_msg" id="no_album">
					<div class="message-body"><?php wg_lang::out('NoAlbumSelected'); ?></div>
				</div>
				<div class="list_msg" id="loading_pictures">
					<div class="message-body"><img src="images/loader.gif" /> <?php wg_lang::out('LoadingPictures'); ?></div>
				</div>

				<span class="action-image" id="action-image-rotateleft"><span class="action-image-text"><?php wg_lang::out('ImageRotateLeft'); ?></span></span>
				<span class="action-image" id="action-image-rotateright"><span class="action-image-text"><?php wg_lang::out('ImageRotateRight'); ?></span></span>
				<span class="action-image" id="action-image-edit"><span class="action-image-text"><?php wg_lang::out('ImageEditName'); ?></span></span>
				<span class="action-image" id="action-image-delete"><span class="action-image-text"><?php wg_lang::out('ImageDelete'); ?></span></span>
				<span class="action-image" id="action-image-zoom"><span class="action-image-text"><?php wg_lang::out('ImageZoom'); ?></span></span>
				<span class="action-image" id="action-image-cover"><span class="action-image-text"><?php wg_lang::out('ImageMakeCover'); ?></span></span>


				<ul id="picture-list-ul">



				</ul>
			</div>
		</div>


	</div>

<div style="clear: both;"></div>


</div>


<div style="display: none;">

<div id="auth_user">
	<p><?php wg_lang::out('FacebookAuthUser1'); ?></p>
	<ul><li><a href="http://www.facebook.com/" target="_blank"><?php wg_lang::out('FacebookAuthUser2'); ?></a></li></ul>
	<p><?php wg_lang::out('FacebookAuthUser3'); ?></p>

	<div class="button button-continue" id="auth-user-2"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonContinue'); ?></div><div class="right-side-button"></div></div>

</div>

<div id="page_user">
	<p><?php wg_lang::out('AddFacebookPageField'); ?></p>
	<input type="text" name="page_url" id="page_url"  style="width: 500px;" /><br/>
	<small>Standard page example: http://www.facebook.com/pages/Apples/11244587738<br />
	Page with username example: http://www.facebook.com/cocacola
	</small>
		<br />
	<div class="button button-continue" id="add-page-user"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonContinue'); ?></div><div class="right-side-button"></div></div>

</div>


<div id="add_album">
	<p style="text-align: center;" ><?php wg_lang::out('AddNewAlbumLabel'); ?></p>

	<div class="button button-continue" id="add-album-upload" style="margin-left: 20px;"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('AddAlbumUploadPhotosButton'); ?></div><div class="right-side-button"></div></div>

	<hr>

	<div class="button button-facebook" id="add-album-facebook" style="margin-left: 28px;"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('AddAlbumFromFacebookButton'); ?></div><div class="right-side-button"></div></div>
</div>

<div id="facebook_users">

	<div class="button <?php if(!wg_settings::hasAppId() || !wg_settings::hasAppSecret()) { echo "button-auth-user-disabled button-disabled"; } ?> " id="auth-user"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('AddFacebookUser'); ?></div><div class="right-side-button"></div></div>

	<div class="button button-page-user" id="page-user"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('AddFacebookLikePage'); ?></div><div class="right-side-button"></div></div>

<hr>
<strong><?php wg_lang::out('CurrentUsersAndPages'); ?></strong><br />
<div id="no_authorized_users"><?php wg_lang::out('NoUsersOrPages'); ?></div>
<ul id="authorized-users-ul">
</ul>
<div class="button button-verify" id="verify-button" style="margin-left: 28px;"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('VerifyFacebookUsers'); ?></div><div class="right-side-button"></div></div>

</div>



<div id="add_album_facebook">
	<table border="0">
		<tr>
			<td><?php wg_lang::out('FacebookAddAlbumSelectUser'); ?></td></tr><tr>
			<td><select id="a_authorized" name="a_authorized"  style="width: 300px;" class="a_authorized"></select></td>
		</tr>
		<tr>

			<td>
				<div class="button button-continue" id="add-album-continue"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonContinue'); ?></div><div class="right-side-button"></div></div>

		</td>
		</tr>
	</table>
</div>

<div id="add_album_list">
	<input type="hidden" id="current-userid" value="" />
	<table border="0">
		<tr>
			<td><?php wg_lang::out('AlbumsForFbUser', array('facebook_user'=>'<span class="current-user-name"></span>')); ?></td></tr><tr>
			<td>

			<ul class="album-list">
			</ul>
			</td>
		</tr>
		<tr>

			<td>
				<div class="button button-continue" id="add-albums"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonUseSelectedAlbums'); ?></div><div class="right-side-button"></div></div>

		</td>
		</tr>
	</table>
</div>

<div id="loading_albums">
	<center><img src="images/loader.gif" /> <?php wg_lang::out('LoadingAlbumsFromFacebook', array('current_album_number' => '<span id="album-downloaded-count"></span>', 'total_album_number' => '<span id="album-total"></span>')); ?><br />
	<?php wg_lang::out('CurrentLoadingAlbum'); ?><b> <span id="current-album-downloading"></span></b><br /><?php wg_lang::out('LoadingStatus'); ?> <b><span id="current-album-status"></span></b>
	</center>
	<div id="progressbar"></div>
</div>

<div id="upload_image_progress">
	<center><img src="images/loader.gif" /><?php wg_lang::out('UploadingImagesPleaseWait', array('current_image_number' => '<span id="image-downloaded-count"></span>', 'total_image_number' => '<span id="image-total"></span>')); ?><br />
	<?php wg_lang::out('CurrentUploadingImage'); ?> <b> <span id="current-image-downloading"></span></b>
	</center>
	<div id="image-progressbar"></div>
</div>

<div id="reauthorize">
	<p><?php wg_lang::out('Reauthorize1'); ?></p>
	<p><?php wg_lang::out('Reauthorize2', array('facebook_user' => '<strong><span class="reauthorize-user-name"></span></strong>')); ?></p>
	<ul><li><a href="http://www.facebook.com/" target="_blank"><?php wg_lang::out('VisitFacebook'); ?></a><?php wg_lang::out('VisitFacebook2'); ?></li></ul>
	<p><?php wg_lang::out('Reauthorize3'); ?></p>

	<div class="button button-continue" id="auth-user-2"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonReauthorize'); ?></div><div class="right-side-button"></div></div>

</div>




<div id="view_settings">
	<table border="0">
		<tr>
			<td><?php wg_lang::out('SettingsApplicationID'); ?></td></tr><tr>
			<td><input type="text" id="s_appid" name="s_appid"  style="width: 500px;" class="s_appid"  value="" /></td>
		</tr>
		<tr>
			<td><?php wg_lang::out('SettingsApplicationSecret'); ?></td></tr><tr>
			<td><input type="text" id="s_appsecret" name="s_appsecret"  style="width: 500px;" class="s_appsecret" value="" /></td>
		</tr>

			<td><?php wg_lang::out('SettingsViewGalleryUrl'); ?></td></tr><tr>
			<td><input type="text" id="s_viewgallery" name="s_viewgallery"  style="width: 500px;" class="s_viewgallery" value="" /></td>
		</tr>

		<tr><td><hr class="settings" /></td></tr><tr>

		<tr>
			<td><?php wg_lang::out('AddNewImagesToWhere'); ?>:</td></tr><tr>
	<tr>		<td><input type="radio" id="s_images_end" name="s_images_position" value="end" /> <label for="s_images_end_dialog"><?php wg_lang::out('AddImagesToEnd'); ?></label></td>		</tr>
		<tr>		<td><input type="radio" id="s_images_start" name="s_images_position" value="start"  /> <label for="s_images_start_dialog"><?php wg_lang::out('AddImagesToBeginning'); ?></label></td>		</tr>



	<tr><td><hr class="settings" /></td></tr><tr>

				<tr>
			<td><?php wg_lang::out('ImageSource'); ?>:</td></tr><tr>
			<td><input type="hidden" id="s_source_original" value="" />
			<input type="radio" id="s_source_server" name="s_source" /> <label for="s_source_server_dialog"><strong><?php wg_lang::out('PhotoSourceServer'); ?></strong> <?php wg_lang::out('PhotoSourceServerLabel'); ?></label><br />
			<input type="radio" id="s_source_facebook" name="s_source" /> <label for="s_source_facebook_dialog"><strong><?php wg_lang::out('PhotoSourceFacebook'); ?></strong> <?php wg_lang::out('PhotoSourceFacebookLabel'); ?></label>

</td>
		</tr>
<tr><td><hr class="settings" /></td></tr><tr>
		<tr>		<td><input type="checkbox" id="s_fb_like" name="s_fb_like" /> <label for="s_fb_like_dialog"><?php wg_lang::out('EnableFacebookLike'); ?></label></td>		</tr>
		<tr>		<td><input type="checkbox" id="s_fb_comment" name="s_fb_comment" /> <label for="s_fb_comment_dialog"><?php wg_lang::out('EnableFacebookComment'); ?></label></td>		</tr>

	</table>
	<div class="button button-save" id="save-settings"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonSaveSettings'); ?></div><div class="right-side-button"></div></div>

</div>


<div id="add_pictures_upload">
	<p><?php wg_lang::out('UploadPicturesSelectAlbum'); ?></p>
	<form action="action.php" method="POST" enctype="multipart/form-data" id="image_multi_upload_form">
		<input type="hidden" name="wg_action" value="imageuploadmulti" />
		<select id="a_album_list" name="a_album_list"  style="width: 300px;" class="a_album_list"></select>
		<br /><br />
		<div class="swfupload-control-small">
			<span></span>
		</div>
		<div class="upload-control">
			<div id="upload_wrap">
			<?php wg_lang::out('SelectImagesBrowse'); ?><br />
				<input id="image_upload_field" class="" type="file" name="image_upload_field[]">
			</div>
				<div id="file_upload_list"></div>
				</div>
				<div class="button button-continue" id="upload-pics"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonUploadImages'); ?></div><div class="right-side-button"></div></div>
	</form>
</div>


<div id="add_album_upload">
	<table border="0">
		<tr>
			<td><?php wg_lang::out('AddAlbumName'); ?></td>
		</tr>
		<tr>
			<td><input type="text" id="a_album_name" name="a_album_name"  style="width: 500px;" class="a_album_name"  value="" />
			<input type="hidden" id="a_album_fbname" name="a_album_fbname" class="a_album_fbname"  value="" />
			</td>
		</tr>
		<tr class="fb_row">
			<td><input type="checkbox" id="before_use_name_fb" name="use_name_fb" value="yes" /> <label for="use_name_fb"><?php wg_lang::out('UseFacebookName'); ?></label></td>
		</tr>
		<tr>
			<td><?php wg_lang::out('AddAlbumLocation'); ?></td></tr><tr>
			<td><input type="text" id="a_album_location" name="a_album_location"  style="width: 500px;" class="a_album_location" value="" />
			<input type="hidden" id="a_album_fblocation" name="a_album_fblocation" class="a_album_fblocation"  value="" /></td>
		</tr>

		<tr class="fb_row">
			<td><input type="checkbox" id="before_use_location_fb" name="use_location_fb" value="yes" /> <label for="use_location_fb"><?php wg_lang::out('UseFacebookLocation'); ?></label></td>
		</tr>
		<tr>
			<td><?php wg_lang::out('AddAlbumDescription'); ?></td></tr><tr>
			<td><textarea name="a_album_desc" id="a_album_desc" style="width: 500px; height: 100px;"></textarea>
			<input type="hidden" id="a_album_fbdesc" name="a_album_fbdesc" class="a_album_fbdesc"  value="" /></td>
		</tr>

		<tr class="fb_row">
			<td><input type="checkbox" id="before_use_desc_fb" name="use_desc_fb" value="yes" /> <label for="use_desc_fb"><?php wg_lang::out('UseFacebookDesc'); ?></label></td>
		</tr>
		<tr>
			<td>
				<input type="hidden" id="a_album_id" name="a_album_id" value="0" />

				<div class="swfupload-control">
					<span></span>
				</div>

<div class="button button-save" id="save-album"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonSaveAlbum'); ?></div><div class="right-side-button"></div></div>
<div class="button button-save" id="save-new-album"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonSaveNewAlbum'); ?></div><div class="right-side-button"></div></div>

			</td>
		</tr>
	</table>
</div>


<div id="edit_image">
	<table border="0">
		<tr>
			<td><?php wg_lang::out('EditImageName'); ?></td></tr><tr>
			<td><input type="text" id="i_image_name" name="i_image_name"  style="width: 500px;" class="i_image_name"  value="" /></td>
		</tr>
		<tr>
			<td>
				<input type="hidden" id="i_image_id" name="i_image_id" value="0" />

<div class="button button-save" id="save-image"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonSaveImage'); ?></div><div class="right-side-button"></div></div>
			</td>
		</tr>
	</table>
</div>


<div id="user_options">
	<table border="0">
		<tr>
			<td><?php wg_lang::out('EditUserUsername'); ?></td></tr><tr>
			<td><input type="text" id="u_username" name="u_username"  style="width: 500px;" class="u_username"/></td>
		</tr>
		<tr>

			<td><br/><b><?php wg_lang::out('EditUserPasswordInstructions'); ?></td></tr><tr>

			<td><?php wg_lang::out('EditUserCurrentPassword'); ?></td></tr><tr>
			<td><input type="password" name="u_password_old" id="u_password_old" style="width: 500px; "/></td>
		</tr><tr>
			<td><?php wg_lang::out('EditUserNewPassword'); ?></td></tr><tr>
			<td><input type="password" name="u_password_new" id="u_password_new" style="width: 500px;"/></td>
		</tr><tr>
			<td><?php wg_lang::out('EditUserConfirmNewPassword'); ?></td></tr><tr>
			<td><input type="password" name="u_password_new_confirm" id="u_password_new_confirm" style="width: 500px; "/></td>

		<tr>

			<td>
				<div class="button button-save" id="save-user"><div class="button-icon-bg" ><div class="button-icon"></div><div class="button-icon-loading" style="display: none;" ></div></div><div class="label"><?php wg_lang::out('ButtonSaveChanges'); ?></div><div class="right-side-button"></div></div>

</td>
		</tr>
	</table>
</div>

<div id="script_info">
	<p><?php wg_lang::out('ScriptInfoIntro'); ?></p>
<div id="script_info_tabs">
	<ul>
		<li><a href="#tabs-1">Insert to Wordpress</a></li>
		<li><a href="#tabs-2">Insert a Gallery</a></li>
		<li><a href="#tabs-3">Insert an Album</a></li>
		<li><a href="#tabs-4">Insert a Slider</a></li>
		<li><a href="#tabs-5">Full Path</a></li>
	</ul>
	<div id="tabs-1">
		<p><?php wg_lang::out('ScriptInfoWordpress'); ?></p>
		<code class="insert_code">
		<?php
			echo str_replace("\\", "/", dirname(dirname(__FILE__)))."/";
		?>
		</code>

	</div>
	<div id="tabs-2">
		<p><?php wg_lang::out('ScriptInfoGallery'); ?></p>
	<code class="insert_code">
	<?php
		echo "&lt;?php<br />
			include(\"".str_replace("\\", "/", dirname(dirname(__FILE__)))."/webgallery.php\");<br />
			?&gt;";
	?>
	</code>
	</div>
	<div id="tabs-3">


	<p><?php wg_lang::out('ScriptInfoAlbum'); ?></p>
	<select id="insert_album_select" name="insert_album_select"  style="width: 300px;" class="a_album_list a_album_list_select"></select><br /><br />
	<code class="insert_code" id="insert_album_code">
	<?php
		echo "&lt;?php<br />
			\$webgallery_album_id = '<span id='insert_album_id'></span>';<br />
			include(\"".str_replace("\\", "/", dirname(dirname(__FILE__)))."/webgallery.php\");<br />
			?&gt;";
	?>
	</code>
	</div>
	<div id="tabs-4">



	<p><?php wg_lang::out('ScriptInfoSlider'); ?></p>

	<select id="insert_slider_select" name="insert_slider_select"  style="width: 300px;" class="a_album_list a_album_list_select"></select><br /><br />
	<code class="insert_code" id="insert_slider_code">
	<?php
		echo "&lt;?php<br />
			\$webgallery_album_id = '<span id='insert_slider_id'></span>';<br />
			include(\"".str_replace("\\", "/", dirname(dirname(__FILE__)))."/webgallery_slider.php\");<br />
			?&gt;";
	?>
	</code>

	</div>
	<div id="tabs-5">

	<p><?php wg_lang::out('ScriptInfoFullPath'); ?></p>

	<code class="insert_code">
	<?php
		echo str_replace("\\", "/", dirname(dirname(__FILE__)))."/webgallery.php";
	?>
	</code>
	</div>
</div>






</div>



</div>


<div id="pg_loader">
</div>


</body>
</html>
