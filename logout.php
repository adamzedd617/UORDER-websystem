<?php
	session_start();

    if ($_SESSION["loggedinv"] === true)
    {
    	unset($_SESSION["vendorid"]);
    	unset($_SESSION["name"]);
    	unset($_SESSION["loggedinv"]);
    	header("location: index.php");
    	exit();
    }



?>
