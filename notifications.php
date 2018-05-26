<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>

<?php
   start_session();
   $denied = null;
   
	if (isset($_GET["page"]) && isset($_GET["member"])){
		
		if ($_SESSION["current_member_id"] !== $_GET["member"]){

			$denied = "Access denied!";
		}
		
	if (isset($_GET["sender_id"])){
		
	$notifications = find_notification_by_sender_and_notification_id($_GET["sender_id"], $_GET["notification_id"]); //after aproval
	$notification = mysqli_fetch_assoc($notifications);
    $new_post = array("sender_id" =>"{$_GET["sender_id"]}", "content" =>"{$notification["content"]}");
	create_new_post($new_post);
	
	}
		
	 $selected_communion = null;
	 $_SESSION["current_page"] = $_GET["page"];
	 $selected_page = find_page_by_id($_SESSION["current_page"]);
	 
	} else {
     user_confirm_logged_in();
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

   <h2>Notifications</h2>
   <p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_name"]);?></p>
   <p>+<a href="send_notification.php?sender_id=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["current_page"]);?>">Send notification</a></p>

   <?php if (isset($_SESSION["message"])){ ?>
   
   <div class = "message">
   <?php echo $_SESSION["message"]; $_SESSION["message"] = null; ?>
   </div>
   
   <?php } ?>
   
  <?php
     $notifications = find_notifications_by_member_id($_SESSION["current_member_id"]);
	 while($notification = mysqli_fetch_assoc($notifications)){	
     $sender = find_member_by_member_id($notification["sender_id"]);
     	 
  ?>
    <p style="font-weight: bold;">Sender</p>

			<p><?php echo ucfirst(htmlentities($sender["first_name"]));?>&nbsp;
				<?php echo ucfirst(htmlentities($sender["middle_initial"]));?>&nbsp;
				<?php echo ucfirst(htmlentities($sender["last_name"]));?>&nbsp;
				
               <a href="view_notification.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&notification_id=<?php echo $notification["id"];?>" style="color:green;" onclick= "return confirm('View notification?');">View</a>&nbsp;
			  
			  <?php
                $member = find_member_by_member_id($_SESSION["current_member_id"]);
				$member["title"] = str_replace(" ", "_", $member["title"]);
				strtolower($member["title"]);
				if ($member["title"] !== "excom"){
			  ?>
			   <a href="notifications.php?sender_id=<?php echo urlencode($sender["member_id"]);?>&notification_id=<?php echo $notification["id"];?>&member=<?php echo urlencode($_SESSION["current_member_id"]);
			   ?>&page=<?php echo urlencode($_SESSION["current_page"]);?>" style="color:red;" onclick= "return confirm('Approve?');">Approve</a>&nbsp;				
			  <?php }?>
			   <a href="delete_notification.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&notification_id=<?php echo $notification["id"];?>" style="color:red;" onclick= "return confirm('Are you sure?');">Delete</a>&nbsp;
			   <a href="send_notification.php?sender_id=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["current_page"]);?>" style="color:green;">Comment</a></p>
			
  <?php
	}
  ?>
			<p><a href="profiles.php?page=<?php echo urlencode($_SESSION["page"]);?>&member=<?php echo urlencode($_SESSION["current_member_id"]);?>">Cancel</a></p>

<?php } elseif($denied !== null) { echo htmlentities($denied); ?>
<?php } else {?>
    <p>Please Select page from the navigation.</p>
<?php }// end of if ($_SESSION["current_member_id"] === $_GET["member"]) ?>

    </div>
 </div>
<?php include("footer.php");?>