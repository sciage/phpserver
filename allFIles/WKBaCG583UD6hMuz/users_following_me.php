<?php
/**
 * Created by PhpStorm.
 * User: harishpc
 * Date: 9/16/2017
 * Time: 11:33 PM
 */

require_once("init_new_config.php");

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";
$token = isset($_GET['token']) ? mysqli_real_escape_string($con, $_GET['token']) : "1";
$follower_id = isset($_GET['follower_id']) ? mysqli_real_escape_string($con, $_GET['follower_id']) : "";


$checkToken = mysqli_query($con, "SELECT `user_token` FROM `user_name` where `id_user_name` = '" . $user_id . "'");

$row = mysqli_fetch_array($checkToken);

// following the given user

if ($row['user_token'] == $token){
    $q = mysqli_query($con, "SELECT id_user_name,
                                user_nick_name,
                                avatar_pics FROM user_name
                                WHERE id_user_name IN
                                ( SELECT user_id  from `tbl_follower`
                                where `followers` = '" . $follower_id . "' ) ");


    $records = array();
    while ($row = mysqli_fetch_assoc($q)) {
        $records[] = $row;
    }

    $json = array("status" => 1, "data" => $records);
    echo json_encode($json);
} else {
    echo "invalid";
}


?>



