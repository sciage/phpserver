<?php
//	header("Content-Type : application/json");

	require "init_new_config.php";
	
	/* FETCH ALL HAShTAGS */
	
		$records=array();

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";

	$q=mysqli_query($con,"select categories.id_categories as id,
							categories.category as name, categories.not_selected_image as unselected, 
							categories.selected_image as selected  FROM categories");



	while($row=mysqli_fetch_row($q)){
		$records[]=array(
				"id"=> $row[0],
				"name"=>  $row[1],
				"unselected"=> $row[2],
				"selected"=>  $row[3]
		);
	}

	die(json_encode($records));






?>
