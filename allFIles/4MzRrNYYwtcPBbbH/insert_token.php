<?php

require "init_new_config.php";

$token = rand(8, 10);
$bytes = bin2hex(random_bytes($token));
$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";

$checkToken = mysqli_query($con, "SELECT `user_token` FROM `user_name` where `id_user_name` = '" . $user_id . "'");

$row = mysqli_fetch_array($checkToken);

if(mysqli_num_rows()>0){

        $json = array("status" => 1, "user_token" => "true");

        mysqli_close($con);
        /* Output header */
        die(json_encode($json));


} else {
    // Insert data into data base
    // 																					`about_me`='$about_me', `user_nick_name`='$user_nick_name',
    $sql = mysqli_query($con, "UPDATE `user_name` SET  `user_token`='$bytes' WHERE `id_user_name` ='$user_id' LIMIT 1");

    if($sql){
        $duplicates = mysqli_query($con, "SELECT `user_token` FROM `user_name` WHERE `id_user_name`='$user_id'");

        $row = mysqli_fetch_array($duplicates);

        $json = array("status" => 1, "user_token" => $row['user_token']);

        mysqli_close($con);
        /* Output header */
        die(json_encode($json));
    } else {
        echo "Invalid request";
    }

}

?>
