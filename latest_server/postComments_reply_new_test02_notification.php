<?php
require_once("init_new_config.php");
require_once("update_user_token.php");
require_once("send_notification_commentreply_owner.php");


$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";  // id of the user who commented the post
$id_posts = isset($_POST['id_posts']) ? mysqli_real_escape_string($con, $_POST['id_posts']) : ""; // id of the post
$id_post_comments_reply = isset($_POST['id_post_comments_reply']) ? mysqli_real_escape_string($con, $_POST['id_post_comments_reply']) : "";
$token = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : "0";

    // update user token of sending user
    $updateUser = update_user_token($id_user_name, $token);

    $topic = "Someone replied on your comment";

// send notification to post owner
    $send_notification_comment_owner = send_notification_to_comment_owner($id_user_name, $id_posts, $topic, $id_post_comments_reply); // it returns back user ID


$resp = array('success' => true);

echo json_encode($resp);

    die();


?>
