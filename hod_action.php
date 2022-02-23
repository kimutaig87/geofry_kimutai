<?php

//student_action.php

include('database_connection.php');

session_start();

if(isset($_POST["action"]))
{
	if($_POST["action"] == "fetch")
	{
		$query = "
		SELECT * FROM tbl_admin
		INNER JOIN tbl_school
		ON tbl_school.school_id = tbl_admin.hod_school
		";

		if(isset($_POST["search"]["value"]))
		{
			$query .= '
			WHERE tbl_admin.admin_user_name LIKE "%'.$_POST["search"]["value"].'%" 
			OR tbl_admin.admin_id LIKE "%'.$_POST["search"]["value"].'%"
			OR tbl_admin.hod_school LIKE "%'.$_POST["search"]["value"].'%"
		
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
			ORDER BY tbl_admin.admin_id ASC 
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
			
			$sub_array[] = $row["admin_id"];
			$sub_array[] = $row["admin_user_name"];
			$sub_array[] = $row["school_name"];
			$sub_array[] = '<button type="button" name="edit_hod" class="btn btn-primary btn-sm edit_hod" id="'.$row["admin_id"].'"><i class="fa fa-edit" aria-hidden="true"></i> Edit</button>';
			$sub_array[] = '<button type="button" name="delete_hod" class="btn btn-danger btn-sm delete_hod" id="'.$row["admin_id"].'"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
		
			$data[] = $sub_array;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	$filtered_rows,
			"recordsFiltered"	=>	get_total_records($connect, 'tbl_admin'),
			"data"				=>	$data
		);

		echo json_encode($output);
	}

	if($_POST["action"] == 'Add' || $_POST["action"] == "Edit")
	{
		$admin_user_name = '';
		$admin_password = '';
		$hod_school = '';
		

		$error_admin_password = '';
        $error_admin_user_name= '';
		$error_hod_school = '';
		$error = 0;
		if(empty($_POST["admin_user_name"]))
		{
			$error_admin_user_name = 'Username is required';
			$error++;
		}
		else
		{
			$admin_user_name = $_POST["admin_user_name"];
		}
		if(empty($_POST["admin_password"]))
		{
			$error_admin_password = 'Password is required';
			$error++;
		}
		else
		{
			$admin_password = $_POST["admin_password"];
		}
		if(empty($_POST["hod_school"]))
		{
			$error_hod_school = "School Name is required";
			$error++;
		}
		else
		{
			$hod_school = $_POST["hod_school"];
		}
		if($error > 0)
		{
			$output = array(
				'error'							=>	true,
				'error_admin_user_name'			=>	$error_admin_user_name,
				'error_admin_password'		=>	$error_admin_password,
				
				'error_hod_school'		=>	$error_hod_school
			);
		}
		else
		{
			if($_POST["action"] == 'Add')
			{
				$data = array(
					':admin_user_name'		=>	$admin_user_name,
					':admin_password'	=>	password_hash($admin_password, PASSWORD_DEFAULT),
					':hod_school'	=>	$hod_school

				);
				$query = "
				INSERT INTO tbl_admin 
				(admin_user_name, admin_password, hod_school) 
				SELECT * FROM (SELECT :admin_user_name, :admin_password, :hod_school) as temp 
				WHERE NOT EXISTS (
					SELECT admin_user_name FROM tbl_admin WHERE admin_user_name = :admin_user_name
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
							'error_admin_user_name'		=>	'Username Already Exists'
						);
					}

				}
			}
			if($_POST["action"] == "Edit")
			{
				$data = array(
					':admin_user_name'			=>	$admin_user_name,	
					':admin_password'	=>	password_hash($admin_password, PASSWORD_DEFAULT),
					
					':hod_school'		=>	$hod_school,
					':admin_id'			=>	$_POST["admin_id"]
				);
				$query = "
				UPDATE tbl_admin 
				SET admin_user_name = :admin_user_name, 
				admin_password = :admin_password, 
				
				hod_school = :hod_school 
				WHERE admin_id = :admin_id
				";
				$statement = $connect->prepare($query);
				if($statement->execute($data))
				{
					$output = array(
						'success'		=>	'Data Updated Successfully',
					);
				}
			}
		}
		echo json_encode($output);
	}
    
	if($_POST["action"] == "edit_fetch")
	{
		$query = "
		SELECT * FROM tbl_admin 
		WHERE admin_id = '".$_POST["admin_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				$output["admin_user_name"] = $row["admin_user_name"];
                
				$output["hod_school"] = $row["hod_school"];
				$output["admin_id"] = $row["admin_id"];
			}
			echo json_encode($output);
		}
	}
	if($_POST["action"] == "delete")
	{
		$query = "
		DELETE FROM tbl_admin 
		WHERE admin_id = '".$_POST["admin_id"]."'
		";
		$statement = $connect->prepare($query);
		if($statement->execute())
		{
			echo 'Data Delete Successfully';
		}
	}
}


?>