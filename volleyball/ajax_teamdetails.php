<?php
	include("inc/db.php");
	include("inc/sendmail.lib.php");
	include("inc/players.lib.php");
	include("inc/teams.lib.php");
	include("inc/settings.php");
	
	$teamObj = new teamlib();
	if(isset($_GET['teamid'])){
		$teamid = substr($_GET['teamid'], 3);
		$teamdetails = $teamObj->fetchteamByid($teamid);
		if($teamdetails){
			$return = array('res'=>'success', 'teamdetails'=>$teamdetails);
		}
		else{
			$return = array('res'=>'error', 'teamdetails'=>'');
		}
	}
	echo json_encode($return);
	
?>