<?php 
    require_once("db_functions.php"); 
	include("db_connection.php");
?>
	
<?php
   start_session();
   user_confirm_logged_in();
   $denied = null;
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
		$denied = "Access Denied!";
	} elseif($_SESSION["current_member_id"] !== $_GET["sender_id"]) {
		$denied = "Access Denied!";
	} else {
		$_SESSION["sender_id"] = $_GET["sender_id"];
	    $selected_communion = null;
	    $selected_page = find_page_by_id($_SESSION["current_page"]);		
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

	 <?php  if (($_SESSION["current_member_id"] === $_GET["sender_id"]) && ($_SESSION["current_page"] === $_GET["page"])){ ?>
     <?php
	 $chair = find_member_by_member_title("chairperson");  $chairperson = mysqli_fetch_assoc($chair);
	 $Gsec = find_member_by_member_title("general secretary"); $General_Scretary = mysqli_fetch_assoc($Gsec);
     $excom_ac = find_member_by_member_title("ExCom Academic");	 $Excom_academic = mysqli_fetch_assoc($excom_ac); 
	 $excom_fin = find_member_by_member_title("ExCom Finance");	 $Excom_finance = mysqli_fetch_assoc($excom_fin); 
	 $excom_sp = find_member_by_member_title("ExCom Sports");	 $Excom_sports = mysqli_fetch_assoc($excom_sp); 
	 ?>

		<h2>Request</h2>
		<p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_name"]);?></p>
		
			<ul class="form">
			<form action="ask.php" method="post">
		
			 <p>Send to:</p>
			  <p><?php if ($_SESSION["current_member_id"] !== $chairperson["member_id"]){?>Chairperson: &nbsp;<input type="radio" name="chairperson_id" value="<?php echo $chairperson["member_id"]; ?>" /> &nbsp; &nbsp;<?php } ?>
			  <?php if ($_SESSION["current_member_id"] !== $General_Scretary["member_id"]){?>
			  General Secretary: &nbsp;<input type="radio" name="general_secretary_id" value="<?php echo $General_Scretary["member_id"]; ?>" /> &nbsp;
			  <?php } ?>
			  <?php if ($_SESSION["current_member_id"] !== $Excom_academic["member_id"]){?>
			  ExCom Academic: &nbsp; <input type="radio" name="excom_id" value="<?php echo $Excom_academic["member_id"]; ?>" /> &nbsp;
			  <?php } ?>
			  <?php if ($_SESSION["current_member_id"] !== $Excom_finance["member_id"]){?>
			  ExCom Finance: &nbsp; <input type="radio" name="excom_id" value="<?php echo $Excom_finance["member_id"]; ?>" /> &nbsp;
			  <?php } ?>
			  <?php if ($_SESSION["current_member_id"] !== $Excom_sports["member_id"]){?>
			  ExCom Sports: &nbsp; <input type="radio" name="excom_id" value="<?php echo $Excom_sports["member_id"]; ?>" /></p>
			  <?php } ?>
			 <p> Type request here:</p>
			 <textarea name="content" rows="20" cols="80"></textarea>
			 <p><input type="submit" name="submit" value="Send" /></p>
				
			</form>	    
		   </ul>
		   <p><a href="ask_help.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["current_page"]);?>" style="color:green;">Cancel</a></p>
		   
	 <?php } else {
		 echo $denied; 
	 } ?>	   
	 </div>	 
	</div>		   
<?php include("footer.php");?>