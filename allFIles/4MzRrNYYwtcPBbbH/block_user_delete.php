<?php
require "init_new_config.php";


$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";
$blocked_id = isset($_POST['blocked_id']) ? mysqli_real_escape_string($con, $_POST['blocked_id']) : "";

$check_request = mysqli_query($con, "select * from `block_user` where `user_id` = '$user_id' and `blocked_id` = '$blocked_id'");

if (mysqli_num_rows($check_request) > 0) {
    $sql = mysqli_query($con, "DELETE FROM `block_user` WHERE `user_id`='$user_id' AND `blocked_id` ='$blocked_id' LIMIT 1");
    $resp = array("status" => 0, "msg" => "UnBlocked Successfully");
} else {

    $resp = array("status" => 1, "msg" => "User Not Blocked");
}


?>
