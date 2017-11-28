<?php
	include("/inc/check_session_admin.php");
	include("../inc/db.php");
	include("../inc/teams.lib.php");
	include("../inc/players.lib.php");
	include("../inc/settings.php");
	include("../inc/sendmail.lib.php");
	
	$teamObj = new teamlib();
	$playersObj = new playerslib();
	$sendemailObj = new sendemail();
	
	$teamid = $_GET['teamid'];
	
	$teamdetails = $teamObj->fetchteamByid($teamid);
	$balanceamount = $teamdetails['teamcost'] - $teamdetails['totalamount'];
	
	$data = ['teamid'=>TID_PREFIX.$teamdetails['teamid'], 'captname'=>$teamdetails['captname'], 'teamname'=>$teamdetails['teamname'], 'emailid'=>$teamdetails['emailid'], 'balanceowed'=>$balanceamount];
	
	$sendemailObj->remind($data);
	header('Location:teams.php?msg=Reminder email sent successfully.&atype=success');
?>