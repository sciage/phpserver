<?php

require "init_new_config.php";
require_once("update_user_token.php");
require_once("send_notification_comment_owner_like.php");
//require_once("init_new_config_local.php");

// old
$id_post_comment = isset($_POST['id_post_comment']) ? mysqli_real_escape_string($con, $_POST['id_post_comment']) : "";
$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";  // sender user ID
$comment_likes = isset($_POST['comment_likes']) ? mysqli_real_escape_string($con, $_POST['comment_likes']) : "";
$post_id = isset($_POST['id_posts']) ? mysqli_real_escape_string($con, $_POST['id_posts']) : "";
$token = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : "insert_comment_like_test.php";


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
    $sql = mysqli_query($con, "INSERT INTO `post_comments_likes` (`id_post_comment`, `id_user_name`, `post_comment_id`, `comment_likes`)
	VALUES ('$id_post_comment', '$id_user_name','$post_id', '$comment_likes')") or die(mysqli_error($con));

    $cat_id = mysqli_insert_id($con);

    if ($cat_id) {
        $resp = array('success' => true);

        $updateUser = update_user_token($id_user_name, $token);

        $topic = "Someone liked your comment";
        //$topic02 = "Someone replied on this comment in ";

// send notification to post owner
        $send_notification_comment_owner = send_notification_to_comment_owner($id_user_name, $post_id, $topic, $id_post_comment); // it returns back user ID

    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));
}

?>
