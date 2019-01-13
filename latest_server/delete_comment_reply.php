<?php
//header("Content-Type : application/json");

require "init_new_config.php";
/* FETCH ALL LIKERS OF A POST */

$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";
$id_post_comment_reply = isset($_POST['id_post_comment_reply']) ? mysqli_real_escape_string($con, $_POST['id_post_comment_reply']) : "";

    $records = array();


    $sql = mysqli_query($con, "SELECT * FROM post_comment_reply WHERE id_post_comment_reply = '" . $_POST['id_post_comment_reply'] . "'");
    $r1 = mysqli_fetch_array($sql);
    if ($r1['id_post_comment_reply'] != '') {

        $q = mysqli_query($con, "delete  from post_comment_reply where id_post_comment_reply = '" . $_POST['id_post_comment_reply'] . "'");

    }
    $json = array("status" => 1, "msg" => "Success");


    die(json_encode($json));



?>
