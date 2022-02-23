<?php

//header.php

include('main_admin/database_connection.php');
session_start();

if(!isset($_SESSION["teacher_id"]))
{
  header('location:login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>RU | Attendance System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap4.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.dataTables.min.js"></script>
  <script src="js/dataTables.bootstrap4.min.js"></script>

  
  
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
<!-- JS -->

<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>
  
  <style>
    body{
      
      background-image:url(img/admin-block.png);
      background-size:cover;
    }
  
     .nav-link{
      color:white;
      margin-left: 25px;

    }
    .nav-link:hover{
      background-color:white;
      color: #000000;
      font-size: 25px;
      padding: 0;
    }
   
  
  </style>
</head>
<body>

<nav id="nav" class="navbar navbar-expand-sm" 
style="background-color:darkorange; padding-top: 12px; padding-bottom: 12px; font-size: 18px;">
 
  <button class="navbar-toggler bg-dark" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
  <p class="fa-fa-bars text-white">Menu</p>
    <span class="navbar-toggler-icon"></span>
    
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
       <li class="nav-item">
        <a class="nav-link" href="index.php">Attendance Percentages</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Check Profile / Switch Class</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="attendance.php">Take / View Attendance</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>  
    </ul>
  </div>  
</nav>