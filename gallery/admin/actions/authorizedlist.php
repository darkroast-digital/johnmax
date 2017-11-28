<?php
if(!defined('BASE_DIR')) { die('Access Denied'); }

$authQuery = wg_data::query("select * from `" . wg_data::tableName('authorized') . "` order by `name` ASC");

$finalHtml = $userList = $userHtml = '';
$html = file_get_contents(WG_ADMIN_DIR . '/display/authorized_list.phtml');

while(($row = wg_data::row($authQuery))) {
	if(isset($row['id'])) {
		$userList .= '<option value="' . $row['id'] .'">' . $row['name'] . '</option>';
	}
	$parse = new wg_display_parse($html, $row);
	$userHtml .= $parse->parse();
}

$result = array('user_html' => $userHtml, 'user_list'=> $userList);

echo json_encode($result);
