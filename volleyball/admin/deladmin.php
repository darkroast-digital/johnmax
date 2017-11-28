<?php
	include("/inc/check_session_admin.php");
	include("../inc/db.php");
	include("/inc/users.lib.php");
	
	$usersObj = new userslib();
	
	$atype = '';
	$errormsg = '';
	if(isset($_GET['userid'])){
		$res = $usersObj->deleteUser($_GET['userid']);
		if($res){
			$errormsg = "Admin user deleted successfully.";
			$atype = "success";
		}
		else{
			$errormsg = "Error occured";
			$atype = "error";
		}
	}
	
	header("location:settings.php?atype=".$atype."&msg=".$errormsg);
	
?>