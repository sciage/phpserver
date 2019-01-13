<?php

require_once("init_new_config.php");
require_once("update_user_token.php");
require_once("send_notification_post_owner.php");

/*
$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : ""; // user sending like
$post_id = isset($_POST['post_id']) ? mysqli_real_escape_string($con, $_POST['post_id']) : "";
$like = isset($_POST['like']) ? mysqli_real_escape_string($con, $_POST['like']) : "0";
$token = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : "0"; // user sending like token
*/
$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : ""; // user sending like
$post_id = isset($_POST['post_id']) ? mysqli_real_escape_string($con, $_POST['post_id']) : "";
$like = isset($_POST['like']) ? mysqli_real_escape_string($con, $_POST['like']) : "0";
$token = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : "0";



$checkdate = mysqli_query ($con,"SELECT 'id_feeling_category' FROM `feeling_category` WHERE `id_posts`='$post_id' AND `id_user_name`='$user_id'")or die(mysqli_error($con));
if(mysqli_num_rows($checkdate) > 0){

    $row = mysqli_fetch_row($checkdate);

    if($like == 1){
        $query = "UPDATE `feeling_category` SET `feeling_likes`='$like' ";

        $updateUser = update_user_token($user_id, $token);

        $topic = "Someone Liked your post";

// send notification to post owner
        $send_notification_post_ower = send_notification_to_post_owner($post_id, $topic, $user_id);
    }



    if ($like == 0){
        $query = "delete  FROM candid_database.feeling_category ";
    }

    $run = mysqli_query($con,$query." WHERE `id_feeling_category`= $row[0]")or die(mysqli_error($con));
    if($run){
        $json = array("status" => 1, "msg"=>"Success");
    }
} else{
    $sql = mysqli_query ($con,"INSERT INTO `feeling_category`(`id_posts`, `id_user_name`, `feeling_likes`) VALUES ('$post_id','$user_id','$like')") or die(mysqli_error($con));
    //$sql = mysqli_query ($con,"INSERT INTO tbl_follower_contact (user_id,contact) VALUES('$user_id','$post_id')");
    if($sql){
        $json = array("status" => 1, "msg"=>"Success");

        $updateUser = update_user_token($user_id, $token);

        $topic = "Someone Liked your post";

// send notification to post owner
        $send_notification_post_ower = send_notification_to_post_owner($post_id, $topic, $user_id);
    }
}

// update user token of sending user



 echo json_encode($json);
?>
