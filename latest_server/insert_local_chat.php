<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 06/01/19
 * Time: 11:47 AM
 */

require "init_new_config.php";
require_once("update_user_token.php");
require_once("send_notification_receiver_owner.php");

$user_one = isset($_POST['user_one']) ? mysqli_real_escape_string($con, $_POST['user_one']) : ""; // sender ID
$user_two = isset($_POST['user_two']) ? mysqli_real_escape_string($con, $_POST['user_two']) : ""; // receiver ID
$id_posts = isset($_POST['id_posts']) ? mysqli_real_escape_string($con, $_POST['id_posts']) : "";
$token = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : "insert_local_chat";


$chatText = isset($_POST['chatText']) ? mysqli_real_escape_string($con, $_POST['chatText']) : ""; // sender ID
$chatImage = isset($_POST['chatImage']) ? mysqli_real_escape_string($con, $_POST['chatImage']) : ""; // sender ID
$random_user_two = isset($_POST['random_user_two']) ? mysqli_real_escape_string($con, $_POST['random_user_two']) : ""; // sender ID

// FIrst userID is generated.
$random_user_one = rand(1,120);

$date = date_create();
$post_date = date_timestamp_get($date) * 1000;

$q = mysqli_query($con, "SELECT `user_id` FROM `block_user` where blocked_id='" . $user_one . "' and user_id='" . $user_two . "'");


if (mysqli_num_rows($q) > 0) {
    $resp = array('success' => false, 'blocked' => true);
    die(json_encode($resp));

} else {
    if($user_one!=$user_two)
    {
        $checkConversation02 = mysqli_query($con, "SELECT * FROM conversation WHERE 
	((senderId='$user_one' and receiverId='$user_two') or 
    (senderId='$user_two' and receiverId='$user_one')) AND id_posts = '$id_posts'  ");


        if(mysqli_num_rows($checkConversation02)>0) {

            while ($row = mysqli_fetch_assoc($checkConversation02)) {
                $senderId = $row['senderId'];
                $senderRandom = $row['senderRandom'];
                $receiverId = $row['receiverId'];
                $receiverRandom = $row['receiverRandom'];
                $id_conversation = $row['id_conversation'];
            }

            $updateUser = update_user_token($user_one, $token);

            $topic = "Someone sent you a Private Message";

            if ($senderId == $user_one){ // $user_one is user who is sending data. we need other user randomID
                $randomUsersIdOne = $receiverRandom; // other user random ID

                $getreceiverData = mysqli_query ($con,"SELECT username, avatar_url FROM candid_database.user_name_chat_random where id_user_name_random = '$randomUsersIdOne'")or die(mysqli_error($con));

                if (mysqli_num_rows($getreceiverData) > 0) {
                    while ($row = mysqli_fetch_assoc($getreceiverData)) {
                        $randomusername = $row['username'];
                        $avatar_url = $row['avatar_url'];
                    }
                }


            } else if ($receiverId = $user_one){
                $randomUsersIdOne = $senderRandom; // if we are receiverId, then senderRandom is other user

                $getreceiverData = mysqli_query ($con,"SELECT username, avatar_url FROM candid_database.user_name_chat_random where id_user_name_random = '$randomUsersIdOne'")or die(mysqli_error($con));

                if (mysqli_num_rows($getreceiverData) > 0) {
                    while ($row = mysqli_fetch_assoc($getreceiverData)) {
                        $randomusername = $row['username'];
                        $avatar_url = $row['avatar_url'];
                    }
                }

            }

            // todo here you need to enter random username and random ID of second user
            //$chatText, $chatImage, $post_id, $topic, $id_user_name, $RANDOM_RECEIVER_USERNAME, $RANDOM_RECEIVER_ID, $RECEIVER_USERID

            $id_conversation_reply = insertChatReply($id_conversation, $randomUsersIdOne, $chatText, $chatImage, $user_one, $post_date, $con);

            $send_notification_chat_owner = send_notification_to_chat_owner($id_conversation_reply, $chatText, $chatImage, $id_posts, $topic, $user_two, $randomusername, $avatar_url, $randomUsersIdOne,  $user_one);

            if ($id_conversation_reply) {
                $resp = array('success' => true, 'blocked' => false);

            } else {
                $resp = array('success' => false, 'blocked' => false);
            }

            die(json_encode($resp));

        } else {


            $q = mysqli_query($con, "INSERT INTO conversation(`senderId`,`senderRandom`,`receiverId`,`receiverRandom`,`id_posts`,`time`)
	VALUES( '".$user_one."','".$random_user_one."','".$user_two."','".$random_user_two."','".$id_posts."','".$post_date."') ");

            $id_conversation = mysqli_insert_id($con);

            $senderId = $user_one;
            $senderRandom = $random_user_one;
            $receiverId = $user_two;
            $receiverRandom = $random_user_two;

            if ($senderId == $user_one){ // $user_one is user who is sending data. we need other user randomID
                $randomUsersIdOne = $receiverRandom; // other user random ID

                $getreceiverData = mysqli_query ($con,"SELECT username, avatar_url FROM candid_database.user_name_chat_random where id_user_name_random = '$randomUsersIdOne'")or die(mysqli_error($con));


                if (mysqli_num_rows($getreceiverData) > 0) {
                    while ($row = mysqli_fetch_assoc($getreceiverData)) {
                        $randomusername = $row['username'];
                        $avatar_url = $row['avatar_url'];
                    }
                }


            } else if ($receiverId = $user_one){
                $randomUsersIdOne = $senderRandom; // if we are receiverId, then senderRandom is other user

                $getreceiverData = mysqli_query ($con,"SELECT username, avatar_url FROM candid_database.user_name_chat_random where id_user_name_random = '$randomUsersIdOne'")or die(mysqli_error($con));

                if (mysqli_num_rows($getreceiverData) > 0) {
                    while ($row = mysqli_fetch_assoc($getreceiverData)) {
                        $randomusername = $row['username'];
                        $avatar_url = $row['avatar_url'];
                    }
                }



            }

            $topic = "Someone sent you a Private Message";


            //    $id_conversation, $randomUsersIdOne, $chatText, $chatImage, $user_one, $post_date, $con
            $id_conversation_reply = insertChatReply($id_conversation, $random_user_one, $chatText, $chatImage, $user_one, $post_date, $con);

            $send_notification_chat_owner = send_notification_to_chat_owner($id_conversation_reply, $chatText, $chatImage, $id_posts, $topic, $user_two, $randomusername, $avatar_url, $randomUsersIdOne,  $user_one);


            if ($id_conversation_reply) {
                $resp = array('success' => true, 'blocked' => false);

            } else {
                $resp = array('success' => false, 'blocked' => false);
            }

            die(json_encode($resp));
        }
    }
}




function insertChatReply($id_conversation, $randomUsersIdOne, $chatText, $chatImage, $user_one, $post_date, $con){

    $q= mysqli_query($con,"INSERT INTO `conversation_reply` (`chatText`, `chatImage`, `id_user_name`, `id_user_name_random`, `time`, `id_conversation`, `unread`) VALUES 
        ('$chatText','$chatImage','$user_one','$randomUsersIdOne','$post_date','$id_conversation', 1)") or die(mysqli_error($con));

    $id_conversation_reply = mysqli_insert_id($con);

    return $id_conversation_reply;


}




?>