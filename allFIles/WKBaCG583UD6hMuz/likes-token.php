<?php
require_once("init_new_config.php");

$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";
$post_id = isset($_POST['post_id']) ? mysqli_real_escape_string($con, $_POST['post_id']) : "";
$like = isset($_POST['like']) ? mysqli_real_escape_string($con, $_POST['like']) : "";
$hug = isset($_POST['hug']) ? mysqli_real_escape_string($con, $_POST['hug']) : "";
$same = isset($_POST['same']) ? mysqli_real_escape_string($con, $_POST['same']) : "";
$audio = isset($_POST['audio']) ? mysqli_real_escape_string($con, $_POST['audio']) : "";

$token = isset($_POST['token']) ? mysqli_real_escape_string($con, $_POST['token']) : "1";

$checkToken = mysqli_query($con, "SELECT `user_token` FROM `user_name` where `id_user_name` = '" . $user_id . "'");

$row = mysqli_fetch_array($checkToken);

/* FETCH ALL LIKERS OF A POST */
if ($row['user_token'] == $token) {

    $checkdate = mysqli_query ($con,"SELECT * FROM `feeling_category` WHERE `id_posts`='$post_id' AND `id_user_name`='$user_id'")or die(mysqli_error($con));
    if(mysqli_num_rows($checkdate) > 0){
        if($like == 1){
            $query = "UPDATE `feeling_category` SET `feeling_likes`='$like'";
        }
        elseif($hug == 1){
            $query = "UPDATE `feeling_category` SET `feeling_hug`='$hug'";
        }
        elseif($same == 1){
            $query = "UPDATE `feeling_category` SET `feeling_same`='$same'";
        }
        elseif($audio == 1){
            $query = "UPDATE `feeling_category` SET `audio_listen`='$audio'";
        }
        $run = mysqli_query($con,$query."WHERE `id_posts`='$post_id' AND `id_user_name`='$user_id'")or die(mysqli_error($con));
        if($run){
            $json = array("status" => 1, "msg"=>"Success");
        }
    }
    else{
        $sql = mysqli_query ($con,"INSERT INTO `feeling_category`(`id_posts`, `id_user_name`, `feeling_likes`, `feeling_same`, `feeling_hug`, `audio_listen`) VALUES ('$post_id','$user_id','$like','$same','$hug','$audio')") or die(mysqli_error($con));
        //$sql = mysqli_query ($con,"INSERT INTO tbl_follower_contact (user_id,contact) VALUES('$user_id','$post_id')");
        if($sql){
            $json = array("status" => 1, "msg"=>"Success");
        }
    }
} else {
    $json = array("status" => 0, "msg" => "Request method not accepted");
}
/* Output header */
//	header('Content-type:application/json');
echo json_encode($json);
?>
