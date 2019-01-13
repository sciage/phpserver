<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 21/12/18
 * Time: 7:35 AM
 */

//require_once("init_new_config.php");
require_once("init_new_config.php");

function save_notification($senderId, $receiverId, $notificationText, $time, $post_id, $randomsenderId, $randomreceiverId){
    global $con;




    $q = mysqli_query($con, "INSERT INTO postNotifications(`senderId`, `senderAnonymous`,`receiverId`,`receiverAnonymous`,`postId`, `notificationText`, `time`)
	VALUES( '".$senderId."','".$randomsenderId."','".$receiverId."','".$randomreceiverId."','".$post_id."', '".$notificationText."', '".$time."') ");

    $cat_id = mysqli_insert_id($con);

    if ($cat_id) {
        $resp = array('success' => true, 'id' => $cat_id);
    } else {
        $resp = array('success' => false);
    }

    return json_encode($resp);

}

