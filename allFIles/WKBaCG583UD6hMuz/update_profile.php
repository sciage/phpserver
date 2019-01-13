<?php

require "init_new_config.php";

$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$avatar_url = isset($_POST['avatar_url']) ? mysqli_real_escape_string($con, $_POST['avatar_url']) : "";
$user_nick_name = isset($_POST['user_nick_name']) ? mysqli_real_escape_string($con, $_POST['user_nick_name']) : "";
$age = isset($_POST['age']) ? mysqli_real_escape_string($con, $_POST['age']) : "";
$gender = isset($_POST['gender']) ? mysqli_real_escape_string($con, $_POST['gender']) : "";
$about_me = isset($_POST['about_me']) ? mysqli_real_escape_string($con, $_POST['about_me']) : "";

if (!empty($id_user_name)) {

    $sql = mysqli_prepare($con, "UPDATE `user_name` SET `user_nick_name`=?,`avatar_pics`=?,
			`user_date_of_birth`=?, `gender`=?, `about_me`=? WHERE `id_user_name`=? LIMIT 1");

    if ($sql) {
        mysqli_stmt_bind_param($sql, "ssssss", $user_nick_name, $avatar_url, $age, $gender, $about_me, $id_user_name);
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
