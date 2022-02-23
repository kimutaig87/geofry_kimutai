<?php

//student_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_student 
		INNER JOIN tbl_grade 
		ON tbl_grade.grade_id = tbl_student.student_grade_id 
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_student.student_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_student.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
			
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
			ORDER BY tbl_student.student_id DESC 
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
			
			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_roll_number"];
			
			$sub_array[] = $row["grade_name"];
			$sub_array[] = '<button type="button" name="edit_student" class="btn btn-primary btn-sm edit_student" id="'.$row["student_id"].'"><i class="fa fa-edit" aria-hidden="true"></i> Edit</button>';
			$sub_array[] = '<button type="button" name="delete_student" class="btn btn-danger btn-sm delete_student" id="'.$row["student_id"].'"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
		
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_student'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$student_name = '';
		$student_roll_number = '';
		
		$student_grade_id = '';
		$error_student_name = '';
		$error_student_roll_number = '';
		
		$error_student_grade_id = '';
		$error = 0;
		if(empty($_POST["student_name"]))
		{
			$error_student_name = 'Student Name is required';
			$error++;
		}
		else
		{
			$student_name = $_POST["student_name"];
		}
		if(empty($_POST["student_roll_number"]))
		{
			$error_student_roll_number = 'Student RegNo is required';
			$error++;
		}
		else
		{
			$student_roll_number = $_POST["student_roll_number"];
		}
		if(empty($_POST["student_grade_id"]))
		{
			$error_student_grade_id = "Class is required";
			$error++;
		}
		else
		{
			$student_grade_id = $_POST["student_grade_id"];
		}
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_student_name'			=>	$error_student_name,
				'error_student_roll_number'		=>	$error_student_roll_number,
				
				'error_student_grade_id'		=>	$error_student_grade_id
			);
		}
		else
		{
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':student_name'		=>	$student_name,
					':student_roll_number'	=>	$student_roll_number,
					
					':student_grade_id'	=>	$student_grade_id
				);
				$query = "
				INSERT INTO tbl_student 
				(student_name, student_roll_number, student_grade_id) 
				SELECT * FROM (SELECT :student_name, :student_roll_number, :student_grade_id) as temp 
				WHERE NOT EXISTS (
					SELECT student_roll_number FROM tbl_student WHERE student_roll_number = :student_roll_number
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
							'error_student_roll_number'		=>	'RegNo Already Exists'
						);
					}

				}
			}
			if($_POST["action"] == "Edit")
			{
				$data = array(
					':student_name'			=>	$student_name,	
					':student_roll_number'	=>	$student_roll_number,
					
					':student_grade_id'		=>	$student_grade_id,
					':student_id'			=>	$_POST["student_id"]
				);
				$query = "
				UPDATE tbl_student 
				SET student_name = :student_name, 
				student_roll_number = :student_roll_number, 
				
				student_grade_id = :student_grade_id 
				WHERE student_id = :student_id
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

	if($_POST["action"] == "edit_fetch")
	{
		$query = "
		SELECT * FROM tbl_student 
		WHERE student_id = '".$_POST["student_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["student_name"] = $row["student_name"];
				$output["student_roll_number"] = $row["student_roll_number"];
				
				$output["student_grade_id"] = $row["student_grade_id"];
				$output["student_id"] = $row["student_id"];
			}
			echo json_encode($output);
		}
	}
	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_student 
		WHERE student_id = '".$_POST["student_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Delete Successfully';
		}
	}
}

?>