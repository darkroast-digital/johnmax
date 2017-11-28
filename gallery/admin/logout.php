<?php

session_start();
$_SESSION['logged_in'] = false;
$_SESSION['auth_token'] = null;
$_SESSION['user_id'] = 0;
?><script type="text/javascript">
<!--
window.location = "index.php";
//-->
</script>
