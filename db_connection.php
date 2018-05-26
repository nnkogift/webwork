<?php
//creating database connection
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_USER_PASS", "!ryio_18");
define("DB_NAME", "daruso_coict");
$connectparam = mysqli_connect(DB_HOST, DB_USER, DB_USER_PASS, DB_NAME);

 if (mysqli_connect_error()){
	 die ("Database connection failed: " . mysqli_connect_error() . "(" .mysqli_connect_errno() . ")" );	 
 }
?>