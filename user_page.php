<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>

<?php
   start_session();
  /*
  this is a default page so no need to confirm log in
  user_confirm_logged_in();
  */
   
	 if ( isset($_GET["communion"])){
		user_confirm_logged_in(); 
		
	 $id = $_GET["communion"];
	 $selected_communion = find_communion_by_id($id);
	 $selected_page = find_default_page_for_communion($selected_communion["id"]);
	 	 
	} elseif (isset($_GET["page"])){
		user_confirm_logged_in();
		
	 $selected_communion = null;
	 $id = $_GET["page"];
	 $selected_page = find_page_by_id($id);
	 
	} else {

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

	    <p>Welcome to DARUSO CoICT UPDATES AND INFORMATION KEEPING SYSTEM<?php if (user_logged_in()){echo ", use the navigation to move across the website.";}?></p>
			<?php 
			  if (user_logged_in()){
			?>
				<p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION['current_member_name']);?></p>
			<?php 
			 }
			?>
		
			<?php
			if (!user_logged_in()){
			?>
			<table>
				<tr>
				<td>Already have an account?</td><td> <a href="user_login.php" style="color: green;" >Please Login.</a></td>
				</tr>
				<tr>
				<td>You have no account?</td><td><a href="sign_up.php" style="color: green;" >Please sign up.</a></td>
				</tr>
			</table>

			<?php
			} else {
			?>
			<p><a href="user_logout.php" style="color: red;" >Logout.</a></p>
            <?php			
			}
			?>
    </div>
 </div>
<?php include("footer.php");?>