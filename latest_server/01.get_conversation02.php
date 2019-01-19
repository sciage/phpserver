<?php

require "init_new_config_local.php";
$i = isset($_GET['i']) ? mysqli_real_escape_string($con, $_GET['i']) : "16000";


$limit = 17350;
$limit_clause = " LIMIT " . $i . "," . $limit;

$q = mysqli_query($con, "SELECT senderId,receiverId FROM candid_database.conversation" . $limit_clause);

$records = array();

while ($row= mysqli_fetch_assoc($q)) {
    $records[] = array(
        $first = $row['senderId'],
        $second = $row['receiverId']
    );

    mysqli_query($con,"SET SQL_SAFE_UPDATES = 0");


    $id_conversationd = mysqli_query($con, "SELECT id_conversation FROM candid_database.conversation where senderId = $second and receiverId = $first");

    $id_conversation = $id_conversationd->fetch_object()->id_conversation;

    if ($id_conversation != null){
        $delete = mysqli_query($con, "DELETE FROM `candid_database`.`conversation` WHERE (`id_conversation` = '$id_conversation')");
        break;
    }

    mysqli_free_result($result);


    $i++;
}

$page = $_SERVER['PHP_SELF'];
$sec = "0";
header("Refresh: $sec; url=$page?i=$i");




?>
