<?php
	//session_start();
	
	require_once 'database_connection.php';
	
	if(ISSET($_POST['admin_login'])){
		if($_POST['admin_user_name'] != "" || $_POST['admin_password'] != ""){
			$username = $_POST['admin_user_name'];
			// md5 encrypted
			// $password = md5($_POST['password']);
			$password = $_POST['admin_password'];
			$sql = "SELECT * FROM main_admin WHERE admin_user_name=? AND `admin_password`=? ";
			$query = $connect->prepare($sql);
			$query->execute(array($username,$password));
			$row = $query->rowCount();
			$fetch = $query->fetch();
			if($row > 0) {
				$_SESSION['admin_user_name'] = $fetch['admin_id'];
				header("location: index.php");
			} else{
				echo "
				<script>alert('Invalid username or password')</script>
				<script>window.location = 'login.php'</script>
				";
			}
		}else{
			echo "
				<script>alert('Please complete the required field!')</script>
				<script>window.location = 'index.php'</script>
			";
		}
	}
?>