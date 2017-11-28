<?php 

include("application.php");

class db extends mysqli
{

	 public function __construct() {
        parent::__construct(HOST, USER, PASS, DB);

        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
    }

}

?>