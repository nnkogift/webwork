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
		 $notifiction = find_notifiction_by_notifiction_id($_GET["notification_id"]);
		}
	 $selected_communion = null;
	 $selected_page = null;
	} else {
		
     user_confirm_logged_in(); // reject those who have not logged in
	 $_GET["notification_id"] = null;
	 $_GET["member"] = null;
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
   <?php if ($_SESSION["current_member_id"] === $_GET["member"]){?>
  
   <h2>Notifiction Content.</h2>
	<div class="view-content">
	
		<?php echo nl2br(ucfirst(htmlentities($notifiction["content"])));?>
	
	</div>
		
   <p><a href="notifications.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["current_page"]);?>">Cancel</a></p>
   <?php } elseif($denied !== null) { echo htmlentities($denied); ?>
   <?php } else {?>
    <p>Please Select page from the navigation.</p>
   <?php }// end of if ($_SESSION["current_member_id"] === $_GET["member"]) ?>		
		
		</div>
 </div>
<?php include("footer.php");?>