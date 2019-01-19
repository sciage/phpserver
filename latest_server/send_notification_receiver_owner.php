<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 21/12/18
 * Time: 7:35 AM
 */

//require_once("init_new_config.php");
require_once("init_new_config.php");
require_once("send_notif.php");

function send_notification_to_chat_owner($id_conversation_reply, $chatText, $chatImage, $post_id, $topic, $id_user_name, $RANDOM_RECEIVER_USERNAME, $avatar_url, $RANDOM_RECEIVER_ID, $RECEIVER_USERID){
    global $con;

       // $id_user_name = $getUserId->fetch_object()->id_user_name;
       // $groups_name = $getUserId->fetch_object()->group_name;

        $getToken = mysqli_query ($con,"SELECT pushnotificationToken FROM candid_database.user_name where `id_user_name` = '$id_user_name'")or die(mysqli_error($con));

        $pushnotificationToken = $getToken->fetch_object()->pushnotificationToken;

    $text_status_new = "Tap to check it out";

        $notification = array (
            "title" => $topic,
            "body" => $text_status_new,
            "click_action" => "in.voiceme.app.voiceme.CHAT",
            "sound" => true,
            "Id" => $id_conversation_reply,
            "chatText" => $chatText,
            "chatImage" => $chatImage,
            "RANDOM_RECEIVER_USERNAME" => $RANDOM_RECEIVER_USERNAME,
            "RANDOM_RECEIVER_AVATAR" => $avatar_url,
            "RANDOM_RECEIVER_ID" => $RANDOM_RECEIVER_ID,
            "POSTID" => $post_id,
            "RECEIVER_USERID" => $id_user_name,
            "SENDER_USERID" => $RECEIVER_USERID,
            "tag" => "CHAT"."_".$post_id
        );

        $textNotification = array
        (
            'chat' 	=> $notification
        );

        $notification_data = array (
            "notification_type" => "post",
            "event_type" => "post_like",
            "postId" => $post_id
        );

        // live code
          if ($id_user_name != $RECEIVER_USERID){

              $fcm_result = sendFCM($pushnotificationToken , $notification, $textNotification);
          }

       // $fcm_result = sendFCM($pushnotificationToken , $notification, $textNotification);

        $resp = array('userId' => $text_status_new);

    return json_encode($resp);

}

