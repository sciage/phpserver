<?php

require "init_new_config.php";

$id_user_name = isset($_GET['id_user_name']) ? mysqli_real_escape_string($con, $_GET['id_user_name']) : "";
$gender = isset($_GET['gender']) ? mysqli_real_escape_string($con, $_GET['gender']) : "";

if (!empty($id_user_name)) {

    $sql = mysqli_prepare($con, "UPDATE `user_name` SET `gender`=? WHERE `id_user_name`=? LIMIT 1");

    if ($sql) {
        mysqli_stmt_bind_param($sql, "ss", $gender, $id_user_name);
        mysqli_stmt_execute($sql);

        $resp = array('success' => true);

        mysqli_stmt_close($sql);

    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));

}


die('Invalid Request');
?>
