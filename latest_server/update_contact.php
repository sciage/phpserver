<?php

	require "init_new_config.php";

$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$contact = isset($_POST['contact']) ? mysqli_real_escape_string($con, $_POST['contact']) : "";

mysqli_query($con,"SET SQL_SAFE_UPDATES = 0" );

	$sql = mysqli_query ($con, "UPDATE `user_name` SET `givenContact`='$contact'  WHERE `id_user_name`='$id_user_name'");

mysqli_query($con,"SET SQL_SAFE_UPDATES = 0" );

if($sql){
		$resp=array('success' => true );
	}else{
		$resp=array('success' => false );
	}

	die(json_encode($resp));
?>
