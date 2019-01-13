<?php

require "init_new_config.php";


if (empty($_POST['user_id'])) {
    echo '{"status":"Invalid request"}';
    die();
}

// Clean params for insertion
$user_id = intval($_POST['user_id']);
$user_id_second = intval($_POST['user_id_second']);

// Prepare insert query
//mysqli_autocommit($con, FALSE);
//mysqli_begin_transaction($con, MYSQLI_TRANS_START_READ_WRITE);

mysqli_query($con,"SET SQL_SAFE_UPDATES = 0");
mysqli_query($con,"delete FROM candid_database.chat_messages where senderId = '".$user_id."' and receiverId = '".$user_id_second."'; ");
mysqli_query($con,"delete FROM candid_database.chat_messages where receiverId = '".$user_id."' and senderId = '".$user_id_second."';  ");
mysqli_query($con,"SET SQL_SAFE_UPDATES = 1");

$resp = array('success' => true);

die(json_encode($resp));

?>
