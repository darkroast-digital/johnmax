<?php
	include("/inc/check_session_admin.php");
	include("../inc/db.php");
	include("../inc/teams.lib.php");
	include("../inc/settings.php");
	
	$teamObj = new teamlib();
	$teamcount = $teamObj->getTeamcount();
	
	if(isset($_GET['teamid'])){
		$teamdata = $teamObj->fetchteamByid($_GET['teamid']);
	}
	
	$errormsg = '';
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if(isset($_GET['teamid'])){
			$res = $teamObj->updateTeam($_POST);
			$errormsg="Team details updated successfully.";
			
			if(res)
				header("location:teams.php?atype=success&msg=".$errormsg);
			else
				$errormsg = "Error Occured";
			
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

	<title>Edit Team</title>
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
								<a href="teams.php"><span class="badge pull-right"><?=$teamcount?></span><i class="icon-bar-chart"></i> TEAMS</a>
							</li>
							<li>
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
							<h1><i class="icon-bar-chart"></i> TEAMS</h1>
						</div>
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li></li>
							<li></li>
							<li class="active">TEAMS</li>
						</ol>
						<div class="main-content">
							<div class="row">
								<div class="col-md-6">
									<div class="widget">
										<?=$errormsg?>
										<form method="POST" name="editteam" action="" role="form">
											<div class="widget-content-white glossed">
												<div class="padded">
													<h3 class="form-title form-title-first"><i class="icon-gears"></i> TEAM SETTINGS</h3>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label>Team Name</label>
																<input type="text" class="form-control" name="teamname" value="<?=$teamdata['teamname']?>">
																<input type="hidden" name="teamid" value="<?=$teamdata['teamid']?>">
															</div>
													
															<div class="form-group">
																<label>Team Captain Full Name</label>
																<input type="text" class="form-control" name="captname" value="<?=$teamdata['captname']?>">
															</div>
															
															<div class="form-group">
																<label>Email Address</label>
																<input type="text" class="form-control" name="emailid" value="<?=$teamdata['emailid']?>">
															</div>
													
															<div class="form-group">
																<label>Choose Night</label>
																<select class="form-control" name="evenight" id="evenight">
																	<?	
																		foreach($APP_leaguenight as $lgnight){
																	?>
																			<option><?=$lgnight?></option>
																	<? 
																		}
																	?>
																</select>
															</div>
														</div>
													</div>
												</div>
											</div>
											<button style="margin-top: 50px;" type="submit" name="submit" class="btn btn-danger">Update Team</button> 
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
	<script>
		$(document).ready(function(){
			$('#evenight').val('<?=$teamdata['evenight']?>');
		});
	</script>

</body>

</html>