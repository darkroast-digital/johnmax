<?php
	include("/inc/check_session_admin.php");
	include("../inc/db.php");
	include("../inc/teams.lib.php");
	include("../inc/settings.php");
	include("../inc/players.lib.php");
	
	$teamObj = new teamlib();
	$playersObj = new playerslib();
	
	$teamcount = $teamObj->getTeamcount();
	$teamdetails = $teamObj->getAllteamdata();
	$teams = [];
	while($row = $teamdetails->fetch_assoc()){
		$teams[$row['evenight']][] = $row;
	}
	
	
	$errormsg = '';
	if(isset($_GET['msg'])){
		if($_GET['atype'] == "success"){
			$errormsg = '<div class="alert alert-success alert-dismissable" style="font-size: 12px;">
			<i class="icon-exclamation-sign"></i><strong> *Success* </strong> '.$_GET['msg'].'<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
		}
		else{
			$errormsg = '<div class="alert alert-danger alert-dismissable" style="font-size: 12px;">
			<i class="icon-exclamation-sign"></i><Error> *Error* </strong> '.$_GET['msg'].'<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
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
	
	<link rel='stylesheet' href='../assets/css/fullcalendar.css'>
	<link rel='stylesheet' href='../assets/css/datatables/datatables.css'>
	<link rel='stylesheet' href='../assets/css/datatables/bootstrap.datatables.css'>
	<link rel='stylesheet' href='../assets/scss/chosen.css'>
	<link rel='stylesheet' href='../assets/scss/font-awesome/font-awesome.css'>
	<link rel='stylesheet' href='../assets/css/app.css'>

	<link href='../assets/css/fonts.googleapis.css' rel='stylesheet' type='text/css'>

	<link href="../assets/favicon.ico" rel="shortcut icon">
	<link href="../assets/apple-touch-icon.png" rel="apple-touch-icon">
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		@javascript html5shiv respond.min
	<![endif]-->

	<title>Teams</title>
</head>

<body>

	<script>
		/*
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-42863888-3', 'pinsupreme.com');
		ga('send', 'pageview');
		*/
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
					<img src="../assets/images/logo.png">
					<div class="search-box">
						<input type="text" placeholder="SEARCH" class="form-control">
					</div>
					<div class="relative-w">
						<ul class="side-menu">
							<li class="current">
								<a href="<?=ADMIN_BASE_URL?>/teams.php"><span class="badge pull-right"><?=$teamcount?></span><i class="icon-bar-chart"></i> TEAMS</a>
							</li>
							<li>
								<a href="<?=ADMIN_BASE_URL?>/settings.php"><i class="icon-gear"></i> SETTINGS</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="content-wrapper">
					<div class="content-inner">
						<div class="page-header">
							<div class="header-links hidden-xs">
								<a href="logout.php"><i class="icon-signout"></i> Logout</a>
							</div>
							<h1><i class="icon-bar-chart"></i> TEAMS</h1>
						</div>
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li></li>
							<li></li>
							<li class="active">TEAMS</li>
						</ol>
						<div class="main-content">
							<div class="widget">
								<?=$errormsg?>
								<h3 class="section-title first-title"><i class="icon-table"></i> TEAMS</h3>
								<ul class="nav nav-tabs">
									<? $i = 1;
									foreach($APP_leaguenight as $lgnight){ ?>
										<li <?=(($i == 1)? 'class="active"': '')?>><a href="#tab_<?=strtolower($lgnight)?>" data-toggle="tab"><i class="icon-calendar"></i> <?=$lgnight?></a></li>
									<? 
										$i++; 
									}
									?>
								</ul>
								
								<div class="tab-content bottom-margin">
								<? $i = 1;
								foreach($APP_leaguenight as $lgnight){ ?>
									<div class="tab-pane <?=(($i == 1)? 'active': '')?>" id="tab_<?=strtolower($lgnight)?>">
										<div class="shadowed-bottom">
											<div class="row">
												<table class="table table-striped table-bordered table-hover datatable">
												  <thead>
													<tr>
													  <th>ID</th>
													  <th>TEAM NAME</th>
													  <th>CAPTAIN</th>
													  <th><nobr>NO. OF TEAMMATES</nobr></th>
													  <th><nobr>BALANCE OWING</nobr></th>
													  <th>STATUS</th>
                                                      <th>REMIND</th>
													  <th>MODIFY</th>
													</tr>
												  </thead>
												  <tbody>
													<? 
														if(isset($teams[$lgnight])){
															foreach($teams[$lgnight] as $team){
																if($team['totalamount'] != 0){
																	$balanceamount = $team['teamcost'] - $team['totalamount'];
													?>
																	<tr>
																	  <td><?=TID_PREFIX.$team['teamid']?></td>
																	  <td><a href="http://www.johnmax.ca/volleyball/team-status.php?teamid=<?=TID_PREFIX.$team['teamid']?>"><?=$team['teamname']?></a></td>
																	  <td><?=$team['captname']?><BR><?=$team['emailid']?></td>
																	  <td><?=$team['teammates']?></td>
																	  <td class="text-left"><?=$balanceamount?></td>
																	  <?
																	  if($team['status']=='Inactive')
																		echo '<td><span class="label label-warning">'.$team['status'].'</span></td>';
																	  else
																		echo '<td><span class="label label-success">'.$team['status'].'</span></td>';
																	  ?>
																	 
																	  <td>
																		<? if($balanceamount >0){ ?>
																		<a href="remind.php?teamid=<?=$team['teamid']?>" class="btn btn-default btn-xs"><i class="icon-envelope"></i> Remind</a>
																		<? } ?>
																	  </td>
																	  <td class="text-center">
																		<a href="<?=BASE_URL?>/admin/edit-team.php?teamid=<?=$team['teamid']?>" class="btn btn-default btn-xs"><i class="icon-pencil"></i> edit</a>
																		<a href="#" onclick="return deleteteam(<?=$team['teamid']?>)" class="btn btn-danger btn-xs"><i class="icon-remove"></i></a>
																	  </td>
																	</tr>
													<?
																}
															}
														}
													?>
												  </tbody>
												</table>
											</div>
										</div>
									</div>
								<? 
									$i++; 
								}
								?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/jquery-ui.min.js"></script>
	<script src='../assets/js/jquery.sparkline.min.js'></script>
	<script src='../assets/js/bootstrap/tab.js'></script>
	<script src='../assets/js/bootstrap/dropdown.js'></script>
	<script src='../assets/js/bootstrap/collapse.js'></script>
	<script src='../assets/js/bootstrap/alert.js'></script>
	<script src='../assets/js/bootstrap/transition.js'></script>
	<script src='../assets/js/bootstrap/tooltip.js'></script>
	<script src='../assets/js/jquery.knob.js'></script>
	<script src='../assets/js/fullcalendar.min.js'></script>
	<script src='../assets/js/datatables/datatables.min.js'></script>
	<script src='../assets/js/chosen.jquery.min.js'></script>
	<script src='../assets/js/datatables/bootstrap.datatables.js'></script>
	<script src='../assets/js/raphael-min.js'></script>
	<script src='../assets/js/morris-0.4.3.min.js'></script>
	<script src='../assets/js/for_pages/color_settings.js'></script>
	<script src='../assets/js/application.js'></script>
	<!--<script src='../assets/js/for_pages/table.js'></script>-->
	
	<script>
		function deleteteam(teamid){
			var delteam = confirm("Are you sure want to delete?");
			if(delteam == true){
				window.location = "<?=BASE_URL?>/admin/delteam.php?teamid="+teamid;
			}
		}
	</script>
</body>

</html>