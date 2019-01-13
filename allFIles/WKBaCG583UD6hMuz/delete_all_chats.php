<?php

require "init_new_config.php";

$senderId = isset($_POST['senderId']) ? mysqli_real_escape_string($con, $_POST['senderId']) : "";
$receiverId = isset($_POST['receiverId']) ? mysqli_real_escape_string($con, $_POST['receiverId']) : "";

if ($_POST['action'] == 'delete') {

    mysqli_query($con,"SET SQL_SAFE_UPDATES = 0");
    $q = $con->prepare("DELETE FROM candid_database.chat_messages where `senderId` = ? and `receiverId`=?");
    $q->bind_param("ss", $senderId, $receiverId);
    $q->execute();
    mysqli_query($con,"SET SQL_SAFE_UPDATES = 1");

    if ($q->affected_rows > 0) {
        echo '{"success": true }';
    } else {
        echo '{"success": false }';
    }
    $q->close();

    die();
}

die('Invalid Request');
?>
