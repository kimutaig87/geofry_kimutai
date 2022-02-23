<?php

//student_action.php

include('../main_admin/database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_attendance 
		
		INNER JOIN tbl_school 
		ON tbl_school.school_id = tbl_attendance.grade_school_id 
	
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
				WHERE tbl_school.school_name LIKE "%'.$_POST["search"]["value"].'%" 
		 
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
			ORDER BY tbl_attendance.grade_school_id DESC 
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
			
			
		
			$sub_array[] = $row["school_name"];
			
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_grade'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == "index_fetch")
	{
		$query = "
		SELECT * FROM tbl_school
		LEFT JOIN tbl_attendance 
		ON tbl_attendance.grade_school_id = tbl_school.school_id 
		
	 
		";
		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_school.school_name LIKE "%'.$_POST["search"]["value"].'%" 
			 
			
			';
		}
		$query .= 'GROUP BY tbl_school.school_id ';
		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY tbl_school.school_name ASC ';
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
			
			$sub_array[] = $row["school_id"];
			$sub_array[] = $row["school_name"];
			$sub_array[] = get_school_percentage($connect, $row["grade_school_id"]);
			$sub_array[] = get_school_percentage_remarks($connect, $row["grade_school_id"]);
            
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
}


?>