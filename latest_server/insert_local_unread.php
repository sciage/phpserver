<?php

	require "init_new_config.php";

$id_user_name = isset($_GET['id_user_name']) ? mysqli_real_escape_string($con, $_GET['id_user_name']) : "";
$id_conversation = isset($_GET['id_conversation']) ? mysqli_real_escape_string($con, $_GET['id_conversation']) : "";

mysqli_query($con,"SET SQL_SAFE_UPDATES = 0" );

	$sql = mysqli_query ($con, "UPDATE `candid_database`.`conversation_reply` SET `unread` = '0' WHERE `id_conversation` = '$id_conversation' and id_user_name != '$id_user_name'");
	//$sql = mysqli_query ($con, "UPDATE `user_name` SET `givenContact`='$contact'  WHERE `id_user_name`='$id_user_name'");

mysqli_query($con,"SET SQL_SAFE_UPDATES = 1" );

	if($sql){
		$resp=array('success' => true );
	}else{
		$resp=array('success' => false );
	}

	die(json_encode($resp));
?>
