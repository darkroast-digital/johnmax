<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  
  <script type="text/javascript">
  $(function () {
    
    //grab the entire query string
    var query = document.location.search.replace('?', '');
    
    //extract each field/value pair
    query = query.split('&');
    
    //run through each pair
    for (var i = 0; i < query.length; i++) {
    
      //split up the field/value pair into an array
      var field = query[i].split("=");
      
      //target the field and assign its value
      $("input[name='" + field[0] + "'], select[name='" + field[0] + "']").val(field[1]);
    
    }
  });
</script>
  
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

	<title>Check Team Status</title>

</head>

<body>

	

	<div style="padding-left: 28px;">
		<div class="row">
			<div style="max-width: 475px;">

			  <div class="content-wrapper">
				  <div class="main-content2">
					<div class="row">
						<div class="widget">
						  <div class="widget-content-white glossed">
							<div class="padded">
								<form name="checkstatus" target="_parent"  method=get action="team-status.php" role="form" class="form-horizontal">
								
									<h3 class="form-title form-title-first"><i class="icon-trophy"></i>Check Team Status</h3>
									<div class="form-group">
										<label class="col-md-4 control-label">Team ID</label>
										<div class="col-md-8">
											<input type="text" name='teamid' id="teamid" class="form-control" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-4 control-label">Email Address</label>
										<div class="col-md-8">
											<input type="text" name='emailid' id="emailid" class="form-control" placeholder="Enter Email Address">
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-12">
											<button class="btn btn-danger" name="submit" type="submit" style="margin-top: 50px;">Check Status</button>
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
<script type="text/javascript" language="JavaScript">
<!-- Copyright 2006 Bontrager Connection, LLC
function FillForm() {
// Specify form's name between the quotes on next line.
var FormName = "checkstatus";
var questionlocation = location.href.indexOf('?');
if(questionlocation < 0) { return; }
var q = location.href.substr(questionlocation + 1);
var list = q.split('&');
for(var i = 0; i < list.length; i++) {
   var kv = list[i].split('=');
   if(! eval('document.'+FormName+'.'+kv[0])) { continue; }
   kv[1] = unescape(kv[1]);
   if(kv[1].indexOf('"') > -1) {
      var re = /"/g;
      kv[1] = kv[1].replace(re,'\\"');
      }
   eval('document.'+FormName+'.'+kv[0]+'.value="'+kv[1]+'"');
   }
}
FillForm();
//-->
</script>


<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/jquery-ui.min.js"></script>
<script src='assets/js/bootstrap/dropdown.js'></script>
<script src='assets/js/bootstrap/collapse.js'></script>
<script src='assets/js/bootstrap/alert.js'></script>
<script src='assets/js/bootstrap/transition.js'></script>
<script src='assets/js/bootstrap/tooltip.js'></script>
<script src='assets/js/for_pages/forms.js'></script>

</body>

</html>