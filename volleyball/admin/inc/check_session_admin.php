<?
	session_start();
	if(!isset($_SESSION['admin']) && $_SESSION['admin'] != 'Y') {
		header("location:login.php");
	}
?>
