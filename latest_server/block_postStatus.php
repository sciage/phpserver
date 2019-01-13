<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 05/01/19
 * Time: 3:34 AM
 */

require "init_new_config.php";
require_once("bad_words_arrays.php");

function block_post($status){ //$id_device_token
    global $con;
    global $bad_words;

    $keys = explode(" ", $status);
    $wordsCount = count($keys);

    for($i=0;$i<$wordsCount;$i++) {

        if ($wordsCount == $bad_words[i]){

        }
    }


    $getUserId = mysqli_query ($con,"SELECT id_user_name, message FROM candid_database.post_comments where id_post_comments = '$id_post_comments'")or die(mysqli_error($con));

    if (mysqli_num_rows($getUserId) > 0) {
        while($row = mysqli_fetch_assoc($getUserId)){
            $id_user_name = $row['id_user_name'];
            $status_length = $row['message'];
        }


        $date = date_create();
        $post_date = date_timestamp_get($date) * 1000;

        $id_user_name_random = rand(1,120);
        $id_user_name_randomreceiver = rand(1,120);

        save_notification($user_id, $id_user_name, $topic, $post_date, $post_id, $id_user_name_random, $id_user_name_randomreceiver);

        /*   $receiverId = $id_user_name;
           $senderId = $user_id;
           $notificationText = $topic;
           $time = $post_date;
           $post_id = $post_id; */


        // $id_user_name = $getUserId->fetch_object()->id_user_name;
        // $groups_name = $getUserId->fetch_object()->group_name;

        $getToken = mysqli_query ($con,"SELECT pushnotificationToken FROM candid_database.user_name where `id_user_name` = '$id_user_name'")or die(mysqli_error($con));

        $pushnotificationToken = $getToken->fetch_object()->pushnotificationToken;


        if ($status_length == 0){
            $text_status_new = "Tap to check it out";
        } else if ($status_length < 40) {
            $text_status_new = $status_length;
        } else {
            $text_status_new =    substr($status_length, 0, 40) . "...";
        }

        $notification = array (
            "title" => $topic,
            "body" => $text_status_new,
            "click_action" => "in.voiceme.app.voiceme.REACTIONS",
            "sound" => true,
            "postId" => $post_id,
            "tag" => "POST"."_".$post_id
        );

        $textNotification = array
        (
            'text' 	=> $notification
        );

        $notification_data = array (
            "notification_type" => "post",
            "event_type" => "post_like",
            "postId" => $post_id
        );

        // live code
        if ($user_id != $id_user_name){
            $fcm_result = sendFCM($pushnotificationToken , $notification, $notification_data);
        }

        //  $fcm_result = sendFCM($pushnotificationToken , $notification, $notification_data);


        $resp = array('userId' => $getUserId);


    }
    return json_encode($resp);

}



?>