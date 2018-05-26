<?php 
    require_once("db_functions.php"); 
	include("db_connection.php");
	include("header.php");
?>
	
<?php 
   start_session();
   //confirm_logged_in();

	  if (isset($_POST["submit"])){
		  
	   $communion_id = ($_POST["communion_id"]);
	   $page_name = ($_POST["page_name"]);
	   $position = ($_POST["position"]);
	   $visibility = ($_POST["visible"]);
	   $new_page = array("communion_id" => $communion_id, "page_name" => $page_name, "position" => $position, "visible" => $visibility);
	   
	   create_new_page($new_page);
      }
    ?>
	   
	 <div id="main">
	 
	 <div id="navigation">	
     &nbsp;
     </div>
	
	 <div id="page">
			<h2>Admin Page: Create new page.</h2>
			<p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION['username']);?></p>
			
			<ul class="form">
			<form action="create_page.php" method="post">
			<table>
			    <tr>
				<td>Page Name:</td><td><input type="text" name="page_name" value="" /></td>
				</tr>
				<tr>
				<td>Communion Id:</td><td><input type="text" name="communion_id" value="" /></td>
				</tr>
				<tr>
				<td>Page Position:</td> 
					<td><select  name="position">
					<?php
					$rows = find_all_pages();
					$page_count = mysqli_num_rows($rows);
					 for ($count=1; $count <= ($page_count + 1); $count++){
					   echo "<option value=\"{$count}\">{$count}</option>";
					 }
					?>
					</select></td>
				</tr>
				<tr>
				<td>Visibility:</td><td><input type="radio" name="visible" value="1"/>Yes</td><td><input type="radio" name="visible" value="0"/>No</td>
				</tr>
				<tr>
				<td><input type="submit" name="submit" value="create" /></td>
				</tr>
				<tr>
				<td><a href="manage_content.php">Cancel</a></td>
				</tr>
			</table>
		   </form>
		   </ul>		   
	 </div>
   	</div>	
<div style="clear: both" ></div>		 
<?php include("footer.php");?>