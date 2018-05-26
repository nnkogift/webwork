<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>
<?php
   start_session();
   $denied = null;
   $post["content"] = null;
   
	 if (isset($_GET["post_id"]) && isset($_GET["member"]) && isset($_GET["page"])){ //reject those who logged in but not allowed in current navigation
		 
		if ($_SESSION["current_member_id"] !== $_GET["member"]){
            
			$denied = "Access denied!";
		} else {
		 $post = find_post_by_post_id($_GET["post_id"]);
		}
	 $selected_communion = null;
	 $selected_page = find_page_by_id($_GET["page"]);
	} else {
		
     user_confirm_logged_in(); // reject those who have not logged in
	 $_GET["post_id"] = null;
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
  
   <h2>Post Content.</h2>
   <p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_name"]);?></p>
   <div class="view-content">
	
		<?php echo nl2br(ucfirst(htmlentities($post["content"])));?>
	
	</div>
		
   <p><a href="posts.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["current_page"]);?>">Cancel</a></p>
   <?php } elseif($denied !== null) { echo htmlentities($denied); ?>
   <?php } else {?>
    <p>Please Select page from the navigation.</p>
   <?php }// end of if ($_SESSION["current_member_id"] === $_GET["member"]) ?>		
		
		</div>
 </div>
<?php include("footer.php");?>