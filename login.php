<?php

//login.php

include('main_admin/database_connection.php');

session_start();

if(isset($_SESSION["teacher_id"]) && (isset($_SESSION["teacher_grade_id"])))
{
  header('location:index.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Attendance System </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>

<style>
    .card-body{
      background-color: darkorange;
     
    }
    form{
      background-color:transparent;
    }
    body{
      background-image:url(img/admin-block.png);
      background-size:cover;
    }
  </style>

<body>
<img src="img/logo.png" alt="logo" width="300" height="200">
<div class="jumbotron text-center" style="margin-bottom:0; margin-top:-230px; color:white; background-color:darkorange">
  <h1>RONGO UNIVERSITY <br> Student Attendance System</h1>
</div>


<div class="container">
  <div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-6" style="margin-top:20px;">
      <div class="card">
        <div class="card-header" style="background-color: darkorange">Lecturer Login</div>
        <div class="card-body bg-white">
          <form method="post" id="teacher_login_form">
            <div class="form-group">
              <label>Email Address</label>
              <input type="text" name="teacher_emailid" id="teacher_emailid" class="form-control" />
              <span id="error_teacher_emailid" class="text-danger"></span>
            </div>
            <div class="form-group">
              <label></i> Enter Password</label>
              <input type="password" name="teacher_password" id="teacher_password" class="form-control" />
              <span id="error_teacher_password" class="text-danger"></span>
            </div>
            <div class="form-group">
              <input type="submit" name="teacher_login" id="teacher_login" class=" form-control btn btn-primary" value="Sign In" />
            
            </div>
            <div class="form-group">
              <a href="main_admin/login.php" class="form-control btn btn-secondary">Main Admin</a>
            </div>
            <div class="form-group">
                <a class="form-control btn btn-success" href="admin/login.php" style="">HOD? LogIn Here!</a>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-4">

    </div>
  </div>
</div>

</body>
</html>

<script>
$(document).ready(function(){
  $('#teacher_login_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"check_teacher_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#teacher_login').val('Validate...');
        $('#teacher_login').attr('disabled','disabled');
      },
      success:function(data)
      {
        if(data.success)
        {
          location.href="index.php";
        }
        if(data.error)
        {
          $('#teacher_login').val('Login');
          $('#teacher_login').attr('disabled', false);
          if(data.error_teacher_emailid != '')
          {
            $('#error_teacher_emailid').text(data.error_teacher_emailid);
          }
          else
          {
            $('#error_teacher_emailid').text('');
          }
          if(data.error_teacher_password != '')
          {
            $('#error_teacher_password').text(data.error_teacher_password);
          }
          else
          {
            $('#error_teacher_password').text('');
          }
        }
      }
    })
  });
});
</script>