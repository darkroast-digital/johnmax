<?php



class sendemail

{

	function newteamregitration($data)

	{

		$to = $data['emailid'] ;

		$from = "beach@johnmax.ca";



		$subject = 'Team Registration at John MAX SPORTS & WINGS';



		$message ='

		  <table>

		  <tr>

			<td style="padding-bottom: 50px;"><img style="width:230px;" src="'.BASE_URL.'/assets/images/logo.png"></td>

		  </tr>

		  <tr>

			<td><span style="font-family:Arial, Gadget, sans-serif;">Hi '.$data['captname'].' ,<br><br>

			Your new team "<b>'.$data['teamname'].'</b>" with Team ID "<b>'.TID_PREFIX.$data['teamid'].'</b>" has been successfully registered. <br><br>

			

			Your team will be actived after receiving full payment.<br><a href="'.BASE_URL.'/team-status.php?teamid='.TID_PREFIX.$data['teamid'].'&emailid=&submit=">Click here</a> to check team status. <br><br>

			<b>Captain Name</b> : '.$data['captname'].'<br>

			<b>League Night</b> : '.$data['evenight'].'<br><br>

			Thank You</font></td>

		  </tr>

		  <tr>

			<td style="font-family:Arial, Gadget, sans-serif;">&copy; John MAX SPORTS & WINGS</td>

		  </tr>

		  </table>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";

		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: John MAX SPORTS & WINGS <'.$from.'>' . "\r\n";

		//echo $message;die();

		mail($to, $subject, $message, $headers);

	}

	

	function teamactivationmail($data)

	{

		$to = $data['emailid'] ;

		$from = "beach@johnmax.ca";



		$subject = 'Team Activated at John MAX SPORTS & WINGS';



		$message ='

		  <table>

		  <tr>

			<td><img style="width:230px;" src="'.BASE_URL.'/assets/images/logo.png"></td>

		  </tr>

		  <tr>

			<td><font face="verdana">Hi ,<br><br>

			Your new team "<b>'.$data['teamname'].'</b>" with Team ID "<b>'.TID_PREFIX.$data['teamid'].'</b>" has been successfully activated.<br><br>

			<b>Captain Name</b> : '.$data['captname'].'<br>

			<b>League Night</b> : '.$data['evenight'].'<br><br>

			Thank You</font></td>

		  </tr>

		  <tr>

			<td>&copy; John MAX SPORTS & WINGS</td>

		  </tr>

		  </table>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";

		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: John MAX SPORTS & WINGS <'.$from.'>' . "\r\n";

		//echo $message;die();

		mail($to, $subject, $message, $headers);

	}



	function playerinvitation($emailid, $data)

	{

		$to = $emailid;

		$from = "beach@johnmax.ca";



		$subject = 'Player Invitation from John MAX SPORTS & WINGS';



		$message ='

		  <table>

		  <tr>

			<td><img style="width:230px;" src="'.BASE_URL.'/assets/images/logo.png"></td>

		  </tr>

		  <tr>

			<td><font face="verdana">Hi ,<br><br>

			You have an invitation from a volleyball team "<b>'.$data['teamname'].'</b>" with Team ID "<b>'.TID_PREFIX.$data['teamid'].'</b>".<br><br>

			<b>Captain Name</b> : '.$data['captname'].'<br>

			<b>League Night</b> : '.$data['evenight'].'<br><br>

			<a href="'.BASE_URL.'/join-team.php?teamid='.$data['teamid'].'">Click here to join the team.</a><br><br>

			Thank You</font></td>

		  </tr>

		  <tr>

			<td>&copy; John MAX SPORTS & WINGS</td>

		  </tr>

		  </table>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";

		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: John MAX SPORTS & WINGS <'.$from.'>' . "\r\n";

		//echo $message;die();

		mail($to, $subject, $message, $headers);

	}



	function newplayer($playerdata, $teamdata)

	{

		$to = $playerdata['playeremail'];

		$from = "beach@johnmax.ca";



		$subject = 'Player Registration at John MAX SPORTS & WINGS';



		$message ='

		  <table>

		  <tr>

			<td><img style="width:230px;" src="'.BASE_URL.'/assets/images/logo.png"></td>

		  </tr>

		  <tr>

			<td><font face="verdana">Hi '.$playerdata['playername'].',<br><br>

			You have successfully joined the volleyball team "<b>'.$teamdata['teamname'].'</b>" with Team ID "<b>'.TID_PREFIX.$teamdata['teamid'].'</b>".<br><br>

			<b>Captain Name</b> : '.$teamdata['captname'].'<br>

			<b>League Night</b> : '.$teamdata['evenight'].'<br><br>

			Thank You</font></td>

		  </tr>

		  <tr>

			<td>&copy; John MAX SPORTS & WINGS</td>

		  </tr>

		  </table>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";

		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: John MAX SPORTS & WINGS <'.$from.'>' . "\r\n";

		//echo $message;die();

		mail($to, $subject, $message, $headers);

	}



	function newadmin($data)

	{

		$to = $data['emailid'];

		$from = "beach@johnmax.ca";



		$subject = 'New Admin Account';



		$message ='

		  <table>

		  <tr>

			<td><img style="width:230px;" src="'.BASE_URL.'/assets/images/logo.png"></td>

		  </tr>

		  <tr>

			<td><font face="verdana">Hi '.$data['fullname'].',<br><br>

			New admin account has been successfully registered at John MAX SPORTS & WINGS.<br>

			Below given is your login credentials,<br><br>

			<b>Username</b> : '.$data['username'].'<br>

			<b>Password</b> : '.$data['password'].'<br><br>

			Thank You</font></td>

		  </tr>

		  <tr>

			<td>&copy; John MAX SPORTS & WINGS</td>

		  </tr>

		  </table>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";

		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: John MAX SPORTS & WINGS <'.$from.'>' . "\r\n";

		//echo $message;die();

		mail($to, $subject, $message, $headers);

	}



	function remind($data)

	{

		$to = $data['emailid'];

		$from = "beach@johnmax.ca";



		$subject = 'Payment Reminder';



		$message ='<br><br>

		  <table>

		  <tr>

			<td><img style="width:230px;" src="'.BASE_URL.'/assets/images/logo.png"></td>

		  </tr>

		  <tr>

			<td><font face="verdana">Hi '.$data['captname'].',<br><br>

			Your team, '.$data['teamname'].', still has a balance owing of $'.$data['balanceowed'].'. The deadline for all payments is April 21st. To avoid the forfeit of your deposit, please ensure your team is fully paid before that date.<br><br>

 

			You can pay the remaining balance by <a href="'.BASE_URL.'/pay/index.php?payamount='.$data['balanceowed'].'&eid='.$data['emailid'].'&name='.$data['captname'].'&teamid='.$data['teamid'].'">clicking here</a><br><br>

			 

			Thank you</font></td>

		  </tr>

		  <tr>

			<td>&copy; John MAX SPORTS & WINGS</td>

		  </tr>

		  </table>';

		$headers  = 'MIME-Version: 1.0' . "\r\n";

		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: John MAX SPORTS & WINGS <'.$from.'>' . "\r\n";

		//echo $message;die();

		mail($to, $subject, $message, $headers);

	}

	

}



?>