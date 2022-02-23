<?php

//database_connection.php

$connect = new PDO("mysql:host=localhost;dbname=attendance","root","");

//base url to project folder
$base_url = "http://localhost/aa/ru-student-attendance-system/";

// pulling all records from data base
function get_total_records($connect, $table_name)
{
	$query = "SELECT * FROM $table_name";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

// function to get class name
function load_grade_list($connect)
{
	$query = "
	SELECT * FROM tbl_grade ORDER BY grade_name ASC
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["grade_id"].'">'.$row["grade_name"].'</option>';
	}
	return $output;
}    

// function to calculate specific student attendance

function get_attendance_percentage($connect, $student_id)
{
	$query = " SELECT 
		ROUND((SELECT COUNT(*) FROM tbl_attendance 
		WHERE attendance_status = 'Present' 
		AND student_id = '".$student_id."') 
	* 100 / COUNT(*)) AS percentage FROM tbl_attendance 
	WHERE student_id = '".$student_id."' 
	";

	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		if($row["percentage"] > 0)
		{
			return $row["percentage"] . '%';
		}
		else
		{
			return 'NA';
		}
	}
}

//function to get class attendance percentage

function get_grade_percentage($connect, $attendance_grade_id)
{
	$query = "SELECT attendance_grade_id,
    ROUND((SUM(CASE WHEN attendance_status = 'Present' THEN 1 ELSE 0 END)/COUNT(*)*100),2) as present

FROM tbl_attendance WHERE attendance_grade_id = '".$attendance_grade_id."'  
GROUP BY attendance_grade_id

	";

	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		if($row["present"] > 0)
		{
			return $row["present"] . '%';
		}
		else
		{
			return 'NA';
		}
	}
}


//LEGIBLE TO SIT FOR EXAM OR NOT
function check_exam_legibility($connect, $student_id)
{
	$query = "SELECT 
		ROUND((SELECT COUNT(*) FROM tbl_attendance 
		WHERE attendance_status = 'Present' 
		AND student_id = '".$student_id."') 
	* 100 / COUNT(*)) AS percentage FROM tbl_attendance 
	WHERE student_id = '".$student_id."' 
	";

	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		if($row["percentage"] >= 70)
		{
			
			return 'To sit for exam';
		}
		else
		{
			return 'Not Legible';
		}
	}
}

// Class reamrks
function get_grade_remarks($connect, $attendance_grade_id)
{
	$query = "SELECT 
		ROUND((SELECT COUNT(*) FROM tbl_attendance 
		WHERE attendance_status = 'Present' 
		AND atte$attendance_grade_id = '".$attendance_grade_id."') 
	* 100 / COUNT(*)) AS class_percent FROM tbl_attendance 
	WHERE atte$attendance_grade_id = '".$attendance_grade_id."' 
	";

	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		if($row["class_percent"] >= 70)
		{
			
			return 'YES';
		}
		else
		{
			return 'NO';
		}
	}
}


// function to get the class name
function Get_student_name($connect, $student_id)
{
	$query = "
	SELECT student_name FROM tbl_student 
	WHERE student_id = '".$student_id."'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	foreach($result as $row)
	{
		return $row["student_name"];
	}
}
// get student Class name
function Get_student_grade_name($connect, $student_id)
{
	$query = "
	SELECT tbl_grade.grade_name FROM tbl_student 
	INNER JOIN tbl_grade 
	ON tbl_grade.grade_id = tbl_student.student_grade_id 
	WHERE tbl_student.student_id = '".$student_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row['grade_name'];
	}
}
// function to get teacher/lecturer name
function Get_student_teacher_name($connect, $student_id)
{
	$query = "
	SELECT tbl_teacher.teacher_name 
	FROM tbl_student 
	INNER JOIN tbl_grade 
	ON tbl_grade.grade_id = tbl_student.student_grade_id 
	INNER JOIN tbl_teacher 
	ON tbl_teacher.teacher_grade_id = tbl_grade.grade_id 
	WHERE tbl_student.student_id = '".$student_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row["teacher_name"];
	}
}

// functio to get class name
function Get_grade_name($connect, $grade_id)
{
	$query = "
	SELECT grade_name FROM tbl_grade 
	WHERE grade_id = '".$grade_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row["grade_name"];
	}
}

// function to get course code
function get_course_code($connect, $attendance_id)
{
	$query = "
	SELECT course_code FROM tbl_attendance 
	WHERE attendance_id = '".$attendance_id."'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	foreach($result as $row)
	{
		return $row["course_code"];
	}
}

// function to get topic covered during class time
function get_topic_covered($connect, $attendance_id)
{
	$query = "
	SELECT topic FROM tbl_attendance 
	WHERE attendance_id = '".$attendance_id."'
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	foreach($result as $row)
	{
		return $row["topic"];
	}
}



?>