<?php
	include("../inc/db.php");
	include("inc/users.lib.php");	
	
	$usersObj = new userslib();
	
	session_start();
	if(isset($_SESSION['admin']) && $_SESSION['admin']=='Y') {
		header("location:settings.php");
	}
	
	$error_msg = '';
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$userdata = $usersObj->loginCheck($_POST);
		if($userdata){
			$adminid = $userdata['userid'];
			$admin_name = $userdata['fullname'];
			$password = $userdata['password'];
			$lastlogin = explode(" ", $userdata['lastlogin']);
			$lastlogin_date =  $lastlogin[0];
			$lastlogin_time = $lastlogin[1];

			$info = base64_encode("$adminid|$admin_name|$password|$lastlogin_date|$lastlogin_time");
			setcookie("admin","$info",0);

			$usersObj->updateLastlogin($adminid);
			if(isset($_POST['rememberme'])) {
				$year = time() + (10 * 365 * 24 * 60 * 60);
				setcookie('remember_me', $_POST['username'], $year);
			}
			else {
				if(isset($_COOKIE['remember_me'])) {
					$past = time() - 100;
					setcookie(remember_me, gone, $past);
				}
			}
			//session_start();
			$_SESSION['id'] = $adminid;
			$_SESSION['admin'] = 'Y';
			header("location:settings.php");
		}
		else {
			$error_msg = '<div class="alert alert-danger alert-dismissable" style="font-size: 12px;">
				<i class="icon-exclamation-sign"></i><Error> *Login error* </strong> Please check admin username/password.<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button></div>';
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

	<title>Admin Login</title>
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

	<div class="all-wrapper no-menu-wrapper">
		<div class="login-logo-w">
			<img src="../assets/images/logo.png"> 
		</div>
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="content-wrapper bold-shadow">
					<div class="content-inner">
						<div class="main-content main-content-grey-gradient no-page-header">
							<div class="main-content-inner">
								<form method="POST" name="loginform" id="loginform" action="" role="form">
									<h3 class="form-title form-title-first"><i class="icon-lock"></i> ADMIN LOGIN</h3>
									<?=$error_msg?>
									<div class="form-group">
										<label>Username</label>
										<input type="text" class="form-control" placeholder="Enter Username" name="username" <?=(isset($_COOKIE['remember_me'])? 'value='.$_COOKIE['remember_me']: '')?>>
									</div>
									<div class="form-group">
										<label>Password</label>
										<input type="password" class="form-control" placeholder="Password" name="password">
									</div>
									<div class="form-group">
										<div class="checkbox">
											<label for="rememberme">
												<input type="checkbox" name="rememberme" style="height: 0px;" <?=(isset($_COOKIE['remember_me'])? 'checked="checked"': '')?>> Remember me
											</label>
										</div>
									</div>
									<button type="submit" class="btn btn-warning btn-lg">Sign in</button>
									<a href="#" onclick='document.getElementById("loginform").reset()' class="btn btn-link">Cancel</a>
								</form>
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

</body>

</html>