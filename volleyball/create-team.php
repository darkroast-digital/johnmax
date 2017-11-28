<?php
	include("inc/db.php");
	include("inc/sendmail.lib.php");
	include("inc/teams.lib.php");
	include("inc/settings.php");

	

	$teamObj = new teamlib();



	$errormsg = '';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$res = $teamObj->addteam($_POST);

		if($res){
			header("location:pay/index.php?payamount=".$_POST['payamount'].'&eid='.$_POST['emailid'].'&name='.$_POST['captname'].'&teamid='.TID_PREFIX.$res);
			die();
			$errormsg = '<div class="alert alert-success alert-dismissable" style="font-size: 12px;">

			<i class="icon-exclamation-sign"></i><strong> *Success* </strong>New team created successfully.<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';

		}

		else{

			$errormsg = '<div class="alert alert-danger alert-dismissable" style="font-size: 12px;">

			<i class="icon-exclamation-sign"></i><Error> *Error* </strong>Error occured.<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';

		}

	}

	

	$eventcount = [];

	$evenightcount = $teamObj->getCountbyevenights();

	while($row = $evenightcount->fetch_assoc()){

		$eventcount[$row['evenight']] = $row['count'];

	}

?>

<!DOCTYPE html>

<html>

<head>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/colorbox/example1/colorbox.css" />

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



	<link href='assets/css/fonts.googleapis.css' rel='stylesheet' type='text/css'>



	<link href="assets/favicon.ico" rel="shortcut icon">

	<link href="assets/apple-touch-icon.png" rel="apple-touch-icon">

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

	<!--[if lt IE 9]>

		@javascript html5shiv respond.min

	<![endif]-->



	<title>Create Team</title>

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

					<img src="assets/images/logo.png">

					

					<div style="margin-top: 40px;" class="relative-w">

						<ul class="side-menu">

							<li class="current">

								<a href="<?=BASE_URL?>/create-team.php"><i class="icon-trophy"></i> CREATE A TEAM</a>

							</li>

							

							
                            
                            <li>

								<a class="iframe" href="<?=BASE_URL?>/check-status.php"><i class="icon-group"></i> CHECK TEAM STATUS</a>

							</li>

						</ul>
<p style="text-align: center;">powered by<br>
  <a href="http://www.primarytargetmedia.com" target="_blank"><img src="assets/images/ptm-logo.png" width="120" height="29"></a></p>
					</div>

				</div>

			</div>

			<div class="col-md-9">

				<div class="content-wrapper">

					<div class="content-inner">

						<div class="page-header">

							<div style="line-height: 3; float: right;">

								<a href="http://www.johnmax.ca/vball-support.html" class="iframe2 btn btn-warning btn-round"><i class="icon-envelope"></i> Need Help?</a>

							</div>

							<h1><i class="icon-trophy"></i> CREATE TEAM</h1>

						</div>

						<ol class="breadcrumb">

							<li><a href="#">Home</a></li>

							<li class="active">CREATE TEAM</li>

						</ol>

						<div class="main-content">

							<div class="row">

								<div class="col-md-6">

									<div class="widget">

										<?=$errormsg?>

										<? if(!isset($res)){ ?>

											<form method="POST" name="createteam" action="" role="form">

												<div class="widget-content-white glossed">

													<div class="padded">

														<h3 class="form-title form-title-first"><i class="icon-gears"></i> TEAM SETTINGS</h3>

														<div class="row">

															<div class="col-md-6">

																<div class="form-group">

																	<label>Team Name</label>

																	<input type="text" class="form-control" name="teamname">

																</div>

														

																<div class="form-group">

																	<label>Team Captain Full Name</label>

																	<input type="text" class="form-control" name="captname">

																</div>

																

																<div class="form-group">

																	<label>Email Address</label>

																	<input type="text" class="form-control" name="emailid">

																</div>
<div class="form-group">
																	
																	<select class="form-control" name="teammates" style="display: none;">
																		<option>4</option>
                                                                        <option selected="selected">5</option>
																		<option>6</option>
																		<option>7</option>
																		<option>8</option>
																		<option>9</option>
																		<option>10</option>
																		<option>11</option>
																		
																	</select>

																</div>

														

																<div class="form-group">

																	<label>Choose Night</label>

																	<select class="form-control" name="evenight">

																		<?	

																			foreach($APP_leaguenight as $lgnight){ 

																				if($eventcount[$lgnight] == "" || $eventcount[$lgnight] < $APP_maxteam){

																		?>

																					<option><?=$lgnight?></option>

																		<? 

																				}

																			}

																		?>

																	</select>

																</div>

															</div>

															<div style="float: left; margin-top: 40px;" class="col-md-12">

																<div class="form-group">

																	<div class="bs-example">

																		<input type="radio" name="payamount" value="<?=$APP_depositamount?>" checked><label for="<?=$APP_depositamount?>"> &nbsp;Pay Deposit ($<?=$APP_depositamount?>) </label>&nbsp;&nbsp;&nbsp;&nbsp;

																		<input type="radio" name="payamount" value="<?=$APP_costperteam?>"><label for="<?=$APP_costperteam?>"> &nbsp;Pay in Full ($<?=$APP_costperteam?>) </label>

																	</div>

																</div>

															</div>

														</div>
                                                        
                                                        <p style="margin-top: 25px; font-size: 12px;"><strong>*Please Note: Team Captains are now responsible for all team payments (deposits and balances)</strong>. If making a deposit, please remember that all final payments are due April 17, 2017.</p>

														
                                                        <p style="margin-top: 25px; font-size: 12px;">Once clicking the "Create Team" button, you will be directed to payment page. Your spot is not secured, until payment is received.</p>

													</div>

												</div>
												<input type="hidden" name="teamcost" value="<?=$APP_costperteam?>" >
												<button style="margin-top: 50px;" type="submit" name="submit" class="btn btn-danger">Create Team</button> 

											</form>

										<? } else { ?>

											<div class="widget-content-white glossed">

												<div class="padded">

													<h4 style="color: #884742;">Team-ID: <?=TID_PREFIX?><?=$res?></h4>

													<button type="button" class="btn btn-default">MAKE PAYMENT</button>

												</div>

											</div>

										<? } ?>

									</div>

								</div>

					  

								<div class="col-md-6">

									<div class="widget">

										<div class="widget-content-white glossed">

											<div class="padded">

												<form action="" role="form">

													<h3 class="form-title form-title-first"><i class="icon-calendar"></i>NIGHTHLY LEAGUES</h3>

													<div class="row">

														<div class="col-md-12">

															<h4 style="color: #884742;">MONDAY</h4>

															<p>Co-Ed Recreation League. Every Monday at 6:15, starting May 1st, 2017.</p>

															<h4 style="margin-top: 15px; color: #884742;">TUESDAY</h4>

															<p>Co-Ed Recreation League. Every Tuesday at 6:15pm, starting May 2nd, 2017.</p>

															<h4 style="margin-top: 15px; color: #884742;">WEDNESDAY</h4>

															<p>Co-Ed Recreation League. Every Wednesday at 6:15pm, starting May 3rd, 2017.</p>

															<h4 style="margin-top: 15px; color: #884742;">THURSDAY</h4>

															<p>Co-Ed Competitive League. Every Thursday at 6:15pm, starting May 4th, 2017.</p>
                                                            
                                                            <h4 style="margin-top: 15px; color: #884742;">FRIDAY</h4>

															<p>Co-Ed Recreation League. Every Friday at 6:15pm, starting May 5th, 2017.</p>
                                                            
                                                            <h4 style="margin-top: 15px; color: #884742;">SUNDAY</h4>

															<p>Co-Ed Recreation League. Every Sunday at 3:00pm, starting May 7th, 2017.</p>

                                                            

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

			</div>

		</div>

	</div>



	<script src="assets/js/jquery.min.js"></script>

	<script src="assets/js/jquery-ui.min.js"></script>
    
    <script src="assets/colorbox/jquery.colorbox.js"></script>
		<script>
			$(document).ready(function(){
				//Examples of how to assign the Colorbox event to elements
				
				$(".iframe").colorbox({scrolling: false, iframe:true, width:"525",height:"430"});
				$(".iframe2").colorbox({scrolling: false, iframe:true, width:"80%",height:"80%"});
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
			});
		</script>


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

	<script src='assets/js/for_pages/table.js'></script>
   
    
    
    



</body>

</html>