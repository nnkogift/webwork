<?php 
    require_once("db_functions.php"); 
	include("db_connection.php");
	include("header.php");
?>	

<?php
   start_session();
   $errors = array();
   $student_id = "";
   
   if (isset($_POST["submit"])){
	   
	   $student_id = mysqli_real_escape_string($connectparam, $_POST["member_id"]);
	   $password = mysqli_real_escape_string($connectparam, $_POST["password"]);
	   
		$field_presence = array("member_id", "password");
		validate_presence($field_presence);
		
	 if (empty($errors)){
		 
	   $student_id = mysqli_real_escape_string($connectparam, $_POST["member_id"]);
	   $password = mysqli_real_escape_string($connectparam, $_POST["password"]);		 
	
		   $found_profile = attempting_user_login($student_id, $password);			
		   if($found_profile){
			 //Successful login.
			 //set status as login
			 
			 $_SESSION["current_member_id"] = $found_profile["member_id"];
			 
			 $_SESSION["current_member_name"] = $found_profile["first_name"] ." ";
			 if (isset($found_profile["middle_initial"])){$_SESSION["current_member_name"] .= $found_profile["middle_initial"] ." ";}
			 $_SESSION["current_member_name"] .= $found_profile["last_name"];
			 
			 $_SESSION['title'] = strtolower($found_profile["title"]);
			 
			if($_SESSION["title"] == "chairperson"){ $com = find_communion_by_name("Chairperson"); $_SESSION["communion_id"] = $com["id"];}
			if($_SESSION["title"] == "general secretary"){ $com= find_communion_by_name("General Secretary"); $_SESSION["communion_id"] =$com["id"];}
			if(substr($_SESSION["title"], 0, 5) == "excom"){ $com= find_communion_by_name("ExCom"); $_SESSION["communion_id"] =$com["id"];}
			if($_SESSION["title"] == "student member"){ $com= find_communion_by_name("student member"); $_SESSION["communion_id"] =$com["id"];}	
			
			 redirect_to("profiles.php?communion={$_SESSION["communion_id"]}&member={$_SESSION["current_member_id"]}");
			 
		   } else {
			  $_SESSION["message"] = "Username/Password not found";
		   }
		 }
   }
?>
		<div id="main">
		 <div id="navigation">
		 <p>&laquo; <a href="user_page.php">Home</a></p>
         &nbsp;	 
		 </div>
		
		 <div id="page">
        
		<?php if (isset($_SESSION["message"]) || !empty($errors)){ ?>
         <div class = "message">
		   <?php 
			 echo message();
			 $_SESSION["message"] = null;
             echo page_errors($errors);
			?>
         </div>
        <?php } ?>		   
			<p>Please Login.</p>
	        <form action="user_login.php" method="post">
			
			<table>
				<tr>
				<td>Student ID:</td>
				<td><input type="text" name="member_id" value="<?php echo htmlentities($student_id);?>" /></td>
				</tr>
				<tr>
				<td>Password:</td>
				<td><input  type="password" name="password" value=""/></td>
				</tr>
				<tr>
				<td><input style="color:red;" type="submit" name="submit" value="login" /></td>
				</tr>
		    </table>
			
		    </form>
		 </div> 
	    </div>
<?php include("footer.php");?>