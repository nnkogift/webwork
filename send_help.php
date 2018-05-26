<?php 
    require_once("db_functions.php"); 
	include("db_connection.php");
?>
	
<?php
   start_session();
   user_confirm_logged_in();

	if (isset($_POST["submit"])){
		
		if (!isset($_POST["chairperson_id"])){$_POST["chairperson_id"] = null;}
		if (!isset($_POST["general_secretary_id"])){$_POST["general_secretary_id"] = null;}
		if (!isset($_POST["excom_id"])){$_POST["excom_id"] = null;}
		   
	$new_question = array("sender_id" =>"{$_SESSION["sender_id"]}", 
	                          "chairperson_id" =>"{$_POST["chairperson_id"]}", 
	                          "general_secretary_id" =>"{$_POST["general_secretary_id"]}", 
							  "excom_id" =>"{$_POST["excom_id"]}", 
							  "content" =>"{$_POST["content"]}");
	 send_question($new_question);
	 redirect_to("ask_help.php?member={$_SESSION["current_member_id"]}&page={$_SESSION["current_page"]}"); 

	}elseif(!isset($_GET["sender_id"])){
		$_SESSION["sender_id"] = $_SESSION["current_member_id"]; // from excom 
	} else {$_SESSION["sender_id"] = $_GET["sender_id"];}
?>

<?php include("header.php");?>	   
	 <div id="main">
	 <div  id="navigation">	
	 <p>&laquo; <a href="user_page.php">Home</a></p>
     &nbsp;
     </div>
	
	 <div id="page">

	 <?php // if ($denied === null){ ?>

		<h2>Send Help</h2>
		<p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_id"]);?></p>
		
			<ul class="form">
			<form action="ask.php" method="post">
		
			 <p>Send to:</p>
			  <p>Chairperson ID: &nbsp;<input type="text" name="chairperson_id" value="" /> &nbsp; &nbsp;
			  General Secretary ID: &nbsp;<input type="text" name="general_secretary_id" value="" /> &nbsp;
			  Excom ID: &nbsp; <input type="text" name="excom_id" value="" /></p>
			 <p> Provide help here:</p>
			 <textarea name="content" rows="20" cols="80"></textarea>
			 <p><input type="submit" name="submit" value="Send" /></p>
				
			</form>	    
		   </ul>
		   <p><a href="ask_help.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["current_page"]);?>" style="color:green;">Cancel</a></p>
		   
	 <?php //} else { echo $denied;}?>		   
	 </div>	 
	</div>		   
<?php include("footer.php");?>