<?php

require "init_new_config.php";

$id_posts = isset($_POST['id_posts']) ? mysqli_real_escape_string($con, $_POST['id_posts']) : "";

if ($_POST['action'] == 'delete') {
    $q = $con->prepare("DELETE FROM posts WHERE `id_posts`=? LIMIT 1");
    $q->bind_param("i", intval($id_posts));
    $q->execute();

    $q1 = mysqli_query($con, "delete  from postnotifications where postId = '" . $_POST['id_posts'] . "'  ");
    $q2 = mysqli_query($con, "delete  from feeling_category where id_posts = '" . $_POST['id_posts'] . "'  ");
    $q3 = mysqli_query($con, "delete  from post_comments  where id_posts = '" . $_POST['id_posts'] . "'  ");


    if ($q->affected_rows > 0) {
        echo '{"success": true }';
    } else {
        echo '{"success": false }';
    }
    $q->close();

    die();
}

if ($_POST['action'] == 'text_status' && !empty($_POST['text_status'])) {

    $text_status = isset($_POST['text_status']) ? mysqli_real_escape_string($con, $_POST['text_status']) : "";

    $q = $con->prepare("UPDATE posts SET `text_status`=? WHERE `id_posts`=? LIMIT 1");
    $q->bind_param("si", $text_status, intval($id_posts));
    $q->execute();

    if ($q->affected_rows > 0) {
        echo '{"success": true }';
    } else {
        echo '{"success": false }';
    }
    $q->close();

    die();
}

if ($_POST['action'] == 'remove_audio') {
    // $q=$con->prepare("UPDATE posts SET `audio_file_link` = NULL ,`audio_duration`= NULL  WHERE `id_posts`=? LIMIT 1");
    $q = $con->prepare("UPDATE posts SET `audio_file_link` = '' ,`audio_duration`= ''  WHERE `id_posts`=? LIMIT 1");
    $q->bind_param("i", intval($id_posts));
    $q->execute();

    if ($q->affected_rows > 0) {
        echo '{"success": true }';
    } else {
        echo '{"success": false }';
    }
    $q->close();

    die();
}


die('Invalid Request');
?>
