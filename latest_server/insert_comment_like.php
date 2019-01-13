<?php

require "init_new_config.php";
//require_once("init_new_config_local.php");

// old
$id_post_comment = isset($_GET['id_post_comment']) ? mysqli_real_escape_string($con, $_GET['id_post_comment']) : "";
$id_user_name = isset($_GET['id_user_name']) ? mysqli_real_escape_string($con, $_GET['id_user_name']) : "";  // sender user ID
$comment_likes = isset($_GET['comment_likes']) ? mysqli_real_escape_string($con, $_GET['comment_likes']) : "";
$token = isset($_GET['token']) ? mysqli_real_escape_string($con, $_GET['token']) : "0";


$checkLike = mysqli_query($con, "SELECT id_post_comments_likes FROM `post_comments_likes`  where `id_post_comment` = '" . $id_post_comment . "' and `id_user_name` = '" . $id_user_name . "' ");

if(mysqli_num_rows($checkLike)>0){

    $id_post_comments_likes = $checkLike ->fetch_object()->id_post_comments_likes;

    $q = mysqli_query($con, "delete from `post_comments_likes` where `id_post_comments_likes` = '" . $id_post_comments_likes . "' ");
    if ($q) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }
    die(json_encode($resp));

} else {
    $sql = mysqli_query($con, "INSERT INTO `post_comments_likes` (`id_post_comment`, `id_user_name`, `comment_likes`)
	VALUES ('$id_post_comment', '$id_user_name', '$comment_likes')") or die(mysqli_error($con));

    $cat_id = mysqli_insert_id($con);

    if ($cat_id) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));
}






?>
