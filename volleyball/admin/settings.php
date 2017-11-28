<?php
	include("inc/check_session_admin.php");
	include("../inc/db.php");
	include("inc/users.lib.php");
	include("../inc/teams.lib.php");
	
	$usersObj = new userslib();
	$teamObj = new teamlib();
	
	$userdata = $usersObj->fetchAllusers();
	$teamcount = $teamObj->getTeamcount();
	
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
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$leagueary = '';
		if(isset($_POST['leaguenight'])){
			foreach($_POST['leaguenight'] as $lgnight){
				$leagueary.='"'.$lgnight.'", ';
			}
			$leagueary = rtrim($leagueary, ", ");
		}
		file_put_contents('../inc/settings.php', "<?php
			
			\$APP_costperteam = '".$_POST['costperteam']."';
			\$APP_depositamount = '".$_POST['depositamount']."';
			\$APP_minplayer = '".$_POST['minplayer']."';
			\$APP_maxteam = '".$_POST['maxteam']."';
			\$APP_leaguenight = array(".$leagueary.");

		?>");
		
		$errormsg = '<div class="alert alert-success alert-dismissable" style="font-size: 12px;">
			<i class="icon-exclamation-sign"></i><strong> *Success* </strong> Application settings changed successfully.<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
	}
	
	include("../inc/settings.php");
	
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

	<title>Settings</title>
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
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-42863888-3', 'pinsupreme.com');
		ga('send', 'pageview');

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
							<li>
								<a href="<?=ADMIN_BASE_URL?>/teams.php"><span class="badge pull-right"><?=$teamcount?></span><i class="icon-bar-chart"></i> TEAMS</a>
							</li>
							<li class="current">
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
							<h1><i class="icon-gear"></i> SETTINGS</h1>
						</div>
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">SETTINGS</li>
						</ol>
						<div class="main-content">
							<div class="row">
								<div class="col-md-12">
									<div class="widget">
										<?=$errormsg?>
										<div class="widget-content-white glossed">
											<div class="padded">
												<h3 class="form-title form-title-first"><i class="icon-user"></i> ADMIN USERS<a style="float: right;" href="admin.php" class="btn btn-success">Add New Admin</a></h3>
												<div class="row">
													<table class="table table-striped table-bordered table-hover datatable">
														<thead>
															<tr>
															  <th style="width: 5%;">ID</th>
															  <th style="width: 25%;">FULL NAME</th>
															  <th style="width: 15%;">USERNAME</th>
															  <th style="width: 40%;">EMAIL ADDRESS</th>
															  <th style="width: 15%;">MODIFY</th>
															</tr>
														</thead>
														<tbody>
														  <? while($ad_data = $userdata->fetch_assoc()){ ?>
															<tr>
																<td><?=$ad_data['userid']?></td>
																<td><?=$ad_data['fullname']?></td>
																<td><?=$ad_data['username']?></td>
																<td class="text-left"><?=$ad_data['emailid']?></td>
																<td class="text-center">
																	<a href="admin.php?userid=<?=$ad_data['userid']?>" class="btn btn-default btn-xs"><i class="icon-pencil"></i> edit</a>
																	<a href="#" onclick="return deleteadmin(<?=$ad_data['userid']?>)" class="btn btn-danger btn-xs"><i class="icon-remove"></i></a>
																</td>
															</tr>
														  <? } ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
							
									<form method="POST" name="changesettings" action="" role="form">
										<div class="col-md-6">
											<div class="widget">
												<div class="widget-content-white glossed">
													<div class="padded">
														<h3 class="form-title form-title-first"><i class="icon-gears"></i> TEAM SETTINGS</h3>
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<div class="moneysign">
																		<label>Cost per Team <span style="color:#999; font-weight: normal;">(Pay in Full Option)</span></label>
																		<span class="icon-usd"></span>
																		<input type="text" class="form-control" name="costperteam" value="<?=(isset($APP_costperteam)? $APP_costperteam: '')?>">
																	</div>
																</div>
														
																<div class="form-group">
																	<div class="moneysign">
																		<label>Deposit Amount</label>
																		<span style="position: relative; left: 15px; float: left;" class="icon-usd"></span>
																		<input type="text" class="form-control" name="depositamount" value="<?=(isset($APP_depositamount)? $APP_depositamount: '')?>">
																	</div>
																</div>
																
																<div class="form-group">
																	<label>Minimum Players Per Team</label>
																	<select class="form-control" name="minplayer" id="minplayer">
																		<option>1</option>
																		<option>2</option>
																		<option>3</option>
																		<option>4</option>
																		<option>5</option>
																		<option>6</option>
																	</select>
																</div>
															</div>
														</div>
													</div>
												</div>
												<button style="margin-top: 50px;" type="submit" class="btn btn-danger">Save Changes</button>
											</div>
										</div>
								  
										<div class="col-md-6">
											<div class="widget">
												<div class="widget-content-white glossed">
													<div class="padded">
														<h3 class="form-title form-title-first"><i class="icon-calendar"></i> NIGHTHLY SETTINGS</h3>
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label>Max Teams Per Night</label>
																	<input type="text" class="form-control" name="maxteam" value="<?=(isset($APP_maxteam)? $APP_maxteam: '')?>">
																</div>
																<div class="form-group">
																	<label>League Nights</label><br>
																	<label class="checkbox">
																		<input type="checkbox" name="leaguenight[]" value="Monday" <?=((in_array("Monday", $APP_leaguenight))? 'checked': '')?>> Monday
																	</label>
																	<label class="checkbox">
																		<input type="checkbox" name="leaguenight[]" value="Tuesday" <?=((in_array("Tuesday", $APP_leaguenight))? 'checked': '')?>> Tuesday
																	</label>
																	<label class="checkbox">
																		<input type="checkbox" name="leaguenight[]" value="Wednesday" <?=((in_array("Wednesday", $APP_leaguenight))? 'checked': '')?>> Wednesday
																	</label>
																	<label class="checkbox">
																		<input type="checkbox" name="leaguenight[]" value="Thursday" <?=((in_array("Thursday", $APP_leaguenight))? 'checked': '')?>> Thursday
																	</label>
																	<label class="checkbox">
																		<input type="checkbox" name="leaguenight[]" value="Friday" <?=((in_array("Friday", $APP_leaguenight))? 'checked': '')?>> Friday
																	</label>
																	<label class="checkbox">
																		<input type="checkbox" name="leaguenight[]" value="Saturday" <?=((in_array("Saturday", $APP_leaguenight))? 'checked': '')?>> Saturday
																	</label>
																	<label class="checkbox">
																		<input type="checkbox" name="leaguenight[]" value="Sunday" <?=((in_array("Sunday", $APP_leaguenight))? 'checked': '')?>> Sunday
																	</label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
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
		$(document).ready(function(){
			$('#minplayer').val(<?=$APP_minplayer?>);
		});
		
		function deleteadmin(uid){
			var deladmin = confirm("Are you sure want to delete?");
			if(deladmin == true){
				window.location = "<?=BASE_URL?>/admin/deladmin.php?userid="+uid;
			}
		}
	</script>
	
</body>

</html>