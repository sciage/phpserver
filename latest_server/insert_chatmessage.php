<?php

require "init_new_config.php";
//require_once("init_new_config_local.php");

// old
$senderId = isset($_GET['senderId']) ? mysqli_real_escape_string($con, $_GET['senderId']) : "";
$receiverId = isset($_GET['receiverId']) ? mysqli_real_escape_string($con, $_GET['receiverId']) : "";  // sender user ID
$chatText = isset($_GET['chatText']) ? mysqli_real_escape_string($con, $_GET['chatText']) : "";
$chatImage = isset($_GET['chatImage']) ? mysqli_real_escape_string($con, $_GET['chatImage']) : "0";
$id_posts = isset($_GET['id_posts']) ? mysqli_real_escape_string($con, $_GET['id_posts']) : "0";
$senderAnonymous = isset($_GET['senderAnonymous']) ? mysqli_real_escape_string($con, $_GET['senderAnonymous']) : "0";
$receiverAnonymous = isset($_GET['receiverAnonymous']) ? mysqli_real_escape_string($con, $_GET['receiverAnonymous']) : "0";
$custom_user_name = isset($_GET['custom_user_name']) ? mysqli_real_escape_string($con, $_GET['custom_user_name']) : null;
$custom_sender_user_name = isset($_GET['custom_sender_user_name']) ? mysqli_real_escape_string($con, $_GET['custom_sender_user_name']) : null;
$token = isset($_GET['token']) ? mysqli_real_escape_string($con, $_GET['token']) : "0";

$date = date_create();
$post_date = date_timestamp_get($date) * 1000; // time

$insertChat = mysqli_query($con, "INSERT INTO `chat_messages` (`senderId`, `senderAnonymous`, `receiverId`, 
        `receiverAnonymous`, `chatText`, `chatImage`, `time`, `id_posts`, `custom_user_name`, `custom_sender_user_name`) 
        VALUES ($senderId, $senderAnonymous, $receiverId, $receiverAnonymous, $chatText, 
		$chatImage, $post_date, $id_posts, $custom_user_name, $custom_sender_user_name)");


    $cat_id = mysqli_insert_id($con);

    if ($cat_id) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));


?>
