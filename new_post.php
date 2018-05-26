<?php 
    require_once("db_functions.php"); 
	include("db_connection.php");
?>
	
<?php
   start_session();
   user_confirm_logged_in();
        $denied = null;
	if (isset($_POST["submit"])){
		   
		 $new_post = array("sender_id" =>"{$_SESSION["sender_id"]}", "content" =>"{$_POST["content"]}");
		 create_new_post($new_post);
		 redirect_to("posts.php?member={$_SESSION["current_member_id"]}&page={$_SESSION["current_page"]}"); 

	} elseif (isset($_GET["sender_id"]) && isset($_GET["page"])){
		$_SESSION["sender_id"] = $_GET["sender_id"];
	    $selected_communion = null;
	    $selected_page = find_page_by_id($_GET["page"]);
	} else {
		$_SESSION["sender_id"] = "";
		$denied = "Access denied";
	    $selected_communion = null;
	    $selected_page = null;		
	}
?>

<?php include("header.php");?>	   
	 <div id="main">
	 <div  id="navigation">	
	 <p>&laquo; <a href="user_page.php">Home</a></p>
		<?php
		if (user_logged_in()){
		?>
		<?php if($_SESSION["current_member_id"] === $_GET["sender_id"]) { echo public_navigation($selected_communion, $selected_page);}?>
		<?php
		}
		?>
     </div>
	
	 <div id="page">

	 <?php if ($denied === null){ ?>

		<h2>New post</h2>
		<p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_name"]);?></p>
		
			<ul class="form">
			<form action="new_post.php" method="post">
				<p> My post:</p>
				<textarea name="content" rows="20" cols="80"></textarea>
				<p><input type="submit" name="submit" value="Send post" /></p>
			</form>	    
		   </ul>
		<p><a href="posts.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["current_page"]);?>">Cancel</a></p>
	 <?php } else { echo $denied;}?>		   
	 </div>	 
	</div>		   
<?php include("footer.php");?>