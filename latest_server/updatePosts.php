<?php

require "init_new_config.php";

$id_posts = isset($_POST['id_posts']) ? mysqli_real_escape_string($con, $_POST['id_posts']) : "";
$text_status = isset($_POST['text_status']) ? mysqli_real_escape_string($con, $_POST['text_status']) : "";
$audio_file_link = isset($_POST['audio_file_link']) ? mysqli_real_escape_string($con, $_POST['audio_file_link']) : "";

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";

    $q = mysqli_query($con, "UPDATE posts SET text_status='" . $text_status . "', audio_file_link='" . $audio_file_link . "' WHERE id_posts='" . $id_posts . "' LIMIT 1");

    if ($q) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));



?>
