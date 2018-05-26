<?php 
    require_once("db_functions.php"); 
	include("db_connection.php");
?>
	
<?php
   start_session();
   $errors = array();
   $message = null;
   $_SESSION["current_member_id"] = null;
   $_SESSION["communion_id"] = null;
   if (isset($_POST["submit"])){
	   
  
	   	$fail = array("member_id" => $_POST["member_id"],
			              "first_name" => $_POST["first_name"], 
						  "middle_initial" => $_POST["middle_initial"],
						  "last_name" => $_POST["last_name"],
						  "programme" => $_POST["programme"],
						  "year_of_study" => $_POST["year_of_study"],
						  "email" => $_POST["email"],
						  "phone" => $_POST["phone"]
						  );
	   if ($_POST["password"] === $_POST["confirm_password"]){
		   
		$fields = array("member_id", "first_name");
		validate_presence($fields);
		
	   $fields = array("last_name", "password");
		validate_presence($fields);

		$fields = array("programme", "year_of_study");
		validate_presence($fields);
		
		if (isset($_POST["email"]) && $_POST["email"] !== "" ){
			validate_email_format($_POST["email"]);
		}

	       if ($_POST["title"] == "Select membership"){
		   	$errors["title"] = "Please select membership, it can't be blank";   
		   }
		
		$fields_length = array("member_id" => 13, "password" => 15);
		validate_max_length($fields_length);
        validate_ID_format($_POST["member_id"]);		
		  
		if (empty($errors)){
			
		 $chairperson = find_member_by_member_title($_POST["title"]);
		 $chair_person = mysqli_fetch_assoc($chairperson);
		 if ($chair_person["title"] !== "Chairperson" ){
			 
		 $Gsec = find_member_by_member_title($_POST["title"]);
		 $Gs = mysqli_fetch_assoc($Gsec);
		 
		 if ($Gs["title"] !== "General Secretary" ){
			 
		 $ExCom = find_member_by_member_title($_POST["title"]);
		 $Ex_Com = mysqli_fetch_assoc($ExCom);

		 if ($Ex_Com["title"] !== "ExCom Academic" && $Ex_Com["title"] !== "ExCom Finance" && $Ex_Com["title"] !== "ExCom Sports"){
			 
	     $password = trim($_POST["password"]);
         $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
			
			$profile = array("member_id" => $_POST["member_id"],
			                 "first_name" => $_POST["first_name"], 
							 "middle_initial" => $_POST["middle_initial"],
							 "last_name" => $_POST["last_name"],
							 "title" => $_POST["title"],
							 "programme" => $_POST["programme"],
							 "year_of_study" => $_POST["year_of_study"],
							 "email" => $_POST["email"],
							 "phone" => $_POST["phone"],
							 "hashed_password" => $hashed_password
							 );
			$_SESSION["current_member_id"] = $profile["member_id"];
			
			 $_SESSION["current_member_name"] = $profile["first_name"] ." ";
			 if (isset($profile["middle_initial"])){$_SESSION["current_member_name"] .= $profile["middle_initial"] ." ";}
			 $_SESSION["current_member_name"] .= $profile["last_name"];
			 
			$_SESSION["title"] = strtolower($profile["title"]);
			
			if($_SESSION["title"] == "chairperson"){ $com = find_communion_by_name("Chairperson"); $_SESSION["communion_id"] = $com["id"];}
			if($_SESSION["title"] == "general secretary"){ $com= find_communion_by_name("General Secretary"); $_SESSION["communion_id"] =$com["id"];}
			if(substr($_SESSION["title"], 0, 5) == "excom"){ $com= find_communion_by_name("ExCom"); $_SESSION["communion_id"] =$com["id"];}
			if($_SESSION["title"] == "student member"){ $com= find_communion_by_name("student member"); $_SESSION["communion_id"] =$com["id"];}
			
			create_profile($profile);
			
		   } else {$errors["title"] = "{$_POST["title"]} already exist!";} 
          } else {$errors["title"] = "{$_POST["title"]} already exist!";}		   
		 } else {$errors["title"] = "{$_POST["title"]} already exist!";}  
		}
	   } else {
		 $errors["message"] = "password does not match"; 
	   }		
	} else {
		$fail = array();
		$fail = null;
	}
?>

<?php include("header.php");?>	   
	 <div id="main">
	 
	 <div  id="navigation">
       <p>&laquo; <a href="user_page.php?communion=<?php echo urlencode($_SESSION["communion_id"]);?>">Home</a></p>	 
       &nbsp;
     </div>
	
	 <div id="page">
	 
		<?php if (isset($_SESSION["message"]) || !empty($errors)){ ?>
         <div class = "message">
		    <?php
			 echo page_errors($errors);
			 if (isset($_SESSION["message"])){
			  echo $_SESSION["message"];
			  $_SESSION["message"] = null;
			 }
			?>
         </div>
        <?php } ?>		   
			
	<h2>Sign up:</h2>
			<ul class="form">
			<form action="sign_up.php" method="post">
			 <table>
				<tr>
				<td>Student ID:</td>
				<td><input style="width: 90%;" type="text" name="member_id" value="<?php echo htmlentities($fail["member_id"]);?>" /></td>
				</tr>
				<tr>
				<td>First Name:</td>
				<td><input style="width: 90%;" type="text" name="first_name" value="<?php echo htmlentities($fail["first_name"]);?>" /></td>
				</tr>
				<tr>
				<td>Middle Initial:</td>
				<td><input style="width: 90%;" type="text" name="middle_initial" value="<?php echo htmlentities($fail["middle_initial"]);?>" /></td>
				</tr>
				<tr>
				<td>Last Name:</td>
				<td><input style="width: 90%;" type="text" name="last_name" value="<?php echo htmlentities($fail["last_name"]);?>" /></td>
				</tr>
				<tr>
				<td>Membership:</td>
				  <td><select  name="title">
					<?php
					$m = array("Select membership", "Chairperson", "General Secretary", "ExCom Academic", "ExCom Finance", "ExCom Sports", "Student Member");
					 for ($count=0; $count <= 6; $count++){
						 $membership = $m["{$count}"];
					   echo "<option value=\"{$membership}\">{$membership}</option>";
					 }
					?>
					</select></td>
				<tr>
				<td>Programme:</td>
				<td><input style="width: 90%;" type="text" name="programme" value="<?php echo htmlentities($fail["programme"]);?>" /></td>
				</tr>
				<tr>
				<td>Year of study:</td>
				<td><input style="width: 90%;" type="text" name="year_of_study" value="<?php echo htmlentities($fail["year_of_study"]);?>" /></td>
				</tr>
				<tr>
				<td>Email:</td>
				<td><input style="width: 90%;" type="text" name="email" value="<?php echo htmlentities($fail["email"]);?>" /></td>
				</tr>
				<tr>
				<td>Phone:</td>
				<td><input style="width: 90%;" type="text" name="phone" value="<?php echo htmlentities($fail["phone"]);?>" /></td>
				</tr>
				<tr>
				<td>Password:</td>
				<td><input style="width: 90%;"  type="password" name="password" value=""/></td>
				</tr>
				<tr>
				<td>Confirm Password:</td>
				<td><input style="width: 90%;" type="password" name="confirm_password" value=""/></td>
				</tr> 
				<tr>
				<td><input type="submit" name="submit" value="create" /></td>
				</tr>
				<tr>
				<td><a href="user_page.php?communion=<?php echo urlencode($_SESSION["communion_id"]);?>&member=<?php echo urlencode($_SESSION["current_member_id"]);?>">Cancel</a></td><td><a href="edit_profile.php?edit=<?php echo urlencode($_SESSION["current_member_id"]);?>" style="color: green;">Edit</a></td>
				</tr>
			 </table>
			</form>	    
		   </ul>		   
	 </div>	 
	</div>		   
<?php include("footer.php");?>