<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 07/10/18
 * Time: 10:14 AM
 */

require "init_new_config.php";

$id_conversation = isset($_GET['id_conversation']) ? mysqli_real_escape_string($con, $_GET['id_conversation']) : "";
$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";
$username = isset($_GET['username']) ? mysqli_real_escape_string($con, $_GET['username']) : "";
$avatar_url = isset($_GET['avatar_url']) ? mysqli_real_escape_string($con, $_GET['avatar_url']) : "";


$checkConversation = mysqli_query($con, "SELECT senderId, senderRandom, receiverId, receiverRandom FROM candid_database.conversation where id_conversation = '$id_conversation'   ");

if (mysqli_num_rows($checkConversation) > 0) {
    while ($row = mysqli_fetch_assoc($checkConversation)) {

        $senderId = $row['senderId'];
        $senderRandom = $row['senderRandom'];
        $receiverId = $row['receiverId'];
        $receiverRandom = $row['receiverRandom'];
    }
}

$insertCustomName = mysqli_query ($con, " INSERT INTO `user_name_chat_random` (`username`, `avatar_url`) VALUES ('".$username."', '".$avatar_url."')");
if($insertCustomName){
    // Obtain last inserted id
    $last_id = mysqli_insert_id($con);

} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
}

if ($senderId == $user_id){
    // $senderRandom needs to change
    $sql = mysqli_query ($con, "UPDATE `candid_database`.`conversation` SET `senderRandom` = '$last_id' WHERE (`id_conversation` = '$id_conversation')");
    mysqli_query($con,"SET SQL_SAFE_UPDATES = 0" );

    $sql = mysqli_query ($con, "UPDATE `candid_database`.`conversation_reply` SET `id_user_name_random` = '$last_id' WHERE (`id_conversation` = '$id_conversation' and id_user_name = '$user_id')");

    mysqli_query($con,"SET SQL_SAFE_UPDATES = 1" );

} else {
    // $receiverRandom needs to change
    $sql = mysqli_query ($con, "UPDATE `candid_database`.`conversation` SET `receiverRandom` = '$last_id' WHERE (`id_conversation` = '$id_conversation'");

    mysqli_query($con,"SET SQL_SAFE_UPDATES = 0" );

    $sql = mysqli_query ($con, "UPDATE `candid_database`.`conversation_reply` SET `id_user_name_random` = '$last_id' WHERE (`id_conversation` = '$id_conversation' and id_user_name = '$user_id')");

    mysqli_query($con,"SET SQL_SAFE_UPDATES = 1" );


}

$resp = array('success' => true);

die(json_encode($resp));


// Close connection
?>