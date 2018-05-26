<?php 
    require_once("db_functions.php"); 
	include("db_connection.php");
	include("header.php");
?>
	
<?php
   start_session();
  // confirm_logged_in();
   
	  if (isset($_POST["submit"])){
		  
	   $communion_name = ($_POST["communion_name"]);
	   $communion_name = mysqli_real_escape_string ($connectparam, $communion_name);
	   $position = (int)($_POST["position"]);
	   
	   create_new_membership($communion_name, $position);
      }
    ?>
	   
	 <div id="main">
	 <div id="navigation">	
	 &nbsp;
     </div>
	
	 <div id="page">
			<h2>Admin Page: Create new membership.</h2>
			<p style="text-align: right; margin-top: 2em; border-top: 1px solid #000000;" >Login as: <?php echo ucwords($_SESSION['username']);?></p>
			
			<ul class="form">
			<form action="create_communion.php" method="post">
			<table>
			    <tr>
				<td>Membership Name:</td><td><input type="text" name="communion_name" value="" /></td>
				</tr>
				<tr>
				<td>Membership Position:</td> 
					<td><select  name="position">
					<?php
					$rows = find_all_communion();
					$communion_count = mysqli_num_rows($rows);
					 for ($count=1; $count <= ($communion_count + 1); $count++){
					   echo "<option value=\"{$count}\">{$count}</option>";
					 }
					?>
					</select></td>
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
     <br />		 
	 </div>		   
<?php include("footer.php");?>