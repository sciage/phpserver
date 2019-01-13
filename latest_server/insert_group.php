<?php

require "init_new_config.php";

if( empty($_POST['user_id']) || empty($_POST['id_categories']) || empty($_POST['group_name'])  ){
	echo '{"status":"Invalid request"}';
	die();
}

$user_id = intval( $_POST['user_id'] );
$id_categories = intval($_POST['id_categories']);
$name = mysqli_real_escape_string($con, $_POST['group_name']);

$date = date_create();
$post_date = date_timestamp_get($date) * 1000;

$group_image = isset($_POST['group_image']) ? mysqli_real_escape_string($con, $_POST['group_image']) : "null";
$group_description = isset($_POST['group_description']) ? mysqli_real_escape_string($con, $_POST['group_description']) : NULL;


$q = mysqli_query($con, "INSERT INTO groups(`id_categories`,`name`,`created_by_id_user_name`, `created_at`, `group_image_url`, `group_description` )
	VALUES( '".$id_categories."','".$name."','".$user_id."', '".$post_date."', '".$group_image."','".$group_description."') ");
$group_id = mysqli_insert_id($con);
if ($group_id) {
	$joingroup = mysqli_query($con, "INSERT INTO groups_user(`group_id`,`id_user_name`) VALUES( '".$group_id."','".$user_id."') ");
    $resp = array('success' => true, 'category_id' => $group_id, 'name' => $_POST['group_name']);
} else {
    $resp = array('success' => false);
}

die(json_encode($resp));


?>
