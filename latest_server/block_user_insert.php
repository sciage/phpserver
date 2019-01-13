<?php

require "init_new_config_local.php";

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";
$blocked_id = isset($_GET['blocked_id']) ? mysqli_real_escape_string($con, $_GET['blocked_id']) : "";

$check_request = mysqli_query($con, "select * from `block_user` where `user_id` = '$user_id' and `blocked_id` = '$blocked_id'");


if (mysqli_num_rows($check_request) > 0) {
    $resp = array("status" => 0, "msg" => "Already Blocked");
} else {
    $sql = mysqli_query($con, "INSERT INTO block_user (user_id,blocked_id) VALUES('$user_id','$blocked_id')");
    $resp = array("status" => 1, "msg" => "Successfully Blocked");
}

die(json_encode($resp));


?>
