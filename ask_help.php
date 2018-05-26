<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>

<?php
   start_session();
   $denied = null;
   $testmember = isset($_GET["member"]);
   $testpage = isset($_GET["page"]);
	if ($testmember && $testpage ){
		
		if ($_SESSION["current_member_id"] !== $_GET["member"]){

			$denied = "Access denied!";
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
   <?php
    $test = $_SESSION["current_member_id"] === $_GET["member"];
    if ($test){
	?>

   <h2>Help</h2>
   <p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_name"]);?></p>
   <p>+<a href="ask.php?sender_id=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["current_page"]);?>">Ask help</a></p>
   <?php if (isset($_SESSION["message"])){ ?>
   
   <div class = "message">
   <?php echo $_SESSION["message"]; $_SESSION["message"] = null; ?>
   </div>
   
   <?php } ?>
   
  <?php
     $questions = find_questions_by_member_id($_SESSION["current_member_id"]);
	 while($question = mysqli_fetch_assoc($questions)){	
     $sender = find_member_by_member_id($question["sender_id"]);
     	 
  ?>
    <p style="font-weight: bold;">Sender</p>

			<p><?php echo ucfirst(htmlentities($sender["first_name"]));?>&nbsp;
				<?php echo ucfirst(htmlentities($sender["middle_initial"]));?>&nbsp;
				<?php echo ucfirst(htmlentities($sender["last_name"]));?>&nbsp;
				
               <a href="view_question.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&question_id=<?php echo $question["id"];?>" style="color:green;" onclick= "return confirm('View request?');">View</a>&nbsp;
			  
			   <a href="delete_question.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&question_id=<?php echo $question["id"];?>" style="color:red;" onclick= "return confirm('Are you sure?');">Delete</a>&nbsp;
			   <a href="send_help.php?sender_id=<?php echo urlencode($_SESSION["current_member_id"]);?>" style="color:green;">Comment</a></p>
			
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