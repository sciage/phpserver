<?php
// Include config file
require_once("init_new_config.php");

$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";
$registrationToken = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : "";

$user_id_duplicate = mysqli_query($con, "SELECT * FROM `user_name` WHERE `id_user_name` ='$user_id'");

if (mysqli_num_rows($user_id_duplicate) > 0) {
    $sql = mysqli_query($con, "UPDATE `user_name` SET `pushnotificationToken`='$registrationToken' WHERE `id_user_name`='$user_id'");
    if ($sql) {
        $duplicates = mysqli_query($con, "SELECT * FROM user_name WHERE id_user_name='$user_id'");
        $row = mysqli_fetch_array($duplicates);
        $result = array(
            "user_id" => $row['userid']
        );
        $json = array("status" => 1, "info" => $result);
    } else {
        $json = array("status" => 0, "info" => "Query Error");
    }
} else {
    $json = array("status" => 1, "info" => "NO user id");
}

echo json_encode($json);

mysqli_close($con);
/* Output header */
//header('Content-type: application/json');

?>