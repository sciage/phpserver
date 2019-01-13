<?php

require "init_new_config_local.php";

$q = mysqli_query($con, "SELECT senderId,receiverId FROM candid_database.conversation");

$records = array();

while ($row = mysqli_fetch_assoc($q)) {
    $records[] = array(
        $first = $row['senderId'],
        $second = $row['receiverId']
    );

    $query = mysqli_query($con, "SELECT * FROM candid_database.conversation where case when senderId = $first then senderId = $first and receiverId = $second end");

    $querySecond = mysqli_query($con, "SELECT * FROM candid_database.conversation where case when senderId = $second then senderId = $second and receiverId = $first end");

if($query->num_rows  > 0){
    while($row = mysqli_fetch_assoc($query)){
        $json[] = array(
            "id_conversation"=> $row['id_conversation'],
            "senderId"=> $row['senderId'] ,
            "senderRandom"=> $row['senderRandom'] ,
            "receiverId"=> $row['receiverId'],
            "receiverRandom"=> $row['receiverRandom'],
            "id_posts"=> $row['id_posts'],
            "time"=> $row['time']
        );
    }

    if($querySecond->num_rows  > 0){
        while($row = mysqli_fetch_assoc($querySecond)){
            $json[] = array(
                "id_conversation"=> $row['id_conversation'],
                "senderId"=> $row['senderId'] ,
                "senderRandom"=> $row['senderRandom'] ,
                "receiverId"=> $row['receiverId'],
                "receiverRandom"=> $row['receiverRandom'],
                "id_posts"=> $row['id_posts'],
                "time"=> $row['time']
            );
        }
    }



}




}

$fp = fopen('final.json', 'w+');
fwrite($fp, json_encode($json));
fclose($fp);
//die(json_encode($json));



?>
