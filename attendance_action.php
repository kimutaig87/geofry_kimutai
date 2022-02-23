<?php

//attendance_action.php

include('main_admin/database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_attendance 
		INNER JOIN tbl_student 
		ON tbl_student.student_id = tbl_attendance.student_id 
		INNER JOIN tbl_grade 
		ON tbl_grade.grade_id = tbl_student.student_grade_id 
		WHERE tbl_attendance.teacher_id = '".$_SESSION["teacher_id"]."' AND (
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_student.student_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_student.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.attendance_status LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.course_code LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.topic LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.week LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_grade.grade_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_attendance.attendance_date LIKE "%'.$_POST["search"]["value"].'%") 
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
			ORDER BY tbl_attendance.attendance_id DESC 
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
			$status = '';
			if($row["attendance_status"] == "Present")
			{
				$status = '<label class="badge badge-success">Present</label>';
			}

			if($row["attendance_status"] == "Absent")
			{
				$status = '<label class="badge badge-danger">Absent</label>';
			}

			$sub_array[] = $row["student_name"];
			$sub_array[] = $row["student_roll_number"];
			$sub_array[] = $row["grade_name"];
			$sub_array[] = $status;
			$sub_array[] = $row["attendance_date"];
			$sub_array[] = $row["week"];
			$sub_array[] = $row["course_code"];
			$sub_array[] = $row["topic"];
			$data[] = $sub_array;
		}

		$output = array(
			'draw'				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_attendance'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == "Add")
	{
		$attendance_date = '';
		$topic = '';
		$course_code = '';
		$week = '';
		$error_attendance_date = '';
		$error_course_code = '';
		$error_week = '';
		$error_topic = '';
		$error = 0;
		if(empty($_POST["attendance_date"]))
		{
			$error_attendance_date = 'Attendance Date is required';
			$error++;
		}
		else
		{
			$attendance_date = $_POST["attendance_date"];
		}
		if(empty($_POST["week"]))
		{
			$error_week = 'Week is required';
			$error++;
		}
		else
		{
			$week = $_POST["week"];
		}
		if(empty($_POST["course_code"]))
		{
			$error_course_code = ' Course Code is required';
			$error++;
		}
		else
		{
			$course_code = $_POST["course_code"];
		}
		if(empty($_POST["topic"]))
		{
			$error_topic = 'Topic Covered is required';
			$error++;
		}
		else
		{
			$topic = $_POST["topic"];
		}



		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_attendance_date'			=>	$error_attendance_date,
				'error_week'			        =>	$error_week,
				'error_course_code'		     	=>	$error_course_code,
				'error_topic'			        =>	$error_topic
				

			);
		}
		else
		{
			$student_id = $_POST["student_id"];
			$query = '
			SELECT attendance_date FROM tbl_attendance 
			WHERE teacher_id = "'.$_SESSION["teacher_id"].'" 
			AND attendance_date = "'.$attendance_date.'"
			';
			$statement = $connect->prepare($query);
			$statement->execute();
			
			for($count = 0; $count < count($student_id); $count++)
				{
					$data = array(
						':student_id'			=>	$student_id[$count],
						':attendance_status'	=>	$_POST["attendance_status".$student_id[$count].""],
						':attendance_date'		=>	$attendance_date,
						':week'		            =>	$week,
						

						':teacher_id'			 =>	$_SESSION["teacher_id"],
						':attendance_grade_id'   => $_POST["attendance_grade_id"],
						':grade_school_id'       => $_POST["grade_school_id"],
						':course_code'           => $course_code,
						':topic'                 => $topic	
						
					
					);

					$query = "
					INSERT INTO tbl_attendance 
					(student_id, attendance_status, attendance_date, week, teacher_id, attendance_grade_id, grade_school_id, course_code, topic) 
					VALUES (:student_id, :attendance_status, :attendance_date, :week, :teacher_id, :attendance_grade_id, :grade_school_id, :course_code, :topic )
					";
					$statement = $connect->prepare($query);
					$statement->execute($data);
				}
				$output = array(
					'success'		=>	'Data Added Successfully',
				);
			}
		
		echo json_encode($output);
	}

	if($_POST["action"] == "index_fetch")
	{
		$query = "
		SELECT * FROM tbl_attendance 
		INNER JOIN tbl_student 
		ON tbl_student.student_id = tbl_attendance.student_id 
		INNER JOIN tbl_grade 
		ON tbl_grade.grade_id = tbl_student.student_grade_id 
		WHERE tbl_attendance.teacher_id = '".$_SESSION["teacher_id"]."' AND (
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			tbl_student.student_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_student.student_roll_number LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_grade.grade_name LIKE "%'.$_POST["search"]["value"].'%" )
			';
		}
		$query .= 'GROUP BY tbl_student.student_id ';
		if(isset($_POST["order"]))
		{
			$query .= '
			ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
			';
		}
		else
		{
			$query .= '
			ORDER BY tbl_student.student_roll_number ASC 
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
			$sub_array[] = get_attendance_percentage($connect, $row["student_id"]);
		     $data[] = $sub_array;
		}
		$output = array(
			'draw'					=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_student'),
			"data"				=>	$data
		);
		echo json_encode($output);
	}
}


?>