<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 06/01/19
 * Time: 6:17 PM
 */

require "init_new_config.php";

$user_one = mysqli_real_escape_string($con, $_GET['user_one']);


$query = mysqli_query($con, "SELECT c.id_conversation,u.username
                    FROM conversation c, user_name_chat_random u
                    WHERE CASE 
                    WHEN c.senderId = '$user_one'
                    THEN c.receiverRandom = u.id_user_name_random
                    WHEN c.receiverId = '$user_one'
                    THEN c.senderRandom= u.id_user_name_random
                    END 
                    AND ( c.senderId ='$user_one' OR c.receiverId ='$user_one')
                    Order by c.id_conversation DESC Limit 20");


$json=array();


while($row = mysqli_fetch_assoc($query)){

     $id_conversation = $row['id_conversation'];
        $random_name = $row['random_name'];

    $cquery = mysqli_query($con, "SELECT R.id_conversation_reply, conversation.id_posts, 
(Select sum(unread) as unread from conversation_reply where conversation_reply.id_conversation = $id_conversation and conversation_reply.id_user_name != $user_one) as unread,

R.id_user_name, R.time, R.chatText, R.chatImage, 
user_name_chat_random.username, user_name_chat_random.avatar_url, conversation.senderId, conversation.senderRandom, conversation.receiverId, conversation.receiverRandom
        FROM conversation_reply R left join user_name_chat_random on R.id_user_name_random = user_name_chat_random.id_user_name_random
        left join conversation on R.id_conversation = conversation.id_conversation
         WHERE R.id_conversation='$id_conversation' ORDER BY R.id_conversation_reply DESC LIMIT 1");

    while($row01 = mysqli_fetch_assoc($cquery)) {

        $lastmessageSender = $row01['id_user_name'];

        $receiverAvatar = null;
        $receiverUsername = null;



        if ($user_one == $row01['senderId']){
            $senderRandom = $row01['senderRandom'];
            $senderId = $row01['senderId'];
            $receiverId = $row01['receiverId'];
            $receiverRandom = $row01['receiverRandom'];
        } else {
            $senderRandom = $row01['receiverRandom'];
            $senderId = $row01['receiverId'];
            $receiverId = $row01['senderId'];
            $receiverRandom = $row01['senderRandom'];
        }

        $getsenderData = mysqli_query ($con,"SELECT username, avatar_url FROM candid_database.user_name_chat_random where id_user_name_random = '$senderRandom'")or die(mysqli_error($con));

        if (mysqli_num_rows($getsenderData) > 0) {
            while ($row02 = mysqli_fetch_assoc($getsenderData)) {

                $senderArray = array(
                    "id"=> $senderId,
                    "randomId"=> $senderRandom,
                    "name"=> $row02['username'],
                    "avatar"=> $row02['avatar_url'],
                    "online"=> false
                );
            }
        }

            $getreceiverData = mysqli_query ($con,"SELECT username, avatar_url FROM candid_database.user_name_chat_random where id_user_name_random = '$receiverRandom'")or die(mysqli_error($con));

            if (mysqli_num_rows($getreceiverData) > 0) {
                while ($row = mysqli_fetch_assoc($getreceiverData)) {

                    $receiverAvatar = $row['avatar_url'];
                    $receiverUsername = $row['username'];

                    $ReceiverArray = array(
                        "id"=> $receiverId,
                        "randomId"=> $receiverRandom,
                        "name"=> $row['username'],
                        "avatar"=> $row['avatar_url'],
                        "online"=> false

                    );
                }
            }

            $allUserArray= array($senderArray, $ReceiverArray);

        if ($lastmessageSender == $row01['senderId']){
            $lastMessageUser = $senderArray;
        } else {
            $lastMessageUser = $ReceiverArray;

        }


        $json[] = array(
            "id"=> $id_conversation, //id_conversation
            "dialogName"=>  $receiverUsername,
            "dialogPhoto"=> $receiverAvatar,
            "users"=> $allUserArray,
            "lastMessage"=> array(
                "id"=> $row01['id_conversation_reply'],
                "text"=> $row01['chatText'],
                "createdAt"=> $row01['time'],
                "postId"=> $row01['id_posts'],
                "user"=> $lastMessageUser,
                "image"=>  array(
                    "url"=> $row01['chatImage'] == "" ? null : $row01['chatImage']
                )
            ),
            "unreadCount"=> $row01['unread'] != null ? $row01['unread'] : 0

        );
    }

}

echo json_encode($json);

//    return_responce(false, array('Comments'=> array()), "No Comments Found.", $action = "");
die();

?>