<?php 
    require_once("db_functions.php"); 
	include("db_connection.php");
?>
	
<?php
   start_session();
   user_confirm_logged_in();

    $denied = null;
		
	if (isset($_POST["submit"])){


		$new_comment = array("post_id" =>"{$_SESSION["post_id"]}", "sender_id" =>"{$_SESSION["sender_id"]}", "content" =>"{$_POST["content"]}");
		create_new_comment($new_comment);
		redirect_to("posts.php?member={$_SESSION["current_member_id"]}&page={$_SESSION["current_page"]}"); 

	} elseif (isset($_GET["sender_id"]) && isset($_GET["post_id"]) && isset($_GET["page"])){
		$_SESSION["sender_id"] = $_GET["sender_id"];
		$_SESSION["post_id"] = $_GET["post_id"];
		$selected_communion = null;
		$selected_page = find_page_by_id($_SESSION["current_page"]);
	} else {
		$denied = "Access denied";
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

		<h2>New comment</h2>
		<p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_name"]);?></p>

			<ul class="form">
			<form action="send_comment.php?" method="post">

				<p> My comment:</p>
				<textarea name="content"   rows="20" cols="80"></textarea>
				<p><input type="submit" name="submit" value="Send comment" /></p>

				<p><a href="posts.php?member=<?php echo ucwords($_SESSION["current_member_id"]);?>&page=<?php echo ucwords($_SESSION["current_page"]);?>" style="color:green;">Cancel</a></p>

            </form>	    
		   </ul>
	 <?php } else { echo $denied;}?>		   
	 </div>	 
	</div>		   
<?php include("footer.php");?>