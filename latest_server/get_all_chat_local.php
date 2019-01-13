<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 06/01/19
 * Time: 7:14 PM
 */

require "init_new_config.php";

$user_one=mysqli_real_escape_string($con,$_GET['user_one']);
$user_two=mysqli_real_escape_string($con,$_GET['user_two']);
$id_posts=mysqli_real_escape_string($con,$_GET['id_posts']); // receiver ID


if($user_one!=$user_two) {
    $checkConversation = mysqli_query($con, "SELECT id_conversation FROM conversation WHERE 
	((senderId='$user_one' and receiverId='$user_two') or 
    (senderId='$user_two' and receiverId='$user_one')) AND id_posts = '$id_posts'   ");


    $json=array();

    if (mysqli_num_rows($checkConversation) > 0) {

        $id_conversation  = $checkConversation->fetch_object()->id_conversation;

        mysqli_query($con,"SET SQL_SAFE_UPDATES = 0" );

        $sql = mysqli_query ($con, "UPDATE `candid_database`.`conversation_reply` SET `unread` = '0' WHERE `id_conversation` = '$id_conversation' and id_user_name != '$user_one'");

        mysqli_query($con,"SET SQL_SAFE_UPDATES = 1" );



        $query= mysqli_query($con, "SELECT R.id_conversation_reply, R.id_user_name_random, U.avatar_url, R.chatText, R.id_user_name, R.chatImage, U.username, R.time FROM user_name_chat_random U, conversation_reply R 
	WHERE R.id_user_name_random=U.id_user_name_random and R.id_conversation='$id_conversation' ORDER BY R.id_conversation_reply asc LIMIT 20");


        while($row = mysqli_fetch_assoc($query)){
            $json[] = array(
                "id"=> $row['id_conversation_reply'],
                "text"=> $row['chatText'] ,
                "postId"=> $id_posts ,
                "image"=>  array(
                    "url"=> $row['chatImage']
                ),
                "user"=> array(
                    "id"=> $row['id_user_name'],
                    "randomId"=> $row['id_user_name_random'],
                    "name"=> $row['username'],
                    "avatar"=> $row['avatar_url'],
                    "online"=> false
                ),
                "createdAt"=> $row['time']
            );
        }

    }
    die(json_encode($json));

}


?>