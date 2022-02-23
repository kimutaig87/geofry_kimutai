 <?php  
 
 session_start();  
 $host = "localhost";  
 $username = "root";  
 $password = "";  
 $database = "attendance";  
 $error_password = ""; 
 $error_username = ''; 
 try  
 {  
      $connect = new PDO("mysql:host=$host; dbname=$database", $username, $password);  
      $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
      if(isset($_POST["login"]))  
      {  
           if(empty($_POST["admin_user_name"]))  
           {  
                $error_username = 'Username is required';  
           } 
           if(empty($_POST["admin_password"])){
                $error_password = 'Password is required';
           } 
      else  
     {  
                $query = "SELECT * FROM main_admin WHERE admin_user_name = :admin_user_name AND admin_password = :admin_password";  
                $statement = $connect->prepare($query);  
                $statement->execute(  
                     array(  
                          'admin_user_name'     =>     $_POST["admin_user_name"],  
                          'admin_password'     =>     $_POST["admin_password"]  
                     )  
                );  
                $count = $statement->rowCount();  
                if($count > 0)  
                {  
                     $_SESSION["admin_user_name"] = $_POST["admin_user_name"];  
                     header("location:index.php");  
                }  
                else  
                {  
                    
                }  
           }  
      }  
 }  
 catch(PDOException $error)  
 {  
      $message = $error->getMessage();  
 }  
 ?>  

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page Design With bootstrap and CSS--</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- CSS-->
<style>
    .col-md-7{
        margin-top: 50px;
        margin-left: 24%;
    }
    body{
        background-image: url(img/admin-block.png);
        background-size: cover;
    }
.card-header{
    background-color: darkorange;
    color: white;
    font-size: large;
    font-weight: bold;
    padding:15px;
    margin-top:-26px;
    
}
.card{
  padding:30px;
   background-color: #ffffff;
}
.btn:hover{
    background-color: darkblue;
}
.header{
  background-color:darkorange;
  color:white;
  padding: 50px;
  text-align:center;

}
.card-header{
  margin-left:-30px;
    margin-right:-30px;
    margin-top:-60px;
}
#teacher-link{
  background-color:green;
  color:white;
  text-decoration:none;
}
#teacher-link:hover{
  background-color:grey;
}
#hod-link{
  background-color:red;
  color:white;
  text-decoration:none;
}
#hod-link:hover{
  background-color:darkorange;
}
</style>

    <!-- CSS-->
</head>
<body>
  <div class="header">
    <h1>RONGO UNIVERSITY </h1>
    <h4>Student Attendance System</h4>
  </div>
  <img src="img/logo.png" alt="logo" style="margin-top:-227px; height:200px; width:300px">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
            <div class="card">
                <div class="card-header"><marquee behavior="" direction="right">Main Admin Login Here...</marquee></div>
                <div class="card-body">
                    <form action="" method="post">
                      <br>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" name="admin_user_name" class="form-control" placeholder="Enter Username..." required />
                            <span><?php $error_username; ?></span>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" name="admin_password" class="form-control" placeholder="Enter password..." required />
                            <span><?php $error_password; ?></span>
                        </div>
                        <div class="form-group">
                          
                        <input type="submit" name="login" value="Login" class="btn btn-primary form-control">
                        </div>
                         <div class="form-group">
                          <a href="../admin/login.php" class="form-control text-center bg-secondary" id="hod-link">HOD</a>
                        </div>
                         <div class="form-group">
                          <a href="../login.php" class="form-control text-center bg-info" id="teacher-link">Lecturer</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>
    
</body>
</html>