<?php
require_once("init_new_config.php");

$userId = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";

$avatar_url = array("0", "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpqAa1sF?generation=1532379768165237&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpSrs3Gy?generation=1532379794741791&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpDy9oyw?generation=1532379814001979&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpDEeRud?generation=1532379838398229&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/php3Spn7T?generation=1532379864372444&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpDK6wzU?generation=1532379892399225&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpb5SXku?generation=1532379916749454&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpFtgnXu?generation=1532379953100253&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpVuO8Bc?generation=1532379971403131&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpJUIRDF?generation=1532379990501226&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpm5E97E?generation=1532380009317681&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpw3ww4E?generation=1532380027251784&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpYpfSq2?generation=1532380053786434&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/php2ezZdB?generation=1532380085990678&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpaRP5Du?generation=1532380109603233&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpWdLmj4?generation=1532380130694700&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/php3ARtfu?generation=1532380150351258&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpRzwQm5?generation=1532380169511197&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpkQTqcW?generation=1532380189790217&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/php0u6fy2?generation=1532380246894858&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpXGERwQ?generation=1532380266540802&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpZlzD7H?generation=1532380294936084&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpWcsZYi?generation=1532380317942682&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpz6x9zk?generation=1532380339728372&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpeSCavq?generation=1532380361316492&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpJGDphn?generation=1532380381038698&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpv904YE?generation=1532380401806886&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/php3xoXRt?generation=1532380422000583&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpE6t6y5?generation=1532380441432533&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phplFU6nG?generation=1532380463131655&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpcZzqpM?generation=1532380483342558&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpgQEvMu?generation=1532380504788067&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpHaE3XK?generation=1532380545581237&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpXnOacq?generation=1532380565888834&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpzHUO54?generation=1532380584585861&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/php4Far2V?generation=1532380603868085&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/php1VIFMj?generation=1532380621455946&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpaXLq2i?generation=1532380647162465&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpF53w3h?generation=1532380670302387&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpvd4LwY?generation=1532380689775991&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpzmDmCq?generation=1532380706619566&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpB4vgjv?generation=1532380727851207&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phphsjIth?generation=1532380747396009&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phperS0ND?generation=1532380765645495&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpjgOh5F?generation=1532380787051326&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpwshhq4?generation=1532380807120636&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpDeF1a4?generation=1532380824969086&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpgxXtqM?generation=1532380841601586&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpXewTHg?generation=1532380876017851&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phptA71TZ?generation=1532380894120142&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpvHdL7v?generation=1532380910962779&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpGadGN7?generation=1532380933516180&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpDxhrao?generation=1532380966780455&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpKbMzav?generation=1532380986658133&alt=media",
    "https://www.googleapis.com/download/storage/v1/b/voiceme-file-bucket/o/phpfkEBqL?generation=1532381053334609&alt=media"
);

$avatar_name = array("0", "Alien_Almonds",
    "Baby_Beetroot",
    "Baked_Beans",
    "Bloody_Pomegranate",
    "Boiled_Rice",
    "Bold_Blackberry",
    "Bold_Brinjal",
    "Brave_Ray",
    "Calm_Cat",
    "Catastrophic_Cactus",
    "Colourful_Butterfly",
    "Crispy_Corn",
    "Crunchy_Apple",
    "Cunning_Pineapple",
    "Cute_Cabbage",
    "Dashing_Daffodils",
    "Diligent_Urchin",
    "Exuberant_Raptor",
    "Friendly_Badger",
    "Gentle_Chicken",
    "Grinded_Spices",
    "Grounded_Carrot",
    "Grounded_Turtle",
    "Herd_of_Grapes",
    "Hidden_Cauliflower",
    "Intutive_Hedgehog",
    "Jelly_Jasmine",
    "Little_Lily",
    "Lovable_Lychee",
    "Lovely_Strawberry",
    "My_Mint",
    "Nosey_Rabbit",
    "Nosey_Toad",
    "Old_Onion",
    "Persistent_Wolf",
    "Plump_Pumpkin",
    "Red_Rose",
    "Rich_Leopard",
    "Rich_Radish,",
    "Ripe_Raisin",
    "Sexy_Banana",
    "Sincere_Reindeer",
    "Sleep_Peas",
    "Strong_Spinach",
    "Sweet_Potato",
    "Sweet_Sunflower",
    "Tangy_Lemon",
    "Tasty_Teak",
    "Tipsy_Tamarind",
    "Tricky_Elephant",
    "Tricky_Pig",
    "Trustful_Tulip",
    "Watery_Melon",
    "Wild_Apricot",
    "Citrus_Orange"
);

$limit_clause = '';

/* if( isset($_GET['limit']) && isset($_GET['page']) ){
     $limit=intval($_GET['limit']);
     $page=intval($_GET['page']);

     if($limit>0){
         $limit_clause=" LIMIT ".$page*$limit.",".$limit;
     }
 }*/

$page1=  isset($_GET['page']) ? $_GET['page'] : '';
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


    $stmt="(SELECT Messages.messageId as Id, 
    Messages.id_posts as id_posts,
Messages.chatText as Text,Messages.time as CreatedAt, senderUser.id_user_name as senderUser_id,
Messages.custom_user_name as custom_user_name,
   
                Messages.receiverAnonymous as receiverAnonymous,
                Messages.senderAnonymous as senderAnonymous,
                '".$avatar_name[22]."' as senderUser_Name,
'".$avatar_url[22]."' as senderUser_Avatar,
'".$avatar_name[23]."' as partnerUser_Name,
'".$avatar_url[23]."' as partnerUser_Avatar,

    
    senderUser.onlineStatus as senderUser_isOnline, partnerUser.id_user_name as partnerUser_id,


    partnerUser.onlineStatus as partnerUser_isOnline FROM ( SELECT MAX(allMessages.messageId) as messageId,
        allMessages.other_party, MAX(allMessages.senderId) as senderId FROM (SELECT `messageId`,`senderId` 
            as other_party,`senderId`,`time` from chat_messages WHERE `receiverId`='".$userId."' UNION SELECT `messageId`, 
            `receiverId` as other_party,`senderId`,`time` from chat_messages WHERE `senderId`= '".$userId."' ) 
            allMessages GROUP BY other_party ) dialogs LEFT JOIN chat_messages Messages ON 
            Messages.messageId = dialogs.messageId LEFT JOIN user_name senderUser 
            ON dialogs.senderId = senderUser.id_user_name LEFT JOIN user_name partnerUser 
            ON dialogs.other_party = partnerUser.id_user_name ORDER BY Messages.time DESC )" . $limit_clause;

    $q=mysqli_query($con,$stmt);

    /* (SELECT username FROM user_name_random where id_user_name_random = receiverAnonymous) as senderUser_Name,
                (SELECT avatar_url FROM user_name_random where id_user_name_random = receiverAnonymous) as senderUser_Avatar,
    (SELECT username FROM user_name_random where id_user_name_random = senderAnonymous) as partnerUser_Name,
                (SELECT avatar_url FROM user_name_random where id_user_name_random = senderAnonymous) as partnerUser_Avatar, */

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
