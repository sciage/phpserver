<?php

require "init_new_config.php";

$post_id = isset($_POST['post_id']) ? mysqli_real_escape_string($con, $_POST['post_id']) : "";
$post_text = isset($_POST['post_text']) ? mysqli_real_escape_string($con, $_POST['post_text']) : "";

$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";


    $sql = mysqli_query($con, "INSERT INTO `specific_post` (`post_id`, `post_text`)
	VALUES ('$post_id', '$post_text')") or die(mysqli_error($con));

    $cat_id = mysqli_insert_id($con);

    if ($cat_id) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));



?>
