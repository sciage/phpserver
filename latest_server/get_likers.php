<?php
//	header("Content-Type : application/json");

require "init_new_config.php";
/* FETCH ALL LIKERS OF A POST */

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";
$id_posts = isset($_GET['id_posts']) ? mysqli_real_escape_string($con, $_GET['id_posts']) : "";


    $records = array();

    $q = mysqli_query($con, "SELECT user_nick_name,avatar_pics, id_user_name FROM user_name WHERE id_user_name IN ( SELECT DISTINCT id_user_name FROM feeling_category WHERE id_posts='" . $id_posts . "' AND feeling_likes=1 ) ");
    while ($row = mysqli_fetch_row($q)) {
        $records['likes'][] = array('name' => $row[0], 'avatar' => $row[1], 'id_user_name' => $row[2]);
    }

    $q = mysqli_query($con, "SELECT user_nick_name,avatar_pics, id_user_name FROM user_name WHERE id_user_name IN ( SELECT DISTINCT id_user_name FROM feeling_category WHERE id_posts='" . $id_posts . "' AND feeling_same=1 ) ");
    while ($row = mysqli_fetch_row($q)) {
        $records['same'][] = array('name' => $row[0], 'avatar' => $row[1], 'id_user_name' => $row[2]);
    }

    $q = mysqli_query($con, "SELECT user_nick_name,avatar_pics, id_user_name FROM user_name WHERE id_user_name IN ( SELECT DISTINCT id_user_name FROM feeling_category WHERE id_posts='" . $id_posts . "' AND feeling_hug=1 ) ");
    while ($row = mysqli_fetch_row($q)) {
        $records['hug'][] = array('name' => $row[0], 'avatar' => $row[1], 'id_user_name' => $row[2]);
    }

    $q = mysqli_query($con, "SELECT user_nick_name,avatar_pics, id_user_name FROM user_name WHERE id_user_name IN ( SELECT DISTINCT id_user_name FROM feeling_category WHERE id_posts='" . $id_posts . "' AND audio_listen=1 ) ");
    while ($row = mysqli_fetch_row($q)) {
        $records['listen'][] = array('name' => $row[0], 'avatar' => $row[1], 'id_user_name' => $row[2]);
    }

    die(json_encode($records));


?>
