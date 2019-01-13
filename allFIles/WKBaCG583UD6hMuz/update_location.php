<?php

	require "init_new_config.php";

$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$givenLocation = isset($_POST['givenLocation']) ? mysqli_real_escape_string($con, $_POST['givenLocation']) : "";

	$sql = mysqli_query ($con, "UPDATE `user_name` SET `givenLocation`='$givenLocation'  WHERE `id_user_name`='$id_user_name'");

	if($sql){
		$resp=array('success' => true );
	}else{
		$resp=array('success' => false );
	}

	die(json_encode($resp));
?>
