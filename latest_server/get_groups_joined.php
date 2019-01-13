<?php
//	header("Content-Type : application/json");

	require "init_new_config.php";
	
	/* FETCH ALL HAShTAGS */
	
		$records=array();

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";

	$q=mysqli_query($con,"SELECT group_id, groups.name as name, c.category FROM candid_database.groups 
		LEFT JOIN categories c ON c.id_categories = groups.id_categories
    	WHERE group_id 
         IN (SELECT group_id FROM candid_database.groups_user where id_user_name = $user_id);");



	while($row=mysqli_fetch_row($q)){
		$records[]=array(

				"group_id"=> $row[0],
				"name"=>  $row[1],
				"category"=>  $row[2]
		);
	}

	die(json_encode($records));






?>
