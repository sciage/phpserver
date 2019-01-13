<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 21/12/18
 * Time: 2:28 PM
 */

//require_once("init_new_config.php");

//require_once("init_new_config_local.php");
require_once("send_notif.php");

function send_topic_broadcast($post_id, $topic02, $user_id){

    $topic = $topic02;


    $notification = array (
        "title" => $topic,
        "body" => "",
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


        $topic_string = "/topics/" ."POST"."_". $post_id; // "topic/POST_123
     //   $topic_string = "/topics/GLOBAL_TOPIC"; // "topic/POST_123

        $fcm_result = sendFCM($topic_string , $notification, $notification_data);



        //   echo json_encode($fcm_result);


    return json_encode($fcm_result);

}