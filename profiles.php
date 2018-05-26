<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>

<?php
   start_session();
   //user_confirm_logged_in();
    $denied = null;
	 if ( isset($_GET["communion"]) && isset($_GET["member"])){
		 
		if ($_SESSION["current_member_id"] !== $_GET["member"]){

			$denied = "Access denied!";
		} else {
			$member = find_member_by_member_id($_GET["member"]);
		}
		
	 $id = $_GET["communion"];
	 $_SESSION["communion"] = $id;
	 $selected_communion = find_communion_by_id($id);
	 $selected_page = find_default_page_for_communion($selected_communion["id"]);
	 $_SESSION["page"] = $selected_page["id"];
	 
	} elseif (isset($_GET["page"]) && isset($_GET["member"])){
		
		if ($_SESSION["current_member_id"] !== $_GET["member"]){

    		$denied = "Access denied!";
		} else {
			$member = find_member_by_member_id($_GET["member"]);
		}
		
	 $selected_communion = null;
	 $id = $_GET["page"];
	 $selected_page = find_page_by_id($id);
	 $_SESSION["page"] = $selected_page["id"];
	 
	} else {
     user_confirm_logged_in();
	 $_GET["member"] = null;
	 $member = null;
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

   <?php
    if(isset($_GET["page"]) || isset($_GET["member"])){
   ?>
   <h2>User Profile.</h2>
   <p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_name"]);?></p>
         <table>
				<tr>
				<td>Student ID:</td><td><?php echo htmlentities($member["member_id"]);?></td>
				</tr>
				<tr>
				<td>First Name:</td><td><?php echo ucwords(htmlentities($member["first_name"]));?></td>
				</tr>
				<tr>
				<td>Middle Initial:</td><td><?php echo ucwords(htmlentities($member["middle_initial"]));?></td>
				</tr>
				<tr>
				<td>Last Name:</td><td><?php echo ucwords(htmlentities($member["last_name"]));?></td>
				</tr>
				<tr>
				<td>Membership:</td><td><?php echo ucwords(htmlentities($member["title"]));?></td>
				</tr>
				<tr>
				<td>Programme:</td><td><?php echo ucwords(htmlentities($member["programme"]));?></td>
				</tr>
				<tr>
				<td>Year of study:</td><td><?php echo $member["year_of_study"];?></td>
				</tr>
				<tr>
				<td>Email:</td><td><?php echo htmlentities($member["email"]);?></td>
				</tr>
				<tr>
				<td>Phone:</td><td><?php echo htmlentities($member["phone"]);?></td>
				</tr>
				<tr>
				<td><a href="edit_profile.php?edit=<?php echo urlencode($_SESSION["current_member_id"]);?>" style="color: green;">Edit</a></td>
				</tr>
 			    <tr>
			    <td><a href="leaders_profiles.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["page"]);?>">View leaders profile</a></td>
				</tr>
				<tr>
				<td><a href="user_page.php">Cancel</a></td>
				</tr>
		 </table>
   <?php } else { ?>
   <p>Please Select page from the navigation.</p>
   <?php } // end of if(isset($_GET["page"]) || isset($_GET["member"])) ?>
   
<?php } elseif($denied !== null) { echo htmlentities($denied); ?>
<?php } else {?>
    <p>Please Select page from the navigation.</p>
<?php }// end of if ($_SESSION["current_member_id"] === $_GET["member"]) ?>

   </div>
 </div>
<?php include("footer.php");?>