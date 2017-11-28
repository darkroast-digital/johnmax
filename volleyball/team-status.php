<?php
	include("inc/db.php");
	include("inc/sendmail.lib.php");
	include("inc/players.lib.php");
	include("inc/teams.lib.php");
	include("inc/settings.php");
	
	$teamObj = new teamlib();
	$playersObj = new playerslib();
	
	$errormsg = '';
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$teamid = substr($_GET['teamid'], 3);
		$teamdetails = $teamObj->fetchteamByid($teamid);
		$teamplayers = $playersObj->fetchteamByid($teamid);
		$balanceamount = $teamdetails['teamcost'] - $teamdetails['totalamount'];
		
		if(empty($teamdetails)){
			$errormsg = '<div class="alert alert-danger alert-dismissable" style="font-size: 12px;"><i class="icon-exclamation-sign"></i><Error> *Error* </strong>Non existing team-id.<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
		}
	}
?>
<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Hammer reload -->
	<script>
		setInterval(function(){
		  try {
			if(typeof ws != 'undefined' && ws.readyState == 1){return true;}
			ws = new WebSocket('ws://'+(location.host || 'localhost').split(':')[0]+':35353')
			ws.onopen = function(){ws.onclose = function(){document.location.reload()}}
			ws.onmessage = function(){
			  var links = document.getElementsByTagName('link'); 
				for (var i = 0; i < links.length;i++) { 
				var link = links[i]; 
				if (link.rel === 'stylesheet' && !link.href.match(/typekit/)) { 
				  href = link.href.replace(/((&|\?)hammer=)[^&]+/,''); 
				  link.href = href + (href.indexOf('?')>=0?'&':'?') + 'hammer='+(new Date().valueOf());
				}
			  }
			}
		  }catch(e){}
		}, 1000)
	</script>
	<!-- /Hammer reload -->
	<link rel='stylesheet' href='assets/css/fullcalendar.css'>
	<link rel='stylesheet' href='assets/css/datatables/datatables.css'>
	<link rel='stylesheet' href='assets/css/datatables/bootstrap.datatables.css'>
	<link rel='stylesheet' href='assets/scss/chosen.css'>
	<link rel='stylesheet' href='assets/scss/font-awesome/font-awesome.css'>
	<link rel='stylesheet' href='assets/css/app.css'>
	<link rel="stylesheet" href="assets/colorbox/example1/colorbox.css" />

	<link href='assets/css/fonts.googleapis.css' rel='stylesheet' type='text/css'>

	<link href="assets/favicon.ico" rel="shortcut icon">
	<link href="assets/apple-touch-icon.png" rel="apple-touch-icon">
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		@javascript html5shiv respond.min
	<![endif]-->

	<title>Team Status</title>
	<style>
		.moneysign input {text-indent: 20px; font-size: 18px; font-weight: normal;}
		.icon-usd { 
		  position: absolute;
		  top: 35px;
		  left: 30px;
		  font-size: 15px;
		  color: #555555;
		}

	</style>
</head>

<body>
	<script>
		/*(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-42863888-3', 'pinsupreme.com');
		ga('send', 'pageview');*/
	</script>

	<div class="all-wrapper">
	  <div class="row">
		<div class="col-md-3">
			<div class="text-center">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>
			<div class="side-bar-wrapper collapse navbar-collapse navbar-ex1-collapse">
				<img src="assets/images/logo.png">
				<div style="margin-top: 40px;" class="relative-w">
					<ul class="side-menu">
						<li>
							<a href="create-team.php"><i class="icon-trophy"></i> CREATE A TEAM</a>
						</li>

						<li>
							<a href="invite-players.php"><i class="icon-comments"></i> INVITE TEAMMATES</a>
						</li>

						<li>
							<a href="join-team.php"><i class="icon-group"></i> JOIN A TEAM</a>
						</li>
						
						<li class="current">
							<a class="iframe" href="check-status.php"><i class="icon-group"></i> CHECK TEAM STATUS</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	   <div class="col-md-9">
			<div class="content-wrapper">
				<div class="content-inner">
					<div class="page-header">
						<h1><i class="icon-trophy"></i> TEAM STATUS</h1>
					</div>
					<ol class="breadcrumb">
						<li><a href="#">Home</a></li>
						<li class="active">TEAM STATUS</li>
					</ol>
					<div class="main-content">
						<div class="row">
							<div class="col-md-12">
								<?=$errormsg?>
								<div class="widget">
									<div class="widget-content-white glossed">
										<div class="padded">
											<h3 class="form-title form-title-first"><i class="icon-group"></i> <?=$teamdetails['teamname']?> </h3>
											<div class="row">
												<table class="table table-striped table-bordered table-hover datatable">
												  <thead>
													<tr>
													  <th style="width: 30%;">PLAYER NAME</th>
													  <th style="width: 20%;">DATE ADDED</th>
													  <th style="width: 15%;">PLAYER FEE</th>
													  <th style="width: 20%;">STATUS</th>
													</tr>
												  </thead>
												  <tbody>
													<tr>
														<td><?=$teamdetails['captname']?> &nbsp;<label class="label label-info"><em>Captain</em></label></td>
														<td><?=$teamdetails['addedon']?></td>
														<td><?=$teamdetails['payamount']?></td>
														<td></td>
													</tr>
													<?
													while($teamplayer = $teamplayers->fetch_assoc()){
													?>
														<tr>
														  <td><?=$teamplayer['playername']?> <strong></strong></td>
														  <td><?=$teamplayer['addedon']?></td>
														  <td class="text-left"><?=$teamplayer['pplramount']?></td>
														  <td>
															<? if($teamplayer['ispaid'] == 'Y'){ ?>
																<span class="label label-success">PAID IN FULL</span>
															<? } else { ?>
																<span class="label label-danger">NOT PAID</span>
																
																<a class="btn btn-success btn-xs" style="margin-left: 10px" href="pay/index.php?payamount=<?=$teamplayer['pplramount']?>&eid=<?=$teamplayer['playeremail']?>&name=<?=$teamplayer['playername']?>&teamid=<?=$teamplayer['teamid']?>&playerid=<?=$teamplayer['playerid']?>">MAKE PAYMENT</a>
															<? } ?>
														  </td>
														</tr>
													<?
													}
													?>
													<tr>
													  <td>&nbsp;</td>
													  <td>&nbsp;</td>
													  <td class="text-left">&nbsp;</td>
													  <td>&nbsp;</td>
													</tr>
												  </tbody>
												</table>
											</div>
										</div>
										<table class="widget-content-white glossed" style="margin-top: 40px; float: right;" width="25%" border="0" cellspacing="0" cellpadding="0">
										  <tr>
											<td style=""><h3 style="padding: 15px 15px 0px 15px;" class="form-title form-title-first">BALANCE OWED</h3>
											<h1 style="top: 0; padding: 0px 15px 0 15px; font-size: 3.75em;">$<?=$balanceamount?></h1>
											<? if($balanceamount > 0){ ?>
											<p style="font-weight: 700; font-size: 16px; padding-left: 15px;"><a href="pay/index.php?payamount=<?=$balanceamount?>&eid=<?=$teamdetails['emailid']?>&name=<?=str_replace(' ', '%20', $teamdetails['captname'])?>&teamid=<?=TID_PREFIX?><?=$teamdetails['teamid']?>">Pay remaining balance</a></p></td>
											<? } ?>
										  </tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery-ui.min.js"></script>
<script src='assets/js/jquery.sparkline.min.js'></script>
<script src='assets/js/bootstrap/tab.js'></script>
<script src='assets/js/bootstrap/dropdown.js'></script>
<script src='assets/js/bootstrap/collapse.js'></script>
<script src='assets/js/bootstrap/alert.js'></script>
<script src='assets/js/bootstrap/transition.js'></script>
<script src='assets/js/bootstrap/tooltip.js'></script>
<script src='assets/js/jquery.knob.js'></script>
<script src='assets/js/fullcalendar.min.js'></script>
<script src='assets/js/datatables/datatables.min.js'></script>
<script src='assets/js/chosen.jquery.min.js'></script>
<script src='assets/js/datatables/bootstrap.datatables.js'></script>
<script src='assets/js/raphael-min.js'></script>
<script src='assets/js/morris-0.4.3.min.js'></script>
<script src='assets/js/for_pages/color_settings.js'></script>
<script src='assets/js/application.js'></script>
<script src="assets/colorbox/jquery.colorbox.js"></script>

<!--<script src='assets/js/for_pages/table.js'></script>-->

<script>
	$(document).ready(function(){
		//Examples of how to assign the Colorbox event to elements
		$(".iframe").colorbox({scrolling: false, iframe:true, width:"525",height:"430"});
		
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){
			$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
			return false;
		});
	});
</script>

</body>

</html>