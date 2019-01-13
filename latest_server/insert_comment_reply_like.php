<?php

require "init_new_config.php";
//require_once("init_new_config_local.php");


$id_post_comment_reply = isset($_POST['id_post_comment_reply']) ? mysqli_real_escape_string($con, $_POST['id_post_comment_reply']) : "";
$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$likes = isset($_POST['likes']) ? mysqli_real_escape_string($con, $_POST['likes']) : "";

$checkLike = mysqli_query($con, "SELECT id_post_comment_reply_likes FROM `post_comment_reply_likes`  where `id_post_comment_reply` = '" . $id_post_comment_reply . "' and `id_user_name` = '" . $id_user_name . "'");

if(mysqli_num_rows($checkLike)>0){

    $id_post_comment_reply_likes = $checkLike ->fetch_object()->id_post_comment_reply_likes;

    $q = mysqli_query($con, "delete from `post_comment_reply_likes` where `id_post_comment_reply_likes` = '" . $id_post_comment_reply_likes . "' ");
    if ($q) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }
    die(json_encode($resp));

} else {
    try{
        $sql = mysqli_query($con, "INSERT INTO `post_comment_reply_likes` (`id_post_comment_reply`, `id_user_name`, `likes`) VALUES ('$id_post_comment_reply', '$id_user_name', '$likes')") or die(mysqli_error($con));

    } catch (Exception $e){
        echo print_r($e);
    }

    $cat_id = mysqli_insert_id($con);

    if ($cat_id) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));
}

?>
