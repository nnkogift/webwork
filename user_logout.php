<?php 
 require_once("db_functions.php"); 
 include("db_connection.php");

    start_session();
	/* clear user identifier;
     <-- what is to be done here
	*/
	
	 $_SESSION['current_member_id'] = null;
	 $_SESSION['current_member_name'] = null;
	 $_SESSION['title'] = null;
	
	redirect_to("user_login.php");
	
 mysqli_close($connectparam);	 
	
?>
