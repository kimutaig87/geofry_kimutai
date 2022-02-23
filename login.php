<?php

//login.php

include('database_connection.php');

session_start();

if(isset($_SESSION["admin_id"]))
{
  header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Student Attendance System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


  <style>
    .card-body{
      background-color:;
      color:darkblue;
    }
    form{
      background-color:transparent;
    }
    input{
      background-color: transparent;
    }
    

 </style>
</head>
<body  style="background-image:url(img/back.jpg); background-size:cover">
<img src="img/logo.png" alt="logo" width="300" height="200">
<div class="jumbotron text-center" style="margin-bottom:0;margin-top:-230px ;background-color:darkorange;color:white">
  <h1 > RONGO UNIVERSITY  <br> Student Attendance System</h1>
  <h4 style="font-family:Roboto, sans serif; float:right"><marquee behavior="">Goldmine of Knowledge</marquee></h4>
</div>


<div class="container">
  <div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-5" style="margin-top:20px;">
      <div class="card">
        <div class="card-header" style="background-color:darkorange"> <i class="fa fa-user"></i> HOD Login Page</div>
        <div class="card-body">
          <form method="post" id="admin_login_form">
            <div class="form-group">
              <label>Enter Username</label>
              <input type="text" name="admin_user_name" id="admin_user_name" class="form-control" />
              <span id="error_admin_user_name" class="text-danger"></span>
            </div>
            <div class="form-group">
              <label>Enter Password</label>
              <input type="password" name="admin_password" id="admin_password" class="form-control" />
              <span id="error_admin_password" class="text-danger"></span>
            </div>
            <div class="form-group">
            
              <input type="submit" name="admin_login" id="admin_login" class="btn btn-primary form-control" value="Sign In." />
              
            </div>
            <div class="form-group">
              <a href="../main_admin/login.php" class="form-control btn btn-secondary">Main Admin</a>
            </div>
            <div class="form-group">
            <a class="form-control btn btn-success" href="../login.php">Click Here For Lecturer login</a>
              
              
            </div>
          </form>
        </div>
      </div>
    </div>
    <div>

    </div>
  </div>
</div>

</body>
</html>

<script>
$(document).ready(function(){
  $('#admin_login_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"check_admin_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#admin_login').val('Please wait...');
        $('#admin_login').attr('disabled', 'disabled');
      },
      success:function(data)
      {
        if(data.success)
        {
          location.href = "<?php echo $base_url; ?>admin";
        }
        if(data.error)
        {
          $('#admin_login').val('Login');
          $('#admin_login').attr('disabled', false);
          if(data.error_admin_user_name != '')
          {
            $('#error_admin_user_name').text(data.error_admin_user_name);
          }
          else
          {
            $('#error_admin_user_name').text('');
          }
          if(data.error_admin_password != '')
          {
            $('#error_admin_password').text(data.error_admin_password);
          }
          else
          {
            $('#error_admin_password').text('');
          }
        }
      }
    });
  });
});
</script>