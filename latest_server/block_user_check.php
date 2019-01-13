<?php

require "init_new_config.php";

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";

$blocked_id = isset($_GET['blocked_id']) ? mysqli_real_escape_string($con, $_GET['blocked_id']) : "";

$token = isset($_GET['token']) ? mysqli_real_escape_string($con, $_GET['token']) : "1";


$q = mysqli_query($con, "SELECT `user_id` FROM `block_user` where blocked_id='" . $blocked_id . "' and user_id='" . $user_id . "'");


if (mysqli_num_rows($q) > 0) {
    $resp = array('success' => true);
} else {
    $resp = array('success' => false);
}

die(json_encode($resp));


?>
