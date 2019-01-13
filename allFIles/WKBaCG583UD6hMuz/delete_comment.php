<?php
//	header("Content-Type : application/json");

require "init_new_config.php";
/* FETCH ALL LIKERS OF A POST */

$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";
$id_post_comments = isset($_POST['id_post_comments']) ? mysqli_real_escape_string($con, $_POST['id_post_comments']) : "";

    $json = array();

    $sql = mysqli_query($con, "SELECT * FROM post_comments WHERE id_post_comments = '" . $_POST['id_post_comments'] . "'");
    $r1 = mysqli_fetch_array($sql);
    if ($r1['id_post_comments'] != '') {

        $q = mysqli_query($con, "delete  from post_comments where id_post_comments = '" . $_POST['id_post_comments'] . "'");
        $qr = mysqli_query($con, "delete  from post_comment_reply where id_post_comments = '" . $_POST['id_post_comments'] . "'");

    }
    $json = array("status" => 1, "msg" => "Success");


    die(json_encode($json));



?>
