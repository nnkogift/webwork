<?php require_once("db_functions.php"); ?>
<?php
//creating database connection
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_USER_PASS", "!ryio_18");
	$connectparam = mysqli_connect(DB_HOST, DB_USER, DB_USER_PASS);

	 if (mysqli_connect_error()){
		 die ("Database connection failed: " . mysqli_connect_error() . "(" .mysqli_connect_errno() . ")" );	 
	 }

	$dbname = "daruso_coict";
    $query = "CREATE DATABASE IF NOT EXISTS {$dbname} ";
	$db = mysqli_query($connectparam, $query);
	if (!$db){
		
	$message[] = "Database {$dbname} : Creation failed ". mysqli_error($connectparam);
	}

   		define("HOST", "localhost");
		define("USER", "root");
		define("USER_PASS", "!ryio_18");
		define("DBS_NAME", $dbname );
		$connectparam = mysqli_connect(HOST, USER, USER_PASS, DBS_NAME);

		 if (mysqli_connect_error()){
			 die ("Database connection failed: " . mysqli_connect_error() . "(" .mysqli_connect_errno() . ")" );	 
		 }

$admins = "CREATE TABLE IF NOT EXISTS admins (
 id INT(10) NOT NULL AUTO_INCREMENT,
 username VARCHAR(50) NOT NULL,
 hashed_password VARCHAR(255) NOT NULL,
 PRIMARY KEY (id)
)";

$communion = "CREATE TABLE IF NOT EXISTS  communion (
 id INT(10) NOT NULL AUTO_INCREMENT,
 communion_name VARCHAR(50) NOT NULL,
 position INT(3) NOT NULL,
 PRIMARY KEY (id)
)";
 
$page = "CREATE TABLE IF NOT EXISTS  page (
 id INT(10) NOT NULL AUTO_INCREMENT,
 communion_id INT(10) NOT NULL,
 page_name VARCHAR(50) NOT NULL,
 position INT(3) NOT NULL,
 visible TINYINT(1) NULL,
 PRIMARY KEY (id)
)";

$profiles = "CREATE TABLE IF NOT EXISTS profiles (
 member_id VARCHAR(20) NOT NULL,
 first_name VARCHAR(30) NOT NULL,
 middle_initial VARCHAR(3) NOT NULL,
 last_name VARCHAR(30) NOT NULL,
 title VARCHAR(50) NOT NULL,
 programme VARCHAR(255) NOT NULL,
 year_of_study INT(1) NOT NULL,
 email VARCHAR(255) NOT NULL,
 phone VARCHAR(15) NOT NULL,
 hashed_password VARCHAR(255) NOT NULL,
 PRIMARY KEY (member_id)
)";

$notification = "CREATE TABLE IF NOT EXISTS notification (
 id INT(10) NOT NULL AUTO_INCREMENT,
 sender_id VARCHAR(20) NOT NULL,
 chairperson_id VARCHAR(20) NULL,
 general_secretary_id VARCHAR(20) NULL,
 excom_id VARCHAR(20) NULL,
 content TEXT NOT NULL,
 PRIMARY KEY (id)
)";

$post = "CREATE TABLE IF NOT EXISTS post (
 id INT(10) NOT NULL AUTO_INCREMENT,
 sender_id VARCHAR(20) NOT NULL,
 content TEXT NOT NULL,
 PRIMARY KEY (id)
)";

$comments = "CREATE TABLE IF NOT EXISTS comments (
 id INT(10) NOT NULL AUTO_INCREMENT,
 post_id INT(10) NOT NULL,
 sender_id VARCHAR(20) NOT NULL,
 content TEXT NOT NULL,
 PRIMARY KEY (id)
)";

$store = "CREATE TABLE IF NOT EXISTS store (
 id INT(10) NOT NULL AUTO_INCREMENT,
 sender_id VARCHAR(20) NOT NULL,
 name VARCHAR(200) NOT NULL,
 type VARCHAR(225) NOT NULL,
 size INT(10) NOT NULL,
 PRIMARY KEY (id)
)";

$finance = "CREATE TABLE IF NOT EXISTS finance (
 id INT(10) NOT NULL AUTO_INCREMENT,
 sender_id VARCHAR(20) NOT NULL,
 name VARCHAR(200) NOT NULL,
 type VARCHAR(255) NOT NULL,
 size INT(10) NOT NULL,
 PRIMARY KEY (id)
)";

$ask_help = "CREATE TABLE IF NOT EXISTS ask_help (
 id INT(10) NOT NULL AUTO_INCREMENT,
 sender_id VARCHAR(20) NOT NULL,
 chairperson_id VARCHAR(20) NULL,
 general_secretary_id VARCHAR(20) NULL,
 excom_id VARCHAR(20) NULL,
 content TEXT NOT NULL,
 PRIMARY KEY (id)
)";
 
 $db_tables = [$admins, $communion, $page, $profiles, $notification, $post, $comments, $store, $finance, $ask_help];
 foreach ($db_tables as $key => $query ){
	$dbt = mysqli_query($connectparam, $query);
	if (!$dbt){
	 $message[] = "Table {$key} : Creation failed ". mysqli_error($connectparam);
	}
 }
 
?>
<?php include("header.php");?>
	 <div id="main">
	 
	 <div  id="navigation">
	 <p>&laquo; <a href="user_page.php">Home</a></p>
       &nbsp;
     </div>
	
	 <div id="page">
	 	<?php if (!empty($message)){ ?>
         <div class = "message">
		    <?php
			foreach ($message as $msg){
			 echo "{$msg} <br />";
            }
			?>
         </div>
        <?php } else { redirect_to("user_page.php");} ?>
	 </div>	 
	</div>
<?php include("footer.php");?>