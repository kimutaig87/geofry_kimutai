<?php

//profile.php

include('header.php');

$teacher_name = '';
$teacher_emailid = '';
$teacher_password = '';
$teacher_grade_id = '';

$error_teacher_name = '';

$error_teacher_emailid = '';
$error_teacher_grade_id = '';

$error = 0;
$success = '';

if(isset($_POST["button_action"]))
{

	if(empty($_POST["teacher_name"]))
	{
		$error_teacher_name = "Lecturer Name is required";
		$error++;
	}
	else
	{
		$teacher_name = $_POST["teacher_name"];
	}


	if(empty($_POST["teacher_emailid"]))
	{
		$error_teacher_emailid = "Email Address is required";
		$error++;
	}
	else
	{
		if(!filter_var($_POST["teacher_emailid"], FILTER_VALIDATE_EMAIL))
		{
			$error_teacher_emailid = "Invalid email format";
			$error;
		}
		else
		{
			$teacher_emailid = $_POST["teacher_emailid"];
		}
	}
	if(!empty($_POST["teacher_password"]))
	{
		$teacher_password = $_POST["teacher_password"];
	}

	if(empty($_POST["teacher_grade_id"]))
	{
		$error_teacher_grade_id = 'Class is required';
		$error++;
	}
	else
	{
		$teacher_grade_id = $_POST["teacher_grade_id"];
	}

	if($error == 0)
	{
		if($teacher_password != '')
		{
			$data = array(
				':teacher_name'			=>	$teacher_name,
				
				':teacher_emailid'		=>	$teacher_emailid,
				':teacher_password'		=>	password_hash($teacher_password, PASSWORD_DEFAULT),
				
				':teacher_grade_id'		=>	$teacher_grade_id,
				':teacher_id'			=>	$_POST["teacher_id"]
			);
			$query = "
			UPDATE tbl_teacher 
		      SET teacher_name = :teacher_name, 
		       
		      teacher_emailid = :teacher_emailid, 
		      teacher_password = :teacher_password, 
		      teacher_grade_id = :teacher_grade_id
		       
		      WHERE teacher_id = :teacher_id
			";
		}
		else
		{
			$data = array(
				':teacher_name'			=>	$teacher_name,
				
				':teacher_emailid'		=>	$teacher_emailid,
				
				':teacher_grade_id'		=>	$teacher_grade_id,
				':teacher_id'			=>	$_POST["teacher_id"]
			);
			$query = "
			UPDATE tbl_teacher 
		      SET teacher_name = :teacher_name, 
		       
		      teacher_emailid = :teacher_emailid, 
		      teacher_grade_id = :teacher_grade_id
		      
		      WHERE teacher_id = :teacher_id
			";
		}

		$statement = $connect->prepare($query);
		if($statement->execute($data))
		{
			$success = '<div class="alert alert-success">Profile Details Change Successfully</div>';
			header("location: attendance.php");
		}
	}
}


$query = "
SELECT * FROM tbl_teacher 
WHERE teacher_id = '".$_SESSION["teacher_id"]."'
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>
<head>
<title></title>
<link rel="stylesheet" href="select2.min.css" />
<style>
.select2-dropdown {top: 22px !important; left: 8px !important;}
.card-header{
  background-color: darkorange;
  color: white;
 
  font-size: 23px;
  font-family: sans-serif;
}
.card-footer{
  background-color: darkorange;
  color: white;
 
  font-size: 23px;
  font-family: sans-serif;
}
</style>
</head>

<div class="container" style="margin-top:30px">
  <span><?php echo $success; ?></span>
  <div class="card">
    <form method="post" id="profile_form" enctype="multipart/form-data">
		<div class="card-header">
			<div class="row">
				<div class="col-md-9">My Profile</div>
				<div class="col-md-3" align="right">
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Name <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="teacher_name" id="teacher_name" class="form-control" />
						<span class="text-danger"><?php echo $error_teacher_name; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Email Address <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="teacher_emailid" id="teacher_emailid" class="form-control" />
						<span class="text-danger"><?php echo $error_teacher_emailid; ?></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Password <span class="text-danger">*</span></label>
					<div class="col-md-8">
						<input type="text" name="teacher_password" id="teacher_password" class="form-control" placeholder="Leave blank to not change it" />
						<span class="text-danger"></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<label class="col-md-4 text-right">Class<span class="text-danger">*</span></label>
					<div class="col-md-8">
						<select name="teacher_grade_id" id="Grade" class="form-control">
                			
							<option value="" class="form-control">Select Class</option>
							<?php
                			echo load_grade_list($connect);
                			?>
							
                		</select>
						<span class="text-danger"><?php echo $error_teacher_grade_id; ?></span>
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer" align="center">
			<input type="hidden" name="hidden_teacher_image" id="hidden_teacher_image" />
			<input type="hidden" name="teacher_id" id="teacher_id" />
			<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-lg" value="Save" />
		</div>     
    </form>
  </div>
</div>
<br />
<br />
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="select2.min.js"></script>
<script>
$("#Grade").select2( {
	placeholder: "Select Class You Want To Take Attendance for...",
	allowClear: true
	
	} );
</script>

<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="css/datepicker.css" />

<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<script>
$(document).ready(function(){
	
<?php
foreach($result as $row)
{
?>
$('#teacher_name').val("<?php echo $row["teacher_name"]; ?>");

$('#teacher_emailid').val("<?php echo $row["teacher_emailid"]; ?>");

$('#Grade').val("<?php echo $row["teacher_grade_id"]; ?>");

$('#teacher_id').val("<?php echo $row["teacher_id"];?>");

<?php
}
?>
  
  	

});        
</script>