<?php

require "init_new_config.php";

$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$pushnotificationToken = isset($_POST['pushnotificationToken']) ? mysqli_real_escape_string($con, $_POST['pushnotificationToken']) : "";


if (!empty($id_user_name)) {

    $sql = mysqli_prepare($con, "UPDATE `user_name` SET `pushnotificationToken`=?  WHERE `id_user_name`=? LIMIT 1");

    if ($sql) {
        mysqli_stmt_bind_param($sql, "ss", $pushnotificationToken, $id_user_name);
        mysqli_stmt_execute($sql);

        $resp = array('success' => true);

        mysqli_stmt_close($sql);

    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));

}
die('Invalid Request');


//	$sql = mysqli_query($con, "UPDATE `user_name` SET `pushnotificationToken`='$pushnotificationToken'  WHERE `id_user_name`='$id_user_name' LIMIT 1");
?>
