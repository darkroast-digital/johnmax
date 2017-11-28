<?php
	include("inc/db.php");
	include("inc/sendmail.lib.php");
	include("inc/players.lib.php");
	include("inc/teams.lib.php");
	include("inc/settings.php");
	
	$sendemailObj = new sendemail();
	$teamObj = new teamlib();
	$playersObj = new playerslib();
	
	$errormsg = '';
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$teamid = substr($_POST['teamid'], 3);
		$res = $playersObj->addplayer($_POST);
		if($res){
			$teamdetails = $teamObj->fetchteamByid($teamid);
			$sendemailObj->newplayer($_POST, $teamdetails);
			
			if($_POST['deposit'] == "pay"){
				//refirect to paypal
				
				header("location:pay/index.php?payamount=".$_POST['payfee'].'&eid='.$_POST['playeremail'].'&name='.$_POST['playername'].'&teamid='.$_POST['teamid'].'&playerid='.$res);
				die();
			
			}
			
			$errormsg = '<div class="alert alert-success alert-dismissable" style="font-size: 12px;">
			<i class="icon-exclamation-sign"></i><strong> *Success* </strong>New player added successfully.<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
		}
		else{
			$errormsg = '<div class="alert alert-danger alert-dismissable" style="font-size: 12px;">
			<i class="icon-exclamation-sign"></i><Error> *Error* </strong>Error occured.<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
		}
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
			/*setInterval(function(){
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
			}, 1000)*/
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

	<title>Join Team</title>
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
							<li>
								<a href="<?=BASE_URL?>/create-team.php"><i class="icon-trophy"></i> CREATE A TEAM</a>
							</li>
							<li>
								<a href="<?=BASE_URL?>/invite-players.php"><i class="icon-comments"></i> INVITE TEAMMATES</a>
							</li>
							<li class="current">
								<a href="<?=BASE_URL?>/join-team.php"><i class="icon-group"></i> JOIN A TEAM</a>
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
							<h1><i class="icon-group"></i> JOIN TEAM</h1>
						</div>
						<ol class="breadcrumb">
							<li><a href="#">Home</a></li>
							<li class="active">JOIN TEAM</li>
						</ol>
						<div class="main-content">
							<div class="row">
								<div class="col-md-6">
									<div class="widget">
										<form method="POST" name="addplayer" id="addplayer" action="" role="form">
											<div class="widget-content-white glossed">
												<div class="padded">
													<h3 class="form-title form-title-first"><i class="icon-gears"></i> JOIN A TEAM</h3>
													<?=$errormsg?>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label>Team ID</label>
																<input class="form-control" placeholder="Team ID" name="teamid" id="teamid" value="<?=(isset($_GET['teamid'])? TID_PREFIX.$_GET['teamid']: '')?>" required>
																<div style="font-size: 12px;" class="alert alert-warning alert-dismissable">
																	<i class="icon-exclamation-sign"></i> <strong>*Note*</strong> If Team ID field is not pre-populated, please enter the Team ID given to you, by your team captain.
																	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
																</div>
															</div>
														
															<div class="form-group">
																<label>Your Name</label>
																<input type="text" class="form-control" name="playername" required>
															</div>
													
															<div class="form-group">
																<label>Email Address</label>
																<input type="text" class="form-control" name="playeremail">
															</div>
														</div>
														<div style="float: left; margin-top: 40px;" class="col-md-12">
															<div class="form-group">
																<div class="bs-example">
																	<input type="radio" name="deposit" value="pay" checked><label for="pay"> &nbsp;Pay Your Fee <span id="plfee"></span> </label>&nbsp;&nbsp;&nbsp;&nbsp;
																	<input type='hidden' name='payfee' id="payfee" value=''>
																	<input type="radio" name="deposit" value="addtoroster"><label for="addtoroster"> &nbsp;Add to Roster without Paying </label>
																	<input type='hidden' name='ispaid' id="ispaid" value='N'>
																</div>
															</div>
														</div>
													</div>
												  <p style="margin-top: 25px; font-size: 12px;">If you chose "Pay Your Fee", you will be directed to payment page upon clicking "Join Team".</p><p style="font-size: 12px;"> If you made payment arrangements with your Team Captain, simply click the "Add to Roster without Paying" option, to be added to the team roster.</p>
												</div>
											</div>
										  
											<button style="margin-top: 50px;" type="submit" class="btn btn-danger">Join Team</button> 
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
				
				<? if(isset($_GET['teamid'])){ ?>
					setplayerfee('<?=TID_PREFIX?><?=$_GET['teamid']?>');
				<? } ?>
				
				$("#teamid").focusout(function(){
					var teamid = $('#teamid').val();
					setplayerfee(teamid);
				});
				
				$( "#addplayer" ).submit(function( event ) {
					var teamid = $('#teamid').val();
					event.preventDefault();
					setplayerfee(teamid, 'form-submit');
				});
			});
			
			function setplayerfee(teamid, action){
				$.ajax({
					url: 'ajax_teamdetails.php?teamid='+teamid,
					dataType: 'json',
					success: function(result){
						if(result.res == 'success'){
							teamdetails = result.teamdetails;
							balanceamount = parseInt(teamdetails.teamcost) - parseInt(teamdetails.totalamount);
							if(balanceamount == 0){
								pay_amnt = 0;
								$('#ispaid').val('Y');
							}
							else{
								pay_amnt = (parseInt(teamdetails.teamcost) - parseInt(teamdetails.payamount)) / parseInt(teamdetails.teammates);
								$('#ispaid').val('N');
							}
							radio_addtoroster = $('input:radio[name=deposit][value=addtoroster]');
							radio_pay = $('input:radio[name=deposit][value=pay]');
							$('#plfee').html('($'+pay_amnt+')');
							$('#payfee').val(pay_amnt);
							
							if(!action){
								if(pay_amnt==0){
									radio_addtoroster.prop("checked", true);
									radio_pay.prop("disabled", true);
								}
								else{
									radio_pay.prop("checked", true);
									radio_pay.prop("disabled", false);
								}
							}
							if(action != '' && action == 'form-submit'){
								$("#addplayer").unbind("submit");
								$("#addplayer").submit();
							}
						}
						else{
							$('#plfee').html('');
							$('#payfee').val('');
						}
					}
				});
			}
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