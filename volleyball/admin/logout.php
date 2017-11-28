<?PHP
	session_start();

	setcookie("admin", false);
	session_destroy();
	header("Location: login.php");

?>