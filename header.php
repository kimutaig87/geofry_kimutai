<?php

//header.php

include('database_connection.php');
session_start();  
 if(!isset($_SESSION["admin_user_name"]))  
 {  
      header('location:login.php'); 
 }  
 
/*
session_start();

if(!isset($_SESSION["main_admin_id"]))
{
  header('location:login.php');
}

*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Rongo University Student Attendance System </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>!-->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/dataTables.bootstrap4.min.css">
  <script src="../js/jquery.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/jquery.dataTables.min.js"></script>
  <script src="../js/dataTables.bootstrap4.min.js"></script>
</head>
<body>
  <style type="text/css">
    nav{
      background-color: darkorange;
      padding-bottom: 12px;
      padding-top: 12px;
      position: fixed;
    }
   
    .nav-link{
      color:white;
      margin-left: 20px;
      font-size: 20px;
    }
    .nav-link:hover{
      font-size:20px;
      font-family: italic;
      color: #000000;
      background-color: white;
      padding:7px;
    }
    body{
      background-image: url(img/admin-block.png);
      background-size: cover;
    }
  </style>


<nav class="navbar navbar-expand-sm fixed-top" style="padding: 20px;">
 <i style="color:white;" class=""><button class="navbar-toggler" type="button"
  style="background-color: #000000; color:white; " data-toggle="collapse" data-target="#collapsibleNavbar"></i>
  Menu
    <span class="navbar-toggler-icon pull-right"><i class="" style="color: white;"></i></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">

       <li class="nav-item"> 
        <a class="nav-link" href="index.php" style="background-color: white; color: #000000;">Gennerate Report</a>
      </li>
       <li class="nav-item">
        <a class="nav-link" href="school.php">Schools</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="grade.php">Classes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="teacher.php">Lecturers</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="student.php">Students</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="attendance.php">Full Attendance Report</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="hods.php">HODs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="classPercentage.php">Class_Report</a>
      </li>
       <li class="nav-item">
        <a class="nav-link" href="schoolPercentage.php">school report</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>  
     
    </ul>
  </div>  
</nav>

<br><br><br> 