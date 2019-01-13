<?php
//	header("Content-Type : application/json");

	require "init_new_config.php";
	
	/* FETCH ALL HAShTAGS */
	
		$records=array();

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";

	$q=mysqli_query($con,"select categories.id_categories as id,
							convert(binary convert(categories.category using latin1) using utf8) as name  FROM categories");



	while($row=mysqli_fetch_row($q)){
		$records[]=array(
				"name"=> $row[1],
				"id"=>  $row[0]
		);
	}

	die(json_encode($records));






?>
