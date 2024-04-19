 <?php
	 include 'database.php';
	
	// Function to delete all records
	function deleteAllRecords() {
		$pdo = Database::connect();
		$sql = 'DELETE FROM table_nodemcu_rfidrc522_mysql1';
		$pdo->query($sql);
		Database::disconnect();
		header("Location: List.php");
	}
	
	// Check if the AJAX request is made
	if(isset($_POST['delete_all'])) {
		deleteAllRecords();
		exit();
	}


/*require 'database.php';
$id = 0;
 
if ( !empty($_GET['id'])) {
	$id = $_REQUEST['id'];
}
 
if ( !empty($_POST)) {
	// keep track post values
	$id = $_POST['id'];
	 
	// delete data
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM table_nodemcu_rfidrc522_mysql1";
	$q = $pdo->prepare($sql);
	Database::disconnect();
	header("Location: List.php");
	 
}*/



	
?> 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link   href="css/bootstrap.min.css" rel="stylesheet">
<script src="js/bootstrap.min.js"></script>
<title>Delete : NodeMCU V1 ESP32 with MYSQL Database</title>
</head>
<img src="hinh2.png" alt="" style="width:50%;">
<body>
<h2 align="center">HỆ THỐNG QUẢN LÝ ĐIỂM DANH SINH VIÊN</h2>

<div class="container">
 
	<div class="span10 offset1">
		<div class="row">
			<h3 align="center">Delete User</h3>
		</div>

		<form class="form-horizontal" action="deleteAll.php" method="post">
			<!--<input type="hidden" name="id" value="<?php echo $name;?>"/>-->
			<p class="alert alert-error">Are you sure to delete ?</p>
			<div class="form-actions">
				<button type="submit" class="btn btn-danger">Yes</button>
				<a class="btn" href="List.php">No</a>
			</div>
		</form>
	</div>
			 
</div> <!-- /container -->
</body>
</html>