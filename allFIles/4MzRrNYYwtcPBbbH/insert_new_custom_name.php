<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 07/10/18
 * Time: 10:14 AM
 */

require "init_new_config.php";

$username = isset($_POST['username']) ? mysqli_real_escape_string($con, $_POST['username']) : "";
$avatar_url = isset($_POST['avatar_url']) ? mysqli_real_escape_string($con, $_POST['avatar_url']) : "";
$senderId = isset($_POST['senderId']) ? mysqli_real_escape_string($con, $_POST['senderId']) : "";
$receiverId = isset($_POST['receiverId']) ? mysqli_real_escape_string($con, $_POST['receiverId']) : "";

$last_id = 0;

// Attempt insert query execution
$sql = "    INSERT INTO `user_name_chat_random` (`username`, `avatar_url`) VALUES ('".$username."', '".$avatar_url."')";
if(mysqli_query($con, $sql)){
    // Obtain last inserted id
    $last_id = mysqli_insert_id($con);
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
}

mysqli_query($con,"SET SQL_SAFE_UPDATES = 0");
mysqli_query($con,"UPDATE chat_messages SET receiverAnonymous='".$last_id."' WHERE senderId = '".$senderId."' and receiverId = '".$receiverId."'; ");
mysqli_query($con,"UPDATE chat_messages SET senderAnonymous='".$last_id."' WHERE senderId = '".$receiverId."' and receiverId = '".$senderId."'; ");
mysqli_query($con,"SET SQL_SAFE_UPDATES = 1");

$resp = array('success' => true);

die(json_encode($resp));


// Close connection
?>