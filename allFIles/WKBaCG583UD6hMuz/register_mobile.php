<?php

require "init_new_config.php";

$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$phone_number = isset($_POST['phone_number']) ? mysqli_real_escape_string($con, $_POST['phone_number']) : "";

$q = mysqli_query($con, "UPDATE user_name SET phone_number='" . $phone_number . "' WHERE id_user_name='" . $id_user_name . "' LIMIT 1");
if ($q) {
    $resp = array('success' => true);
} else {
    $resp = array('success' => false);
}

die(json_encode($resp));
?>
