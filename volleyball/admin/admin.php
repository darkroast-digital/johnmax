<?php
	include("/inc/check_session_admin.php");
	include("../inc/db.php");
	include("../inc/sendmail.lib.php");
	include("/inc/users.lib.php");
	include("../inc/teams.lib.php");
	
	$usersObj = new userslib();
	$teamObj = new teamlib();
	
	$teamcount = $teamObj->getTeamcount();
	
	if(isset($_GET['userid'])){
		$udata = $usersObj->fetchuserByid($_GET['userid']);
		$title = "Update Admin";
		$btnlable = "Update";
	}
	else{
		$title = "Add New Admin";
		$btnlable = "Create Admin";
	}
	
	$errormsg = '';
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_GET['userid'])){
			$res = $usersObj->updateUserdata($_POST);
			$errormsg="Admin details updated successfully.";
		}
		else{
			$res = $usersObj->addUser($_POST);
			$errormsg="New admin added successfully.";
		}
		
		if(res)
			header("location:settings.php?atype=success&msg=".$errormsg);
		else
			$errormsg = "Error Occured";
		
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

	<title><?=$title?></title>
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
								<a href="teams.php"><span class="badge pull-right"><?=$teamcount?></span><i class="icon-bar-chart"></i> TEAMS</a>
							</li>
							<li class="current">
								<a href="settings.php"><i class="icon-gear"></i> SETTINGS</a>
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
							<h1><i class="icon-user"></i> MANAGE ADMIN</h1>
						</div>
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">MANAGE ADMIN</li>
						</ol>
						<div class="main-content">
							<div class="row">
								<div class="col-md-6">
									<div class="widget">
										<?=$errormsg?>
										<form method="POST" name="createteam" action="" role="form">
											<div class="widget-content-white glossed">
												<div class="padded">
													<h3 class="form-title form-title-first"><i class="icon-gears"></i> <?=strtoupper($title)?></h3>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label>Fullname</label>
																<input type="text" class="form-control" name="fullname" <?=(isset($udata['fullname'])? 'value='.$udata['fullname']: '')?> >
															</div>
															
															<div class="form-group">
																<label>Email Address</label>
																<input type="email" class="form-control" name="emailid" <?=(isset($udata['emailid'])? 'value='.$udata['emailid']: '')?> >
															</div>
															
															<div class="form-group">
																<label>Username</label>
																<input type="text" class="form-control" name="username" <?=(isset($udata['username'])? 'value='.$udata['username']: '')?> >
															</div>
															
															<? if(!isset($_GET['userid'])){ ?>
															<div class="form-group">
																<label>Password</label>
																<input type="password" class="form-control" name="password">
															</div>
															<? } else { ?>
																<input type="hidden" name="userid" value="<?=$udata['userid']?>">
															<? } ?>
														</div>
													</div>
												</div>
											</div>
											<button style="margin-top: 50px;" type="submit" name="submit" class="btn btn-danger"><?=$btnlable?></button> 
										</form>
									</div>
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
	<script src='../assets/js/for_pages/table.js'></script>

</body>

</html>