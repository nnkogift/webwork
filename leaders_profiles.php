<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>

<?php
   start_session();
   //user_confirm_logged_in();
   	 if ( isset($_GET["communion"]) && isset($_GET["member"])){
		user_confirm_logged_in(); 
		
	 $id = $_GET["communion"];
	 $selected_communion = find_communion_by_id($id);
	 $selected_page = find_default_page_for_communion($selected_communion["id"]);
	 $member = find_member_by_member_id($_GET["member"]);
	 	 
	} elseif (isset($_GET["page"]) && isset($_GET["member"])){
		user_confirm_logged_in();
		
	 $selected_communion = null;
	 $id = $_GET["page"];
	 $selected_page = find_page_by_id($id);
	 $member = find_member_by_member_id($_GET["member"]);
	 
	} else {
     user_confirm_logged_in();
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
  <h2>View leaders profile.</h2>
  <p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_name"]);?></p>
   <p><a href="leaders_profiles.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["page"]);?>&leader=chairperson">Chairperson</a></p>
   <p><a href="leaders_profiles.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["page"]);?>&leader=<?php echo urlencode("general secretary")?>">General secretary</a></p>
   <p><a href="leaders_profiles.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["page"]);?>&leader=ExCom Academic">ExCom Academic</a></p>
   <p><a href="leaders_profiles.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["page"]);?>&leader=ExCom Finance">ExCom Finance</a></p>   
   <p><a href="leaders_profiles.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["page"]);?>&leader=ExCom Sports">ExCom Sports</a></p>
   
  <?php
  if (isset($_GET["leader"])){
	  $leader = strtolower(substr($_GET["leader"], 0, 5));
  ?>
   <?php
   if ($leader == "excom"){

     $excom_set = find_member_by_member_title($_GET["leader"]);
	 $excom = mysqli_fetch_assoc($excom_set);
  ?>
	         <table>
				<tr>
				<td>Student ID:</td><td><?php echo htmlentities($excom["member_id"]);?></td>
				</tr>
				<tr>
				<td>First Name:</td><td><?php echo ucwords(htmlentities($excom["first_name"]));?></td>
				</tr>
				<tr>
				<td>Middle Initial:</td><td><?php echo ucwords(htmlentities($excom["middle_initial"]));?></td>
				</tr>
				<tr>
				<td>Last Name:</td><td><?php echo ucwords(htmlentities($excom["last_name"]));?></td>
				</tr>
				<tr>
				<td>Membership:</td><td><?php echo ucwords(htmlentities($excom["title"]));?></td>
				</tr>
				<tr>
				<td>Programme:</td><td><?php echo ucwords(htmlentities($excom["programme"]));?></td>
				</tr>
				<tr>
				<td>Year of study:</td><td><?php echo $excom["year_of_study"];?></td>
				</tr>
				<tr>
				<td>Email:</td><td><?php echo htmlentities($excom["email"]);?></td>
				</tr>
				<tr>
				<td>Phone:</td><td><?php echo htmlentities($excom["phone"]);?></td>
				</tr>
				<tr>
		 </table>
	<?php
     } else { 
	     $executive_set = find_member_by_member_title($_GET["leader"]); 
		 $executive = mysqli_fetch_assoc($executive_set);
    ?>
	         <table>
				<tr>
				<td>Student ID:</td><td><?php echo htmlentities($executive["member_id"]);?></td>
				</tr>
				<tr>
				<td>First Name:</td><td><?php echo ucwords(htmlentities($executive["first_name"]));?></td>
				</tr>
				<tr>
				<td>Middle Initial:</td><td><?php echo ucwords(htmlentities($executive["middle_initial"]));?></td>
				</tr>
				<tr>
				<td>Last Name:</td><td><?php echo ucwords(htmlentities($executive["last_name"]));?></td>
				</tr>
				<tr>
				<td>Membership:</td><td><?php echo ucwords(htmlentities($executive["title"]));?></td>
				</tr>
				<tr>
				<td>Programme:</td><td><?php echo ucwords(htmlentities($executive["programme"]));?></td>
				</tr>
				<tr>
				<td>Year of study:</td><td><?php echo $executive["year_of_study"];?></td>
				</tr>
				<tr>
				<td>Email:</td><td><?php echo htmlentities($executive["email"]);?></td>
				</tr>
				<tr>
				<td>Phone:</td><td><?php echo htmlentities($executive["phone"]);?></td>
				</tr>
				<tr>
		 </table>
	<?php
     }
    ?>
	
	<?php
     }
    ?>
			
	<p><a href="profiles.php?page=<?php echo urlencode($_SESSION["page"]);?>&member=<?php echo urlencode($_SESSION["current_member_id"]);?>">Cancel</a></p>

    </div>
</div>
<?php include("footer.php");?>