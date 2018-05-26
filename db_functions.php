<?php
function redirect_to($new_location){
	 header("location: " . $new_location);
	 exit;
	}

function start_session(){
	/*
	--start session OR
	--close session which start already
	*/
	session_start();
}	

 function logged_in(){
	 return isset($_SESSION["current_admin_id"]);
 }

 function user_logged_in(){
	 return isset($_SESSION["current_member_id"]);
 }
 
function confirm_logged_in(){
	 if (!logged_in()){
		 redirect_to("login.php");
	 }
 }

function user_confirm_logged_in(){
	 if (!user_logged_in()){
		 redirect_to("user_login.php");
	 }
 }
  
function page_errors($errors=array()){
		$output = "";
	   if (!empty($errors)){
		 $output .= "<div class=\"error\">";
		 $output .= "Please fix errors in:";
		 $output .= "<ul>";
		 foreach ($errors as $key => $error){
		  $output .= "<li>{$error}</li>";
		 }
		 $output .= "</ul>";
		 $output .= "</div>";
		} 
 return $output;
 }

function message(){
	if (isset($_SESSION["message"])){
		return $_SESSION["message"]; 
	}
	 return null;
}
 
function ispresent($value){
 return isset($value) && $value !== "";
}

function ismin($value, $min){
 return strlen($value) < $min;
}

function ismax($value, $max){
 return strlen($value) > $max;
}

function inset($value, $array){
 return in_array($value, $array);
}

function validate_ID_format($stdID){
global $errors;
 $stdID = trim($stdID);
 if(strlen($stdID) === 13){

    $intarray = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
	for($c=0;$c <= 12;$c++){	
		if (($c == 4) || ($c == 7 )){
			continue;
		}
		
		$char = $stdID["{$c}"];
	    $check = in_array("{$char}", $intarray, true);

		if(!$check){
			
		 $errors["inttype"] = "Please make sure numbers you type in are integer";	
		
		}
    }

	if ($stdID["4"] && $stdID["7"] !== "-"){
		$errors["student_id"] = "Please insert Student ID in \"YYYY-0N-NNNNN\" format";
	 }

 } else {
	$errors["chrlen"] = "Student ID must be 13 character in \"YYYY-0N-NNNNN\" format"; 
 }
}

function validate_email_format($email){
global $errors;
		/*
		 use a regular expression on a string
		 preg_match($regx, $subject)
		 e.g preg_match("/PHP/", "PHP is fun.")
		 BUT IT IS NOT THE FASTEST
		*/
			if (!preg_match("/@/", "{$email}")){
				$errors["email"] = "Please insert email in \"example@server.com\" format";
			 }
}

function validate_max_length($field_length){
	global $errors;
		foreach ($field_length as $field => $max){
			   $trimed_field = trim($_POST[$field]);
			   if (ismax($trimed_field, $max)){
				$errors[$field] = ucfirst($field) . " is too long";   
			   }
		}
 }
 
function validate_presence($field_presence){
	global $errors;
	   foreach ($field_presence as $field){
		   $key = trim($_POST[$field]);
	       if (!ispresent($key)){
		   	  $errors[$field] = str_replace("_", " ", ucfirst($field)) . " can't be blank";
		   }
		}
}

function attempting_login($username, $password){
	
   $admin = find_admins_by_username($username);
  if ($admin){
	   if(password_verify($password, $admin["hashed_password"])){
		   // match found 
		   //die("Database query failed: During password_verify. ");
		   return $admin;
	   } else {
		  //password not match
         return false;  
	   }
  } else {
	  //admin not found
	return false;  
  }  
}

function attempting_user_login($student_id, $password){

   $member = find_member_by_member_id($student_id);
  if ($member){
	   if(password_verify($password, $member["hashed_password"])){
		   // match found 
		   //die("Database query failed: During password_verify. ");
		   return $member;
	   } else {
		  //password not match
         return false;  
	   }
  } else {
	  //admin not found
	return false;  
  }  	
}

function find_all_admins(){
 global $connectparam;
	 $query = "SELECT *";
	$query .= " FROM admins";
	$query .= " ORDER BY username ASC";
	
	$admins_set = mysqli_query($connectparam, $query);
	if (!$admins_set){
	  die("Database query failed: During retrieving the admins:" . mysqli_error($connectparam));
    }
 return $admins_set;
}	
 
function find_all_communion(){
	global $connectparam;
	 $query = "SELECT *";
	$query .= " FROM communion";
	$query .= " ORDER BY position ASC";
	
	$communion_set = mysqli_query($connectparam, $query);
	if (!$communion_set){
	  die("Database query failed: During retrieving the communion:" . mysqli_error($connectparam));
    }
 return $communion_set;
}

function find_all_pages(){
	global $connectparam;
	 $query = "SELECT *";
	$query .= " FROM page";
	$query .= " ORDER BY position ASC";
	
	$page_set = mysqli_query($connectparam, $query);
	if (!$page_set){
	  die("Database query failed: During retrieving the page:" . mysqli_error($connectparam));
	}
return $page_set;
 }

function find_pages_for_a_communion($communion_id){
	global $connectparam;
	
	 $query = "SELECT *";
	$query .= " FROM page";
	$query .= " WHERE communion_id = {$communion_id}";
	$query .= " ORDER BY position ASC";
	
	$page_set = mysqli_query($connectparam, $query);
	if (!$page_set){
	  die("Database query failed: During retrieving the page:" . mysqli_error($connectparam));
	}
return $page_set;	
}

function find_admins_by_username($admin_username){
global $connectparam;
    $admin_username = mysqli_real_escape_string($connectparam, $admin_username);
     $query = "SELECT *";
	$query .= " FROM admins";
	$query .= " WHERE username = '{$admin_username}'";
	
	$returnadmin = mysqli_query($connectparam, $query);
	if (!$returnadmin){
	  die("Database query failed: During finding an admin." . mysqli_error($connectparam));
	}
	$admin = mysqli_fetch_assoc($returnadmin);
return $admin;	
}	

function find_member_by_member_id($student_id){
global $connectparam;
    $student_id = mysqli_real_escape_string($connectparam, $student_id);
     $query = "SELECT *";
	$query .= " FROM profiles";
	$query .= " WHERE member_id = '{$student_id}'";
	
	$returnmember = mysqli_query($connectparam, $query);
	if (!$returnmember){
		
	  die("Database query failed: During finding a member." . mysqli_error($connectparam));
	}
	$member = mysqli_fetch_assoc($returnmember);
return $member;	
}

function find_member_by_member_title($title){
global $connectparam;
    $title = mysqli_real_escape_string($connectparam, $title);
     $query = "SELECT *";
	$query .= " FROM profiles";
	$query .= " WHERE title = '{$title}'";
	
	$return_member = mysqli_query($connectparam, $query);
	if (!$return_member){
		
	  die("Database query failed: During finding a member by title." . mysqli_error($connectparam));
	}
return $return_member;		
}

function find_notification_by_sender_and_notification_id($sender_id, $notification_id){
global $connectparam;

    $sender_id = mysqli_real_escape_string($connectparam, $sender_id);

     $query = "SELECT *";
	$query .= " FROM notification";
    $query .= " WHERE sender_id = '{$sender_id}'";
	$query .= " AND id = '{$notification_id}'";
	
	$notifications = mysqli_query($connectparam, $query);
	if (!$notifications){
		
	  die("Database query failed: During finding a notifications." . mysqli_error($connectparam));
	}	
return $notifications;
}

function find_notifications_by_member_id($student_id){
global $connectparam;

    $student_id = mysqli_real_escape_string($connectparam, $student_id);
	$member = find_member_by_member_id($student_id);
	
     $query = "SELECT *";
	$query .= " FROM notification";
	
	$member["title"] = str_replace(" ", "_", $member["title"]);
	switch (strtolower($member["title"])){
	case "chairperson": $query .= " WHERE chairperson_id = '{$student_id}'"; break;
	case "general_secretary": $query .= " WHERE general_secretary_id = '{$student_id}'";break;
	case "excom": $query .= " WHERE excom_id = '{$student_id}'";break;
	}
	
	$query .= " ORDER BY id DESC";
	
	$notifications = mysqli_query($connectparam, $query);
	if (!$notifications){
		
	  die("Database query failed: During finding a notifications." . mysqli_error($connectparam));
	}	
return $notifications;	
}

function downloadlink(){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM store";
	$query .= " ORDER BY id DESC";
	
	$downloads = mysqli_query($connectparam, $query);
	if (!$downloads){
		
	  die("Database query failed: During downloading files." . mysqli_error($connectparam));
	}	
return $downloads;	
}

function downloadlink_finance(){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM finance";
	$query .= " ORDER BY id DESC";
	
	$downloads = mysqli_query($connectparam, $query);
	if (!$downloads){
		
	  die("Database query failed: During downloading files." . mysqli_error($connectparam));
	}	
return $downloads;	
}

function downloadlink_by_id($id){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM store";
	$query .= " WHERE id = {$id}";
	
	$links = mysqli_query($connectparam, $query);
	if (!$links){
		
	  die("Database query failed: During downloading links." . mysqli_error($connectparam));
	}
   $link = mysqli_fetch_assoc($links);
return $link;	
}

function downloadlink_finance_by_id($id){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM finance";
	$query .= " WHERE id = {$id}";
	
	$links = mysqli_query($connectparam, $query);
	if (!$links){
		
	  die("Database query failed: During downloading links." . mysqli_error($connectparam));
	}
   $link = mysqli_fetch_assoc($links);
return $link;	
}

function find_questions_by_member_id($student_id){
global $connectparam;

    $student_id = mysqli_real_escape_string($connectparam, $student_id);
	$member = find_member_by_member_id($student_id);
	
     $query = "SELECT *";
	$query .= " FROM ask_help";
	
	$member["title"] = str_replace(" ", "_", $member["title"]);
	switch (strtolower($member["title"])){
	case "chairperson": $query .= " WHERE chairperson_id = '{$student_id}'"; break;
	case "general_secretary": $query .= " WHERE general_secretary_id = '{$student_id}'";break;
	case "excom": $query .= " WHERE excom_id = '{$student_id}'";break;
	}
	
	$query .= " ORDER BY id DESC";
	
	$questions = mysqli_query($connectparam, $query);
	if (!$questions){
		
	  die("Database query failed: During finding Questions." . mysqli_error($connectparam));
	}	
return $questions;	
}

function find_all_posts(){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM post";
	$query .= " ORDER BY id DESC";
	
	$posts = mysqli_query($connectparam, $query);
	if (!$posts){
		
	  die("Database query failed: During finding posts." . mysqli_error($connectparam));
	}	
return $posts;	
}

function find_post_by_post_id($post_id){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM post";
	$query .= " WHERE id = {$post_id}";
	
	$posts = mysqli_query($connectparam, $query);
	if (!$posts){
		
	  die("Database query failed: During finding post by id." . mysqli_error($connectparam));
	}
    $post = mysqli_fetch_assoc($posts);	
return $post;	
}

function find_notifiction_by_notifiction_id($notifiction_id){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM notification";
	$query .= " WHERE id = {$notifiction_id}";
	
	$notifictions = mysqli_query($connectparam, $query);
	if (!$notifictions){
		
	  die("Database query failed: During finding notifictions by id." . mysqli_error($connectparam));
	}
    $notifiction = mysqli_fetch_assoc($notifictions);	
return $notifiction;	
}

function find_question_by_question_id($question_id){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM ask_help";
	$query .= " WHERE id = {$question_id}";
	
	$questions = mysqli_query($connectparam, $query);
	if (!$questions){
		
	  die("Database query failed: During finding questions by id." . mysqli_error($connectparam));
	}
    $question = mysqli_fetch_assoc($questions);	
return $question;	
}

function find_comment_by_comment_id($comment_id){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM comments";
	$query .= " WHERE id = {$comment_id}";
	
	$comments = mysqli_query($connectparam, $query);
	if (!$comments){
		
	  die("Database query failed: During finding comment by id." . mysqli_error($connectparam));
	}
    $comment = mysqli_fetch_assoc($comments);	
return $comment;	
}

function find_comments_for_a_post($post_id){
global $connectparam;

     $query = "SELECT *";
	$query .= " FROM comments";
	$query .= " WHERE post_id = {$post_id}";
	$query .= " ORDER BY id DESC";
	
	$comments = mysqli_query($connectparam, $query);
	if (!$comments){
		
	  die("Database query failed: During finding comments." . mysqli_error($connectparam));
	}	
return $comments;	
}

function find_admin_by_id($admin_id){
global $connectparam;
     $query = "SELECT *";
	$query .= " FROM admins";
	$query .= " WHERE id = {$admin_id}";
	
	$returnadmin = mysqli_query($connectparam, $query);
	if (!$returnadmin){
	  die("Database query failed: During finding an admin." . mysqli_error($connectparam));
	}
	$admin = mysqli_fetch_assoc($returnadmin);
return $admin;	
}

function find_communion_by_id($communion_id){
	global $connectparam;
	
	$communion_id = mysqli_real_escape_string($connectparam, $communion_id);
	
     $query = "SELECT *";
	$query .= " FROM communion";
	$query .= " WHERE id = {$communion_id}";
	
	$returncommunion = mysqli_query($connectparam, $query);
	if (!$returncommunion){
	  die("Database query failed: During finding a communion." . mysqli_error($connectparam));
	}
	$communion = mysqli_fetch_assoc($returncommunion);
return $communion;
}

function find_communion_by_name($communion_name){
	global $connectparam;
	
	$communion_name = mysqli_real_escape_string($connectparam, $communion_name);
	
     $query = "SELECT *";
	$query .= " FROM communion";
	$query .= " WHERE communion_name = '{$communion_name}'";
	
	$returncommunion = mysqli_query($connectparam, $query);
	if (!$returncommunion){
	  die("Database query failed: During finding a communion." . mysqli_error($connectparam));
	}
	$communion = mysqli_fetch_assoc($returncommunion);
return $communion;
}

function find_default_page_for_communion($communion_id){
	
  $page_set = find_pages_for_a_communion($communion_id);
 if ($first_page = mysqli_fetch_assoc($page_set)){
  return $first_page;	
 } else {
 return null;
 }
}

function find_page_by_id($page_id){
	global $connectparam;	
     $query = "SELECT *";
	$query .= " FROM page";
    $query .= " WHERE id = {$page_id}";

	$returnpage = mysqli_query($connectparam, $query);
	if (!$returnpage){
	  die("Database query failed: During finding a page." . mysqli_error($connectparam));
	}
 $page = mysqli_fetch_assoc($returnpage);
return $page;
}

function create_new_admin($new_admin_array){
 global $connectparam;
 
	$new_admin_array["username"] = mysqli_real_escape_string ($connectparam, $new_admin_array["username"]);
    
     $query = "INSERT INTO admins (";
	$query .= " username, hashed_password";
	$query .= " ) VALUES (";
	$query .= " '{$new_admin_array["username"]}', '{$new_admin_array["hashed_password"]}'";
	$query .= " )";
	$success = mysqli_query($connectparam, $query);
	if (!$success){
		die("Database query failed: During admin creation. " . mysqli_error($connectparam));
	} 
}

function create_new_post($new_post){
 global $connectparam;
 
	$new_post["content"] = mysqli_real_escape_string ($connectparam, $new_post["content"]);
    
     $query = "INSERT INTO post (";
	$query .= " sender_id, content";
	$query .= " ) VALUES (";
	$query .= " '{$new_post["sender_id"]}', '{$new_post["content"]}'";
	$query .= " )";
	$success = mysqli_query($connectparam, $query);
	if (!$success){
		die("Database query failed: During post creation. " . mysqli_error($connectparam));
	} 
}

function create_new_comment($new_comment){
 global $connectparam;

	$new_comment["content"] = mysqli_real_escape_string ($connectparam, $new_comment["content"]);
    
     $query = "INSERT INTO comments (";
	$query .= " post_id, sender_id, content";
	$query .= " ) VALUES (";
	$query .= " '{$new_comment["post_id"]}', '{$new_comment["sender_id"]}', '{$new_comment["content"]}'";
	$query .= " )";
	$success = mysqli_query($connectparam, $query);
	if (!$success){
		die("Database query failed: During comment creation. " . mysqli_error($connectparam));
	} 
}

function send_notification($new_notification){
 global $connectparam;

	$new_notification["content"] = mysqli_real_escape_string ($connectparam, $new_notification["content"]);
    
     $query = "INSERT INTO notification (";
	$query .= " sender_id, chairperson_id, general_secretary_id, excom_id, content";
	$query .= " ) VALUES (";
	$query .= " '{$new_notification["sender_id"]}', '{$new_notification["chairperson_id"]}', '{$new_notification["general_secretary_id"]}', '{$new_notification["excom_id"]}', '{$new_notification["content"]}'";
	$query .= " )";
	$success = mysqli_query($connectparam, $query);
	if (!$success){
		die("Database query failed: During notification creation. " . mysqli_error($connectparam));
	} 
}

function send_question($new_question){
 global $connectparam;

	$new_question["content"] = mysqli_real_escape_string ($connectparam, $new_question["content"]);
    
     $query = "INSERT INTO ask_help (";
	$query .= " sender_id, chairperson_id, general_secretary_id, excom_id, content";
	$query .= " ) VALUES (";
	$query .= " '{$new_question["sender_id"]}', '{$new_question["chairperson_id"]}', '{$new_question["general_secretary_id"]}', '{$new_question["excom_id"]}', '{$new_question["content"]}'";
	$query .= " )";
	$success = mysqli_query($connectparam, $query);
	if (!$success){
		die("Database query failed: During Questions creation. " . mysqli_error($connectparam));
	} 
}

function create_profile($profile){
 global $connectparam;
 
	$profile["member_id"] = mysqli_real_escape_string ($connectparam, $profile["member_id"]);
	$profile["first_name"] = mysqli_real_escape_string ($connectparam, $profile["first_name"]);
	$profile["middle_initial"] = mysqli_real_escape_string ($connectparam, $profile["middle_initial"]);
	$profile["last_name"] = mysqli_real_escape_string ($connectparam, $profile["last_name"]);
	$profile["title"] = mysqli_real_escape_string ($connectparam, $profile["title"]);
	$profile["programme"] = mysqli_real_escape_string ($connectparam, $profile["programme"]);
	$profile["year_of_study"] = mysqli_real_escape_string ($connectparam, $profile["year_of_study"]);
	$profile["email"] = mysqli_real_escape_string ($connectparam, $profile["email"]);
	$profile["phone"] = mysqli_real_escape_string ($connectparam, $profile["phone"]);
    
     $query = "INSERT INTO profiles (";
	$query .= " member_id, first_name, middle_initial, last_name, title, programme, year_of_study, email, phone, hashed_password";
	$query .= " ) VALUES (";
	$query .= " '{$profile["member_id"]}', '{$profile["first_name"]}',
            	'{$profile["middle_initial"]}', '{$profile["last_name"]}',
				'{$profile["title"]}', '{$profile["programme"]}', 
				'{$profile["year_of_study"]}', '{$profile["email"]}',
				'{$profile["phone"]}', '{$profile["hashed_password"]}'";
	$query .= " )";
	$success = mysqli_query($connectparam, $query);
	if (!$success){
		die("Database query failed: During profile creation. " . mysqli_error($connectparam));
	}else {$_SESSION["message"] = "Sign up successful";} 
}

function create_new_membership($communion_name, $position){
	global $connectparam;
	$communion_name = mysqli_real_escape_string($connectparam, $communion_name);
	
	 $query = "INSERT INTO communion (";
	$query .= " communion_name, position";
	$query .= " ) VALUES (";
	$query .= " '{$communion_name}', {$position}";
	$query .= " )";
	$returnquery = mysqli_query($connectparam, $query);
	if (!$returnquery){
		die("Database query failed: During creating the membership. " . mysqli_error($connectparam));
	} else {
		$_SESSION["message"] = "Membership Created";
		redirect_to("manage_content.php");
	}	 
}

function create_new_page($new_page_array){
    global $connectparam;	
     $new_page_array["page_name"] = mysqli_real_escape_string ($connectparam, $new_page_array["page_name"]);
	 
	 $query = "INSERT INTO page (";
	$query .= " communion_id, page_name, position, visible";
	$query .= " ) VALUES (";
	$query .= " {$new_page_array["communion_id"]}, '{$new_page_array["page_name"]}', {$new_page_array["position"]}, {$new_page_array["visible"]}";
	$query .= " )";
	$returnquery = mysqli_query($connectparam, $query);
	if (!$returnquery){
		die("Database query failed: During creating the membership. " . mysqli_error($connectparam));
	} else {
		$_SESSION["message"] = "Page added";
		redirect_to("manage_content.php");
	}  
}

function upload($new_upload){
global $connectparam;

    $new_upload["name"] = mysqli_real_escape_string ($connectparam, $new_upload["name"]);
    
     $query = "INSERT INTO store (";
	$query .= " sender_id, name, type, size";
	$query .= " ) VALUES (";
	$query .= " '{$new_upload["sender_id"]}', '{$new_upload["name"]}', '{$new_upload["type"]}', '{$new_upload["size"]}'";
	$query .= " )";
	$success = mysqli_query($connectparam, $query);
	if (!$success){
		die("Database query failed: During uploading file " . mysqli_error($connectparam));
	} 
}

function upload_finace($new_upload){
global $connectparam;

    $new_upload["name"] = mysqli_real_escape_string ($connectparam, $new_upload["name"]);
    
     $query = "INSERT INTO finance (";
	$query .= " sender_id, name, type, size";
	$query .= " ) VALUES (";
	$query .= " '{$new_upload["sender_id"]}', '{$new_upload["name"]}', '{$new_upload["type"]}', '{$new_upload["size"]}'";
	$query .= " )";
	$success = mysqli_query($connectparam, $query);
	if (!$success){
		die("Database query failed: During uploading file " . mysqli_error($connectparam));
	} 
}

function edit_profile($profile, $student_id){
global $connectparam;

		foreach ($profile as $field => $value){
			if ($field !== "hashed_password"){
			   $profile["{$field}"] = mysqli_real_escape_string($connectparam, $value);
			}
		} 
		
	    $query = "UPDATE profiles SET";
		$query .= " member_id = '{$profile["member_id"]}',";
		$query .= " first_name = '{$profile["first_name"]}',";
		$query .= " middle_initial = '{$profile["middle_initial"]}',";
		$query .= " last_name = '{$profile["last_name"]}',";
		$query .= " title = '{$profile["title"]}',";
		$query .= " programme = '{$profile["programme"]}',";
		$query .= " year_of_study = {$profile["year_of_study"]},";
		$query .= " email = '{$profile["email"]}',";
		$query .= " phone = '{$profile["phone"]}',";
		$query .= " hashed_password = '{$profile["member_id"]}'";
		$query .= " WHERE member_id = '{$student_id}'";
		$query .= " LIMIT 1";
		$returnquery = mysqli_query($connectparam, $query);
		if (!$returnquery){
	 		die("Database query failed: During editing profile. " . mysqli_error($connectparam));
		}
}	

function edit_admins($editing_array, $id){
 global $connectparam;
	 
	 $admin_name = $editing_array["username"];
	 $admin_name = mysqli_real_escape_string($connectparam, $admin_name);
	 $password = (int) $editing_array["password"];
	 
	    $query = "UPDATE admins SET";
		$query .= " id = {$id},";
		$query .= " username = '{$admin_name}',";
		$query .= " hashed_password = {$password}";
		$query .= " WHERE id = {$id}";
		$query .= " LIMIT 1";
		$returnquery = mysqli_query($connectparam, $query);
		if (!$returnquery){
	 		die("Database query failed: During editing. " . mysqli_error($connectparam));
		}
}	

function edit_post($new_post){
 global $connectparam;
	 
	 
	 $new_post["sender_id"] = mysqli_real_escape_string($connectparam, $new_post["sender_id"]);
	 $new_post["content"] = mysqli_real_escape_string($connectparam, $new_post["content"]);
	 
	 
	    $query = "UPDATE post SET";
		$query .= " id = {$new_post["id"]},";
		$query .= " sender_id = '{$new_post["sender_id"]}',";
		$query .= " content = '{$new_post["content"]}'";
		$query .= " WHERE id = {$new_post["id"]}";
		$query .= " LIMIT 1";
		
		$returnquery = mysqli_query($connectparam, $query);
		if (!$returnquery){
	 		die("Database query failed: During editing post. " . mysqli_error($connectparam));
		}
}

function edit_comment($new_comment){
 global $connectparam;
	 
	 
	 $new_comment["sender_id"] = mysqli_real_escape_string($connectparam, $new_comment["sender_id"]);
	 $new_comment["content"] = mysqli_real_escape_string($connectparam, $new_comment["content"]);
	 
	 
	    $query = "UPDATE comments SET";
		$query .= " id = {$new_comment["id"]},";
		$query .= " post_id = {$new_comment["post_id"]},";
		$query .= " sender_id = '{$new_comment["sender_id"]}',";
		$query .= " content = '{$new_comment["content"]}'";
		$query .= " WHERE id = {$new_comment["id"]}";
		$query .= " LIMIT 1";
		
		$returnquery = mysqli_query($connectparam, $query);
		if (!$returnquery){
	 		die("Database query failed: During editing comment. " . mysqli_error($connectparam));
		}
}

function edit_communion($editing_array, $id){
	 global $connectparam;
	 
	 $communion_name = $editing_array["communion_name"];
	 $communion_name = mysqli_real_escape_string($connectparam, $communion_name);
	 $position = (int) $editing_array["position"];

	    $query = "UPDATE communion SET";
		$query .= " id = {$id},";
		$query .= " communion_name = '{$communion_name}',";
		$query .= " position = {$position}";
		$query .= " WHERE id = {$id}";
		$query .= " LIMIT 1";
		$returnquery = mysqli_query($connectparam, $query);
		if ($returnquery){
			$_SESSION["message"] = "Membership Edited";	
	    	redirect_to("manage_content.php");
        } else {
			die("Database query failed: During editing. " . mysqli_error($connectparam));
		}
}

function edit_page($editing_array, $id){
global $connectparam;
  
	 $communion_id = (int)$editing_array["communion_id"]; 
	 $page_name = $editing_array["page_name"];
	 $page_name = mysqli_real_escape_string($connectparam, $page_name);
	 $position = (int) $editing_array["position"];
	 $visibility = (int) $editing_array["visible"];
	 
     $query = "UPDATE page SET ";
	$query .= "communion_id = {$communion_id}, ";
	$query .= "page_name = '{$page_name}', ";
	$query .= "position = {$position}, ";
	$query .= "visible = {$visibility} ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1";
	
	$returnquery = mysqli_query($connectparam, $query);
	if ($returnquery){
	  $_SESSION["message"] = "Page Edited";
	  redirect_to("manage_content.php");
	} else {
		die("Database query failed: During page updation." . mysqli_error($connectparam));
	}	 
}

function delete_admin($id){
	
	global $connectparam;
	 $query = "DELETE FROM admins";
	$query .= " WHERE id = {$id}";
	$query .= " LIMIT 1";
	$returnquery = mysqli_query($connectparam, $query);
	if ($returnquery){
		redirect_to("manage_admins.php");		
	} else {
		die("Database query failed: During deleting admin. " . mysqli_error($connectparam));
	}
}

function delete_post($post_id){
global $connectparam;
	
	$query = "DELETE FROM post";
	$query .= " WHERE id = {$post_id}";
	$query .= " LIMIT 1";
	
	$returnquery = mysqli_query($connectparam, $query);
	if (!$returnquery){
	  die("Database query failed: During deleting post. " . mysqli_error($connectparam));
	}
}

function delete_notification($notification_id){
global $connectparam;
	
	$query = "DELETE FROM notification";
	$query .= " WHERE id = {$notification_id}";
	$query .= " LIMIT 1";
	
	$returnquery = mysqli_query($connectparam, $query);
	if (!$returnquery){
	  die("Database query failed: During deleting notification. " . mysqli_error($connectparam));
	}
}

function delete_question($question_id){
global $connectparam;
	
	$query = "DELETE FROM ask_help";
	$query .= " WHERE id = {$question_id}";
	$query .= " LIMIT 1";
	
	$returnquery = mysqli_query($connectparam, $query);
	if (!$returnquery){
	  die("Database query failed: During deleting question. " . mysqli_error($connectparam));
	}
}

function delete_comment($comment_id){
global $connectparam;
	
	$query = "DELETE FROM comments";
	$query .= " WHERE id = {$comment_id}";
	$query .= " LIMIT 1";
	
	$returnquery = mysqli_query($connectparam, $query);
	if (!$returnquery){
	  die("Database query failed: During deleting comment. " . mysqli_error($connectparam));
	}
}

function delete_communion($id){
	    global $connectparam;
	     $query = "DELETE FROM communion";
		$query .= " WHERE id = {$id}";
		$query .= " LIMIT 1";
		$returnquery = mysqli_query($connectparam, $query);
		if ($returnquery){
			$_SESSION["message"] = "Membership Deleted";
			redirect_to("manage_content.php");		
		} else {
			die("Database query failed: During editing. " . mysqli_error($connectparam));
		}
}

function delete_page($id){
	    global $connectparam;
	     $query = "DELETE FROM page";
		$query .= " WHERE id = {$id}";
		$query .= " LIMIT 1";
		$returnquery = mysqli_query($connectparam, $query);
		if ($returnquery){
			$_SESSION["message"] = "Page Deleted";
			redirect_to("manage_content.php");		
		} else {
			die("Database query failed: During editing. " . mysqli_error($connectparam));
		}
}

function public_navigation($communion_array, $page_array){	
    // make selected page or communion bold 
    global $connectparam;

	 $concat = "<ul class=\"subjects\">";
	 $communion_set = find_all_communion();
	 while($communion = mysqli_fetch_assoc($communion_set)){
		 
     $_SESSION['communion'] = strtolower($communion['communion_name']);
	 
	 $ExCom = substr($_SESSION['title'], 0, 5);
    if($_SESSION['title'] == $_SESSION['communion'] || $ExCom == $_SESSION['communion']){
	 $concat .= "<li";
	 // make  membership and its page bold
	   if ($communion_array && $communion["id"] == $communion_array["id"]){
		 $concat .= " class=\"selected\"";  
	   }
	 $concat .= ">";
	 $concat .= "<a href=\"profiles.php?communion=";
	 $concat .= urlencode($communion["id"]);
	 $concat .= "&member=";
	 
	 if($_SESSION['title'] == $_SESSION['communion'] || $ExCom == $_SESSION['communion']){
	  $concat .= urlencode($_SESSION['current_member_id']);
	 }
	 
	 $concat .= "\">";
	 $concat .= ucwords($communion["communion_name"]);
	 $concat .= "</a>";
	// make selected page bold 
	if($communion["id"] == $communion_array["id"] || $communion["id"] == $page_array["communion_id"]){
    		
	 $page_set = find_pages_for_a_communion($communion["id"]);
	 $concat .= "<ul class=\"pages\">";
	 while($page = mysqli_fetch_assoc($page_set)){
		$finance = false;
	 $current_member = find_member_by_member_id($_SESSION['current_member_id']);	 
	 if($current_member["title"] == "Chairperson" || $current_member["title"] == "General Secretary" || $current_member["title"] == "ExCom Finance"){
	 $finance = true;
	 }	
	 if ($page["page_name"] == "finance"){
	 if ($finance){
	 $concat .= "<li";
	  
	   if ($page_array && $page["id"] == $page_array["id"]){
		 $concat .= " class=\"selected\"";  
	  }
	  
	 $concat .= ">";		 
	 }
	 } else { 
	 $concat .= "<li";
	  
	   if ($page_array && $page["id"] == $page_array["id"]){
		 $concat .= " class=\"selected\"";  
	  }
	  
	 $concat .= ">";
	 }
	$page["page_name"] = str_replace(" ", "_", $page["page_name"]);
	 
	switch ($page["page_name"]){
		 
	case "profiles": $concat .= "<a href=\"profiles.php?member=";
	
                	if($_SESSION['title'] == $_SESSION['communion'] || $ExCom == $_SESSION['communion']){
		              $concat .= urlencode($_SESSION['current_member_id']);
					 }
					 
	                $concat .= "&page=";
					$concat .= urlencode($page["id"]);
	 break;
	 case "notifications": $concat .= "<a href=\"notifications.php?member=";
	
						if($_SESSION['title'] == $_SESSION['communion'] || $ExCom == $_SESSION['communion']){
						  $concat .= urlencode($_SESSION['current_member_id']);
						 }
						 
					     $concat .= "&page=";
					     $concat .= urlencode($page["id"]);
	 break;
	 case "posts": $concat .= "<a href=\"posts.php?member="; 
	
                	if($_SESSION['title'] == $_SESSION['communion'] || $ExCom == $_SESSION['communion']){
		              $concat .= urlencode($_SESSION['current_member_id']);
					}
					 
				    $concat .= "&page=";
				    $concat .= urlencode($page["id"]);
	 break;
	 case "stores": $concat .= "<a href=\"stores.php?member="; 
	
                	if($_SESSION['title'] == $_SESSION['communion'] || $ExCom == $_SESSION['communion']){
		              $concat .= urlencode($_SESSION['current_member_id']);
					}
					 
					$concat .= "&page=";
					$concat .= urlencode($page["id"]);
	 break;
	 case "finance":if($current_member["title"] == "Chairperson" || $current_member["title"] == "General Secretary" || $current_member["title"] == "ExCom Finance"){
					 $finance = true;
					 $concat .= "<a href=\"finance.php?member="; 
	
                	 if($_SESSION['title'] == $_SESSION['communion'] || $ExCom == $_SESSION['communion']){
		              $concat .= urlencode($_SESSION['current_member_id']);
					 }
					 
					 $concat .= "&page=";
					 $concat .= urlencode($page["id"]);
					}
	 break;
	 case "ask_help": $concat .= "<a href=\"ask_help.php?member="; 
	
                	if($_SESSION['title'] == $_SESSION['communion'] || $ExCom == $_SESSION['communion']){
		              $concat .= urlencode($_SESSION['current_member_id']);
					 }
					 
					$concat .= "&page=";
					$concat .= urlencode($page["id"]);
	 break;
	 }
	 if ($page["page_name"] == "finance"){
		 if ($finance){
		 $concat .= "\">";
		 $page["page_name"] = str_replace("_", " ", $page["page_name"]);
		 $concat .= ucfirst($page["page_name"]); 
		 $concat .= "</a></li>";
	     }
	   } else {
	 $concat .= "\">";
	 $page["page_name"] = str_replace("_", " ", $page["page_name"]);
	 $concat .= ucfirst($page["page_name"]); 
	 $concat .= "</a></li>";		   
	   }
	  }
	 $concat .= "</ul>";
	 mysqli_free_result($page_set);
	  }
	 $concat .= "</li>";
	 break;
      }
	}
	 
	 mysqli_free_result($communion_set);
	 $concat .= "</ul>";
return $concat; 		 	
}

function navigations($communion_array, $page_array){
    global $connectparam;
	
	 $concat = "<ul class=\"subjects\">";
	 $communion_set = find_all_communion();
	 while($communion = mysqli_fetch_assoc($communion_set)){
	 $concat .= "<li";
	   if ($communion_array && $communion["id"] == $communion_array["id"]){
		 $concat .= " class=\"selected\"";  
	   }
	 $concat .= ">";
	 $concat .= "<a href=\"manage_content.php?communion=";
	 $concat .= urlencode($communion["id"]);
	 $concat .= "\">";
	 $concat .= ucwords($communion["communion_name"]);
	 $concat .= "</a>";
	 
	if($communion["id"] == $communion_array["id"] || $page_array["communion_id"] == $communion["id"]){ 
	 $page_set = find_pages_for_a_communion($communion["id"]);
	 $concat .= "<ul class=\"pages\">";
	 while($page = mysqli_fetch_assoc($page_set)){
	  $concat .= "<li";
	   if ($page_array && $page["id"] == $page_array["id"]){
		 $concat .= " class=\"selected\"";  
	  }
	 $concat .= ">";
	 $concat .= "<a href=\"manage_content.php?page=";
	 $concat .= urlencode($page["id"]);
	 $concat .= "\">";
	 $concat .= ucfirst($page["page_name"]); 
	 $concat .= "</a></li>";
	   }
	 $concat .= "</ul>";
	 mysqli_free_result($page_set);
	  }
	 $concat .= "</li>";
	 }
	 mysqli_free_result($communion_set);
	 $concat .= "</ul>";
return $concat; 		 	
}

?>