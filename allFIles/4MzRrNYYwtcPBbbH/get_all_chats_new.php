<?php
require_once("init_new_config.php");

$userId = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";

//$limit_clause = '';

/* if( isset($_GET['limit']) && isset($_GET['page']) ){
     $limit=intval($_GET['limit']);
     $page=intval($_GET['page']);

     if($limit>0){
         $limit_clause=" LIMIT ".$page*$limit.",".$limit;
     }
 }*/

/* $page1=  isset($_GET['page']) ? $_GET['page'] : '';
if ($page1 != '') {
    $page = $page1;
    $limit = 10;
    $left = $page * $limit - $limit;
    $limit_clause = " LIMIT " . $left . "," . $limit;
} else {
    $limit = 10;
    $page = 1;
    $limit_clause = " LIMIT 0 ," . $limit;

}
*/


    $stmt="(SELECT Messages.messageId as Id, 
    Messages.id_posts as id_posts,
Messages.chatText as Text,Messages.time as CreatedAt, senderUser.id_user_name as senderUser_id,
Messages.custom_user_name as custom_user_name,

     (SELECT username FROM user_name_chat_random where id_user_name_random = receiverAnonymous) as senderUser_Name,
                (SELECT avatar_url FROM user_name_chat_random where id_user_name_random = receiverAnonymous) as senderUser_Avatar,  
    (SELECT username FROM user_name_chat_random where id_user_name_random = senderAnonymous) as partnerUser_Name,
                (SELECT avatar_url FROM user_name_chat_random where id_user_name_random = senderAnonymous) as partnerUser_Avatar,
                Messages.receiverAnonymous as receiverAnonymous,
                Messages.senderAnonymous as senderAnonymous,
    senderUser.onlineStatus as senderUser_isOnline, partnerUser.id_user_name as partnerUser_id,
    partnerUser.onlineStatus as partnerUser_isOnline FROM ( SELECT MAX(allMessages.messageId) as messageId,
        allMessages.other_party, MAX(allMessages.senderId) as senderId FROM (SELECT `messageId`,`senderId` 
            as other_party,`senderId`,`time` from chat_messages WHERE `receiverId`='".$userId."' UNION SELECT `messageId`, 
            `receiverId` as other_party,`senderId`,`time` from chat_messages WHERE `senderId`= '".$userId."' ) 
            allMessages GROUP BY other_party ) dialogs LEFT JOIN chat_messages Messages ON 
            Messages.messageId = dialogs.messageId LEFT JOIN user_name senderUser 
            ON dialogs.senderId = senderUser.id_user_name LEFT JOIN user_name partnerUser 
            ON dialogs.other_party = partnerUser.id_user_name ORDER BY Messages.time DESC )" ; /*. $limit_clause; */

    $q=mysqli_query($con,$stmt);

    $json=array();
    while( $row= mysqli_fetch_assoc($q) ){

        $sender_user=array(
            "Id"=> $row['senderUser_id'],
            "Name"=> $row['senderUser_Name'],
            "id_posts"=> $row['id_posts'],
            "Avatar"=> $row['senderUser_Avatar'],
            "custom_user_name"=> $row['custom_user_name'],
            "senderAnonymous"=> $row['senderAnonymous'],
            "receiverAnonymous"=> $row['receiverAnonymous'],
            "isOnline"=> $row['senderUser_isOnline']
        );

        $message=array(
            "Id"=> $row['Id'],
            "Text"=> $row['Text'],
            "CreatedAt"=> $row['CreatedAt'],
            "User"=> $sender_user
        );

        $partner_user=array(
            "Id"=> $row['partnerUser_id'],
            "Name"=> $row['partnerUser_Name'],
            "Avatar"=> $row['partnerUser_Avatar'],
            "isOnline"=> $row['partnerUser_isOnline']
        );

        $rec=array(
            "User" => $partner_user,
            "Message" => $message
        );

        $json[]=$rec;
    }


/* Output header */
//header('Content-type:application/json');
echo json_encode($json);
?>
