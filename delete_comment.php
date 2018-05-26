<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>

<?php
   start_session();
   $denied = null;
   $post["content"] = null;
   
	 if (isset($_GET["comment_id"]) && isset($_GET["sender_id"])){ //reject those who logged in but not allowed in current navigation
		 
		if ($_SESSION["current_member_id"] !== $_GET["sender_id"]){
            
			$denied = "Access denied!";
		} else {
	    delete_comment($_GET["comment_id"]);
		$_SESSION["message"] = "comment deleted successeful";
		redirect_to("posts.php?member={$_SESSION["current_member_id"]}");
	    }
	} else {
		
     user_confirm_logged_in(); // reject those who have not logged in
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
<?php include("footer.php");?>jm