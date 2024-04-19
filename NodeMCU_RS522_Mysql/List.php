<?php
	$Write="<?php $" . "UIDresult=''; " . "echo $" . "UIDresult;" . " ?>";
	file_put_contents('UIDContainer.php',$Write);
?>

<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="utf-8">
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
		<style>
		html {
			font-family: Arial;
			display: inline-block;
			margin: 0px auto;
			text-align: center;
		}

		ul.topnav {
			list-style-type: none;
			margin: auto;
			padding: 0;
			overflow: hidden;
			background-color: #4CAF50;
			width: 70%;
		}

		ul.topnav li {float: left;}

		ul.topnav li a {
			display: block;
			color: white;
			text-align: center;
			padding: 14px 16px;
			text-decoration: none;
		}

		ul.topnav li a:hover:not(.active) {background-color: #3e8e41;}

		ul.topnav li a.active {background-color: #333;}

		ul.topnav li.right {float: right;}

		@media screen and (max-width: 600px) {
			ul.topnav li.right, 
			ul.topnav li {float: none;}
		}
		
		.table {
			margin: auto;
			width: 90%; 
		}
		
		thead {
			color: #FFFFFF;
		}
		</style>
		
		<title>User Data : NodeMCU V1 ESP32 with MYSQL Database</title>
	</head>
	<img src="hinh2.png" alt="" style="width:50%;">
	<body>
		<h2>HỆ THỐNG QUẢN LÝ ĐIỂM DANH SINH VIÊN</h2>
		<ul class="topnav">
			<li><a href="home.php">Home</a></li>
			<li><a href="user data.php">Student List</a></li>
			<li><a href="registration.php">Registration</a></li>
			<li><a href="read tag.php">Read Tag ID</a></li>
			<li><a class="active" href="List.php">Attendance List </a></li>
		</ul>
		<br>
		<div class="container">
            <div class="row">
                <h3>Attendance Datasheet</h3>
            </div>
			<button id="delete--btn" style="font-size: 16px; background-color: #ff0000; color: #ffffff; margin-right: -20cm;border: 1px solid #ff0000;">Delete</button>
			
            <div class="row table-content">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr  style="background-color:#10a0c5;  color=#FFFFFF"> 
                      <th>Name</th>
                      <th>ID</th>
					  <th>Gender</th>
					  <th>Email</th>
                      <th>MSSV</th>
                      <th>TIME</th>
				
                    </tr>
                  </thead>
                  <tbody id="raw-data">
                  <?php
                   include 'database.php';
                   $pdo = Database::connect();
                   $sql = 'SELECT * FROM table_nodemcu_rfidrc522_mysql1 ORDER BY name ASC';
                   foreach ($pdo->query($sql) as $row) {
                            echo '<tr>';
                            echo '<td>'. $row['name'] . '</td>';
                            echo '<td>'. $row['id'] . '</td>';
                            echo '<td>'. $row['gender'] . '</td>';
							echo '<td>'. $row['email'] . '</td>';
							echo '<td>'. $row['mobile'] . '</td>';
							echo '<td>'. $row['time'] . '</td>';
							
                   }
                   Database::disconnect();
                  ?>
                  </tbody>
				</table>
                
                
                
			</div>
		</div>
		
			</div>
		</div> <!-- /container -->
	</div> <!-- /container -->
</div>
		</div> <!-- /container -->
		
	</body>
	<script>
        const deleteHandle = () => {
            var tableContent = document.querySelector("#raw-data");
            if (tableContent) {
                tableContent.style.display = 'none'; // Ẩn nội dung bảng
            }
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            const deleteButton = document.querySelector("#delete--btn");
            if(deleteButton) {
                deleteButton.addEventListener("click", deleteHandle);
            }
        });
    </script>
</html>
