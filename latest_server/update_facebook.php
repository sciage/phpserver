<?php

	require "init_new_config.php";

$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$facebook = isset($_POST['facebook']) ? mysqli_real_escape_string($con, $_POST['facebook']) : "";

	$sql = mysqli_query ($con, "UPDATE `user_name` SET `given_facebook`='$facebook'  WHERE `id_user_name`='$id_user_name'");

	if($sql){
		$resp=array('success' => true );
	}else{
		$resp=array('success' => false );
	}

	die(json_encode($resp));
?>
