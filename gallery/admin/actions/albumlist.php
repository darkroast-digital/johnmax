<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$authQuery = wg_data::query("select * from `" . wg_data::tableName('albums') . "` order by `sortorder` ASC");

$albumList = $finalHtml = '';
$html = file_get_contents(WG_ADMIN_DIR . '/display/album_main_list.phtml');

while(($row = wg_data::row($authQuery))) {
	$row['desc'] = substr($row['desc'], 0 ,82);
	if(strlen($row['desc']) > 0) {
		if(strlen($row['location']) > 0) {
			$row['desc'] = "&nbsp;-&nbsp;" . $row['desc'];
		}
	}

	$preview = wg_data::row("select * from `" . wg_data::tableName('photos') . "` where id = '".$row['coverid']."' and albumid = ".$row['id']." LIMIT 1");


	if(wg_settings::get('photo_source') == 'server' || empty($preview['facebookid']))
	{
		$row['preview'] = WG_INSTALL_LOCATION . 'photos/'.$row['id'].'/'.$preview['id'].'_thumbnail.' . $preview['imagetype'];
	}
	else
	{
		$row['preview'] = $preview['imageurl_thumbnail'];
	}

	if(!empty($row['facebookid'])) {
		$row['facebookClass'] = "is-fb-album";
	} else {
		$row['facebookClass'] = "not-fb-album";
	}

	$parse = new wg_display_parse($html, $row);
	$finalHtml .= $parse->parse();

	if(isset($row['id'])) {
		$albumList .= '<option value="' . $row['id'] .'">' . $row['name'] . '</option>';
	}
}

$result = array('album_html'=> $finalHtml, 'album_list' =>$albumList);

echo json_encode($result);