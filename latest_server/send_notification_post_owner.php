<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 21/12/18
 * Time: 7:35 AM
 */

//require_once("init_new_config.php");
//require_once("init_new_config.php");
require_once("init_new_config.php");
require_once("send_notif.php");
require_once("save_notification.php");



function send_notification_to_post_owner($post_id, $topic, $user_id){
    global $con;


    $getUserId = mysqli_query ($con,"SELECT id_user_name, groups.name as group_name, text_status FROM candid_database.posts LEFT JOIN groups ON  groups.group_id = posts.group_id where id_posts = '$post_id'")or die(mysqli_error($con));

    if (mysqli_num_rows($getUserId) > 0) {
        while($row = mysqli_fetch_assoc($getUserId)){
            $id_user_name = $row['id_user_name'];
            $text_status = $row['text_status'];
     }

        $id_user_name_random = rand(1,120);
        $id_user_name_randomreceiver = rand(1,120);


        $date = date_create();
        $post_date = date_timestamp_get($date) * 1000;

        $status_length = strlen($text_status);

        if ($status_length == 0){
            $text_status_new = "Tap to check it out";
        } else if ($status_length < 40) {
            $text_status_new = $text_status;
        } else {
            $text_status_new =    substr($text_status, 0, 40) . "...";
        }

       // $id_user_name = $getUserId->fetch_object()->id_user_name;
       // $groups_name = $getUserId->fetch_object()->group_name;

    // live code    $getToken = mysqli_query ($con,"SELECT pushnotificationToken FROM candid_database.user_name where `id_user_name` = '$id_user_name'")or die(mysqli_error($con));
        // demo code for testing
        $getToken = mysqli_query ($con,"SELECT pushnotificationToken FROM candid_database.user_name where `id_user_name` = '$id_user_name'")or die(mysqli_error($con));

        $pushnotificationToken = $getToken->fetch_object()->pushnotificationToken;

        $notification = array (
            "title" => $topic,
            "body" => $text_status_new,
            "click_action" => "in.voiceme.app.voiceme.REACTIONS",
            "sound" => "default",
            "priority" => 10,
            "postId" => $post_id,
            "idusername" => "$user_id", // my userid = $user_id, post owner user ID= $id_user_name
            "tag" => "POST"."_".$post_id
        );

        $topic02 =  "Someone Commented in this post";


        $notificationTopic = array (
            "title" => $topic02,
            "body" => $text_status_new,
            "click_action" => "in.voiceme.app.voiceme.REACTIONS",
            "sound" => "default",
            "priority" => 10,
            "postId" => $post_id,
            "idusername" => $user_id, // my userid = $user_id, post owner user ID= $id_user_name
            "tag" => "POST"."_".$post_id
        );

        $textNotification = array
        (
            'text' 	=> $notification
        );

        $textNotificationTopic = array
        (
            'text' 	=> $notificationTopic
        );

        $notification_data = array (
            "notification_type" => "post",
            "event_type" => "post_like",
            "postId" => $post_id
        );

        //$senderId, $receiverId, $notificationText, $time, $post_id, $randomsenderId, $randomreceiverId
        // live code
        if ($user_id != $id_user_name){
            $fcm_result = sendFCM($pushnotificationToken , $notification, $textNotification);
            save_notification($user_id, $id_user_name, $topic, $post_date, $post_id, $id_user_name_random, $id_user_name_randomreceiver);
        }

        $topic_string = "/topics/" ."POST"."_". $post_id; // "topic/POST_123


        // todo testing code below
        $fcm_result_broadcast = sendFCM($topic_string , $notificationTopic, $textNotificationTopic);


        $resp = array('userId' => $id_user_name);


    }
    return json_encode($resp);

}

