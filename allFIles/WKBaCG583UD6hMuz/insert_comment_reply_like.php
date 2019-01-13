<?php

require "init_new_config.php";

$id_post_comment_reply = isset($_POST['id_post_comment_reply']) ? mysqli_real_escape_string($con, $_POST['id_post_comment_reply']) : "";
$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$likes = isset($_POST['likes']) ? mysqli_real_escape_string($con, $_POST['likes']) : "";
$post_comment_reply_id = isset($_POST['post_comment_reply_id']) ? mysqli_real_escape_string($con, $_POST['post_comment_reply_id']) : "";

$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";

$checkLike = mysqli_query($con, "SELECT * FROM `comment_reply_likes`  where `id_post_comment_reply` = '" . $id_post_comment_reply . "'");

if(mysqli_num_rows($checkLike)>0){
    $q = mysqli_query($con, "delete  from `comment_reply_likes` where `id_post_comment_reply` = '" . $id_post_comment_reply . "'");
    if ($q) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }
    die(json_encode($resp));

} else {
    $sql = mysqli_query($con, "INSERT INTO `comment_reply_likes` (`id_post_comment_reply`, `id_user_name`, `likes`,  `post_comment_reply_id`)
	VALUES ('$id_post_comment_reply', '$id_user_name', '$likes', '$post_comment_reply_id')") or die(mysqli_error($con));

    $cat_id = mysqli_insert_id($con);

    if ($cat_id) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));
}






?>
