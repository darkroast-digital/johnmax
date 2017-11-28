<?php
	include("/inc/check_session_admin.php");
	include("../inc/db.php");
	include("../inc/teams.lib.php");
	
	$teamObj = new teamlib();
	
	$atype = '';
	$errormsg = '';
	if(isset($_GET['teamid'])){
		$res = $teamObj->deleteTeam($_GET['teamid']);
		if($res){
			$errormsg = "Team deleted successfully.";
			$atype = "success";
		}
		else{
			$errormsg = "Error occured";
			$atype = "error";
		}
	}
	
	header("location:teams.php?atype=".$atype."&msg=".$errormsg);
	
?>