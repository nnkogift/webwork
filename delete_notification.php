<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>

<?php
   start_session();
   $denied = null;
   
	 if (isset($_GET["notification_id"]) && isset($_GET["member"])){ //reject those who logged in but not allowed in current navigation
		 
		if ($_SESSION["current_member_id"] !== $_GET["member"]){
            
			$denied = "Access denied!";
		} else {
	    delete_notification($_GET["notification_id"]);
		$_SESSION["message"] = "Notification deleted successeful";
		redirect_to("notifications.php?member={$_SESSION["current_member_id"]}&page={$_SESSION["current_page"]}");
	    }
	} else {
		
     user_confirm_logged_in(); // reject those who have not logged in
	 $denied = "Access denied!";
	 $selected_communion = null;
	 $selected_page = null;
}
?>

 <div id="main">

   <div id="navigation">
 
    <p>&laquo; <a href="user_page.php">Home</a></p>
   	<?php
	if (user_logged_in()){
	?>
	<?php echo public_navigation($selected_communion, $selected_page);?>
	<?php
	}
	?>
	
   </div>
 
   <div id="page">
   
	   <?php 
		if($denied !== null) { 
		 echo htmlentities($denied);
		} 
	   ?>		
		
		</div>
 </div>
<?php include("footer.php");?>