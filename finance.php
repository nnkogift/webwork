<?php include("db_connection.php"); ?>
<?php require_once("db_functions.php"); ?>
<?php include("header.php");?>

<?php
   start_session();
   user_confirm_logged_in();
 
    if(isset($_POST['upload']) && $_FILES['userfile']['size'] > 0){

	$fileName = basename($_FILES['userfile']['name']);
    
	$tmpName = $_FILES['userfile']['tmp_name'];
	$intodir = "c:/wamp64/tmp/";
	
    if(move_uploaded_file($tmpName, $intodir . $fileName)){
	  $_SESSION["message"] = "File $fileName uploaded";
	}
	
	$fileSize = $_FILES['userfile']['size'];

	$fileType = $_FILES['userfile']['type'];

  	$new_upload = array("sender_id" =>"{$_SESSION["member"]}", 
	                    "name" =>"{$fileName}", 
	                    "size" =>"{$fileSize}", 
						"type" =>"{$fileType}");
						
   upload_finace($new_upload);
   $_SESSION["message"] = "File $fileName uploaded";

 }elseif(isset($_GET["member"])){
        if ($_SESSION["current_member_id"] !== $_GET["member"]){

			$denied = "Access denied!";
		} else {$_SESSION["member"] = $_GET["member"];}
	}else{
       $denied = "Access denied!";
	    }
		
	if (isset($_GET["page"]) && isset($_GET["member"]) && isset($_GET['file']) && isset($_GET['id']) ){

		if (isset($_GET['file']) && basename($_GET['file']) == $_GET['file']) {
		$filename = $_GET['file'];
		} else {
		$filename = NULL;
		// define error message
		$err = 'Sorry, the file does not exist.';
		}

		if (!$filename) {
		// if variable $filename is NULL or false display the message
		echo $err;
		} else {
			// define the path to your download folder plus assign the file name
			$path = 'c:/wamp64/tmp/'.$filename;
			// check that file exists and is readable
			if (file_exists($path) && is_readable($path)) {
			// get the file size and send the http headers
			$size = filesize($path);
			$id = $_GET['id'];
	        $link = downloadlink_finance_by_id($id); 
			$type = $link["type"]; 
			
			header('Content-Type: '. $type);
			header('Content-Length: '.$size);
			header('Content-Disposition: attachment; filename='.$filename);
			header('Content-Transfer-Encoding: binary');
			// open the file in binary read-only mode
			// display the error messages if the file can´t be opened

			$file = fopen($path, 'r');
			if ($file) {
			// stream the file and exit the script when complete
			readfile($file);
			exit;
			} else {
			echo $err;
			}
			} else {
			echo $err;
			}
}
	 
     $selected_communion = null;
	 $_SESSION["current_page"] = $_GET["page"];
	 $selected_page = find_page_by_id($_SESSION["current_page"]);
	 
    } elseif (isset($_GET["page"]) && isset($_GET["member"])) {
		
	 $_SESSION["current_page"] = $_GET["page"];
	 $selected_page = find_page_by_id($_SESSION["current_page"]);
	 $selected_communion = null;
	 
	}else{

	 $_GET["member"] = null;
     $selected_page = null;
	 $selected_communion = null;
	 
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
   <h2>Finance</h2>
   	<p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION["current_member_name"]);?></p> 	
		<?php if (isset($_SESSION["message"])){ ?>
         <div class = "message">
		    <?php

			 echo htmlentities($_SESSION["message"]);
			 $_SESSION["message"] = null;
			?>
         </div>
        <?php } ?>
  <p>Upload file here.</p>
	<form method="post" enctype="multipart/form-data">
		<table width="350" border="0" cellspacing="1" cellpadding="1">
		<tbody>
		<tr>
		<td width="246">
		<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />

		<input id="userfile" type="file" name="userfile" /></td>
		<td width="80"><input id="upload" type="submit" name="upload" value=" Upload " /></td>
		</tr>
		</tbody>
		</table>
	</form>
<hr>
<?php
  $downloads = downloadlink_finance();
  if(mysqli_num_rows($downloads) !== 0){ 
?>
 <p>Click a link on the list to download the file here.</p>
 <p style="padding-left: 3em;">Sender &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; Downloads</p>
 <ul>
<?php
    while($download = mysqli_fetch_assoc($downloads)){

		 $member = find_member_by_member_id($download["sender_id"]);
	     $name = $member["first_name"] ." " . $member["middle_initial"] . " " . $member["last_name"];
?>
     <?php echo ucwords(htmlentities($name));?>&nbsp;<a href="finance.php?member=<?php echo urlencode($_SESSION["current_member_id"]);?>&page=<?php echo urlencode($_SESSION["current_page"]);?>&file=<?php echo urlencode($download["name"]);?>&id=<?php echo urlencode($download["id"]);?>"><?php echo $download["name"]; ?></a>
   <?php 
	echo "<br />"; }
	?>
  </ul>
	<?php
    } else {echo "Database is empty";} ?>

<p><a href="profiles.php?page=<?php echo urlencode($_SESSION["page"]);?>&member=<?php echo urlencode($_SESSION["current_member_id"]);?>">Cancel</a></p>
<?php } elseif($denied !== null) { echo htmlentities($denied); ?>
<?php } else {?>
    <p>Please Select page from the navigation.</p>
<?php } ?>
		</div>
 </div>	
<?php include("footer.php");?>