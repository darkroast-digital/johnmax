<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$authQuery = wg_data::query("select * from `" . wg_data::tableName('photos') . "` WHERE albumid= " . (int) $_POST['albumid'] . " order by `position` ASC");

$finalHtml = '';
$html = file_get_contents(WG_ADMIN_DIR . '/display/picture_main_list.phtml');

while(($row = wg_data::row($authQuery))) {

	if(wg_settings::get('photo_source') == 'server' || empty($row['facebookid']))
	{
		$row['thumbnail_location'] = WG_INSTALL_LOCATION . 'photos/'.$row['albumid'].'/'.$row['id'].'_medium.' . $row['imagetype'];
	}
	else
	{
		$row['thumbnail_location'] = $row['imageurl_medium'];
	}

	if(!empty($row['facebookid'])) {
		$row['facebookClass'] = "is-fb-album";
	} else {
		$row['facebookClass'] = "not-fb-album";
	}

	$parse = new wg_display_parse($html, $row);
	$finalHtml .= $parse->parse();
}

$result = array('album_html'=> $finalHtml, 'albumid' =>  (int) $_POST['albumid'] );

echo json_encode($result);