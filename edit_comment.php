<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>

<?php
   start_session();
   $denied = null;
   $comment["content"] = null;
   
	 if (isset($_POST["submit"])){ //reject those who logged in but not allowed in current navigation
		 
		$new_comment = array("id" =>"{$_SESSION["comment_id"]}", "post_id" =>"{$_SESSION["post_id"]}", "sender_id" =>"{$_SESSION["sender_id"]}", "content" =>"{$_POST["content"]}");
	    edit_comment($new_comment);
		$_SESSION["message"] = "Comment edited successeful";
		redirect_to("posts.php?member={$_SESSION["current_member_id"]}");
		 
	 } elseif (isset($_GET["post_id"]) && isset($_GET["sender_id"]) && isset($_GET["comment_id"])){ //reject those who logged in but not allowed in current navigation
		 
		if ($_SESSION["current_member_id"] !== $_GET["sender_id"]){
            
			$denied = "Access denied!";
		}

        $_SESSION["sender_id"] = $_GET["sender_id"];
		$_SESSION["post_id"] = $_GET["post_id"];
		$_SESSION["comment_id"] = $_GET["comment_id"];
		$comment = find_comment_by_comment_id($_SESSION["comment_id"]);
    }
?>
   
	 <div id="main">
	 <div  id="navigation">	
	 <p>&laquo; <a href="user_page.php">Home</a></p>
     &nbsp;
     </div>
 
   <div id="page">
	 <?php if ($denied === null){ ?>

		<h2>Edit comment</h2>
		<p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_id"]);?></p>
		
			<ul class="form">
			<form action="edit_comment.php" method="post">
				<p> My comment:</p>
				<textarea name="content"   rows="20" cols="80"><?php echo htmlentities($comment["content"])?></textarea>
				<p><input type="submit" name="submit" value="edit comment" /></p>
				<p><a href="posts.php?member=<?php echo ucwords($_SESSION["current_member_id"]);?>" style="color:green;">Cancel</a></p>
			</form>	    
		   </ul>
	 <?php } else { echo $denied;}?>		   	
		</div>
 </div>
<?php include("footer.php");?>