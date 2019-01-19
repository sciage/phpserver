<?php

require "init_new_config.php";
require_once("update_user_token.php");
require_once("send_notification_receiver_owner.php");
require_once("all_arrays.php");

$senderId = isset($_POST['senderId']) ? mysqli_real_escape_string($con, $_POST['senderId']) : "";
$receiverId = isset($_POST['receiverId']) ? mysqli_real_escape_string($con, $_POST['receiverId']) : "";
$chatText = isset($_POST['chatText']) ? mysqli_real_escape_string($con, $_POST['chatText']) : "";
$chatImage = isset($_POST['chatImage']) ? mysqli_real_escape_string($con, $_POST['chatImage']) : "";
$receiverAnonymous = isset($_POST['receiverAnonymous']) ? mysqli_real_escape_string($con, $_POST['receiverAnonymous']) : "";
$receiverAnonymousUsername = isset($_POST['receiverAnonymousUsername']) ? mysqli_real_escape_string($con, $_POST['receiverAnonymousUsername']) : "";
$senderAnonymous = isset($_POST['senderAnonymous']) ? mysqli_real_escape_string($con, $_POST['senderAnonymous']) : "";
$id_posts = isset($_POST['id_posts']) ? mysqli_real_escape_string($con, $_POST['id_posts']) : "";
$token = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : "";

$date = date_create();
$post_date = date_timestamp_get($date) * 1000;

    $sql = mysqli_query($con, "Insert into `chat_messages` (`senderId`, `receiverId`, `chatText`, `chatImage`, `time`, 
        `receiverAnonymous`, `senderAnonymous`, `id_posts`) values ('$senderId', '$receiverId', '$chatText', '$chatImage', 
        '$post_date', '$receiverAnonymous', '$senderAnonymous', '$id_posts')") or die(mysqli_error($con));

    if ($sql) {
        $resp = array('success' => true);

        $updateUser = update_user_token($senderId, $token);

        $topic = "Someone sent you a Private Message";
        //$topic02 = "Someone replied on this comment in ";

// send notification to post owner
        //($post_id, $topic, $id_user_name, $RANDOM_RECEIVER_USERNAME, $RANDOM_RECEIVER_ID, $RANDOM_SENDER_ID, $RECEIVER_USERID)

        //$id_conversation_reply, $chatText, $chatImage, $post_id, $topic, $id_user_name, $RANDOM_RECEIVER_USERNAME, $avatar_url, $RANDOM_RECEIVER_ID, $RECEIVER_USERID
        $send_notification_comment_owner = send_notification_to_chat_owner($id_conversation_reply, $chatText, $chatImage, $id_posts, $topic, $senderId, $receiverAnonymousUsername, $receiverAnonymous, $senderAnonymous, $receiverId); // it returns back user ID



    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));



?>
