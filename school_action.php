<?php

//grade_action.php

include('database_connection.php');

session_start();

$output = '';

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "SELECT * FROM tbl_school ";
		if(isset($_POST["search"]["value"]))
		{
			$query .= 'WHERE school_name LIKE "%'.$_POST["search"]["value"].'%" ';
		}
		if(isset($_POST["order"]))
		{
			$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$query .= 'ORDER BY school_name ASC ';
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
			

			/*
		//<td><a class="safe" href="edit.php?ID=<?php echo $row['ID']; ?>">Update</a></td>    */

			$sub_array[] = '<button type="button" name="edit_school" class="btn btn-info btn-sm edit_school" id="'.$row["school_id"].'"><i class="fa fa-edit" aria-hidden="true"></i> Edit</button>';
			$sub_array[] = '<button type="button" name="delete_school" title="This Option is Not Available" class="btn btn-danger btn-sm delete_school" id="'.$row["school_id"].'"><i class="fa fa-trash"></i> Delete</button>';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"			    =>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_school'),
			"data"				=>	$data
		);

		
	}
	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$school_name = '';
		$error_school_name = '';
		$error = 0;
		if(empty($_POST["school_name"]))
		{
			$error_school_name = 'School Name is required';
			$error++;
		}
		else
		{
			$school_name = $_POST["school_name"];
		}
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_school_name'				=>	$error_school_name
			);
		}
		else
		{
			if($_POST["action"] == "Add")
			{
				$data = array(
					':school_name'				=>	$school_name,
				);
				$query = "
				INSERT INTO tbl_school 
				(school_name) 
				SELECT * FROM (SELECT :school_name) as temp 
				WHERE NOT EXISTS (
					SELECT school_name FROM tbl_school WHERE school_name = :school_name
				) LIMIT 1
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					if($statement->rowCount() > 0)
					{
						$output = array(
							'success'		=>	'School Added Successfully!!',
						);
					}
					else
					{
						$output = array(
							'error'					=>	true,
							'error_school_name'		=>	'School Already Exists'
						);
					}
				}
			}
			if($_POST["action"] == "Edit")
			{
				$data = array(
					':school_name'			=>	$school_name,
					':school_id'				=>	$_POST["school_id"]
				);

				$query = "
				UPDATE tbl_school 
				SET school_name = :school_name 
				WHERE school_id = :school_id
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$output = array(
						'success'		=>	'School Updated Successfully',
					);
				}
			}
		}
	}

	if($_POST["action"] == "edit_fetch")
	{
		$query = "
		SELECT * FROM tbl_school WHERE school_id = '".$_POST["school_id"]."' ";
		$statement = $connect->prepare($query);
		$output = array();
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["school_name"] = $row["school_name"];
				$output["school_id"] = $row["school_id"];
			}

		}
	}
	

	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_school 
		WHERE school_id = '".$_POST["school_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Deleted Successfully';
		}
	}

	echo json_encode($output);
}

?>