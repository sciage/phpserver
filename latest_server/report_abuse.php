<?php

require "init_new_config.php";

$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$sender_user_id = isset($_POST['sender_user_id']) ? mysqli_real_escape_string($con, $_POST['sender_user_id']) : "";
$id_posts = isset($_POST['id_posts']) ? mysqli_real_escape_string($con, $_POST['id_posts']) : "";
$abuse_message = isset($_POST['abuse_message']) ? mysqli_real_escape_string($con, $_POST['abuse_message']) : "";

    $sql = mysqli_query($con, "INSERT INTO `report_abuse` (`id_posts`, `id_user_name`, `sender_user_id`, `abuse_message`)
	VALUES ('$id_posts','$id_user_name','$sender_user_id','$abuse_message')") or die(mysqli_error($con));

    $sql = mysqli_query($con, "UPDATE `posts` SET report_abuse_count = report_abuse_count+1 WHERE `id_posts`='" . $id_posts . "' ");

    if ($sql) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));




?>
