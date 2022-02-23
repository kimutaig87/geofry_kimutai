<?php

//teacher_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_teacher 
		INNER JOIN tbl_grade 
		ON tbl_grade.grade_id = tbl_teacher.teacher_grade_id 
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_teacher.teacher_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_teacher.teacher_emailid LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_grade.grade_name LIKE "%'.$_POST["search"]["value"].'%" 
			';
		}
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].'
			';
		}
		else
		{
			$query .= '
			ORDER BY tbl_teacher.teacher_id DESC 
			';
		}
		if($_POST["length"] != -1)
		{
			$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$data = array();
		$filtered_rows = $statement->rowCount();
		foreach($result as $row)
		{
			$sub_array = array();
			
			$sub_array[] = $row["teacher_name"];
			$sub_array[] = $row["teacher_emailid"];
			$sub_array[] = $row["grade_name"];
			$sub_array[] = '<button type="button" title="View Lecturer Info" name="view_teacher" class="btn btn-info btn-sm view_teacher" id="'.$row["teacher_id"].'"><i class="fa fa-eye " aria-hidden="true"></i> </button>';
			$sub_array[] = '<button type="button" title="Update Lecturer Info"   name="edit_teacher" class="btn btn-primary btn-sm edit_teacher" id="'.$row["teacher_id"].'"><i class="fa fa-pencil-square " aria-hidden="true"></i> </button>';
			$sub_array[] = '<button type="button" title="Delete Record"  name="delete_teacher" class="btn btn-danger btn-sm delete_teacher" id="'.$row["teacher_id"].'"><i class="fa fa-trash " aria-hidden="true"></i> </button>';
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_teacher'),
			"data"				=>	$data
		);
		echo json_encode($output);
	}

	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$teacher_name = '';
		
		$teacher_emailid = '';
		$teacher_password = '';
		$teacher_grade_id = '';
		
		$error_teacher_name = '';
	
		$error_teacher_emailid = '';
		$error_teacher_password = '';
		$error_teacher_grade_id = '';
		
		$error = 0;

		if(empty($_POST["teacher_name"]))
		{
			$error_teacher_name = 'Lecturer Name is required';
			$error++;
		}
		else
		{
			$teacher_name = $_POST["teacher_name"];
		}
		
		if($_POST["action"] == "Add")
		{
			if(empty($_POST["teacher_emailid"]))
			{
				$error_teacher_emailid = 'Email Address is required';
				$error++;
			}
			else
			{
				if(!filter_var($_POST["teacher_emailid"], FILTER_VALIDATE_EMAIL))
				{
					$error_teacher_emailid = 'Invalid email format';
					$error++;
				}
				else
				{
					$teacher_emailid = $_POST["teacher_emailid"];
				}
			}
			if(empty($_POST["teacher_password"]))
			{
				$error_teacher_password = "Password is required";
				$error++;
			}
			else
			{
				$teacher_password = $_POST["teacher_password"];
			}
		}
		if(empty($_POST["teacher_grade_id"]))
		{
			$error_teacher_grade_id = "Class is required";
			$error++;
		}
		else
		{
			$teacher_grade_id = $_POST["teacher_grade_id"];
		}
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_teacher_name'			=>	$error_teacher_name,
				
				'error_teacher_emailid'			=>	$error_teacher_emailid,
				'error_teacher_password'		=>	$error_teacher_password,
				'error_teacher_grade_id'		=>	$error_teacher_grade_id
				
			);
		}
		else
		{
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':teacher_name'			=>	$teacher_name,
				
					':teacher_emailid'		=>	$teacher_emailid,
					':teacher_password'		=>	password_hash($teacher_password, PASSWORD_DEFAULT),
					
					':teacher_grade_id'		=>	$teacher_grade_id
				);
				$query = "
				INSERT INTO tbl_teacher 
				(teacher_name, teacher_emailid, teacher_password, teacher_grade_id) 
				SELECT * FROM (SELECT :teacher_name, :teacher_emailid, :teacher_password, :teacher_grade_id) as temp 
				WHERE NOT EXISTS (
					SELECT teacher_emailid FROM tbl_teacher WHERE teacher_emailid = :teacher_emailid
				) LIMIT 1
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					if($statement->rowCount() > 0)
					{
						$output = array(
							'success'		=>	'Data Added Successfully',
						);
					}
					else
					{
						$output = array(
							'error'					=>	true,
							'error_teacher_emailid'	=>	'Email Already Exists'
						);
					}
				}
			}
			if($_POST["action"] == "Edit")
			{
				$data = array(
					':teacher_name'		=>	$teacher_name,
					
					':teacher_password' => password_hash($_POST["teacher_password"], PASSWORD_DEFAULT),
					':teacher_grade_id'	=>	$teacher_grade_id,
					':teacher_id'		=>	$_POST["teacher_id"]
				);
				$query = "
				UPDATE tbl_teacher 
				SET teacher_name = :teacher_name, 
				 
				teacher_password = :teacher_password,
				teacher_grade_id = :teacher_grade_id
				
				WHERE teacher_id = :teacher_id
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$output = array(
						'success'		=>	'Data Edited Successfully',
					);
				}
			}
		}
		echo json_encode($output);
	}



	if($_POST["action"] == "single_fetch")
	{
		$query = "
		SELECT * FROM tbl_teacher 
		INNER JOIN tbl_grade 
		ON tbl_grade.grade_id = tbl_teacher.teacher_grade_id 
		WHERE tbl_teacher.teacher_id = '".$_POST["teacher_id"]."'";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			$output = '
			<div class="row">
			';
			foreach($result as $row)
			{
				$output .= '
				
				<div class="col-md-9">
					<table class="table">
						<tr>
							<th>Name</th>
							<td>'.$row["teacher_name"].'</td>
						</tr>
						
						<tr>
							<th>Email Address</th>
							<td>'.$row["teacher_emailid"].'</td>
						</tr>
						
						
						<tr>
							<th>Class</th>
							<td>'.$row["grade_name"].'</td>
						</tr>
					</table>
				</div>
				';
			}
			$output .= '</div>';
			echo $output;
		}
	}

	if($_POST["action"] == "edit_fetch")
	{
		$query = "
		SELECT * FROM tbl_teacher WHERE teacher_id = '".$_POST["teacher_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["teacher_name"] = $row["teacher_name"];
			
				$output["teacher_grade_id"] = $row["teacher_grade_id"];
				$output["teacher_id"] = $row["teacher_id"];
			}
			echo json_encode($output);
		}
	}

	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_teacher 
		WHERE teacher_id = '".$_POST["teacher_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Deleted Successfully';
		}
	}
	
}

?>