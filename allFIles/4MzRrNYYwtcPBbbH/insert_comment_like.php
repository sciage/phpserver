<?php

require "init_new_config.php";


$id_post_comment = isset($_POST['id_post_comment']) ? mysqli_real_escape_string($con, $_POST['id_post_comment']) : "";
$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";
$comment_likes = isset($_POST['comment_likes']) ? mysqli_real_escape_string($con, $_POST['comment_likes']) : "";
$post_comment_id = isset($_POST['post_comment_id']) ? mysqli_real_escape_string($con, $_POST['post_comment_id']) : "";
$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";

$checkLike = mysqli_query($con, "SELECT * FROM `post_comments_likes`  where `id_post_comment` = '" . $id_post_comment . "'");

if(mysqli_num_rows($checkLike)>0){
    $q = mysqli_query($con, "delete  from `post_comments_likes` where `id_post_comment` = '" . $id_post_comment . "'");
    if ($q) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }
    die(json_encode($resp));

} else {
    $sql = mysqli_query($con, "INSERT INTO `post_comments_likes` (`id_post_comment`, `id_user_name`, `comment_likes`,  `post_comment_id`)
	VALUES ('$id_post_comment', '$id_user_name', '$comment_likes', '$post_comment_id')") or die(mysqli_error($con));

    $cat_id = mysqli_insert_id($con);

    if ($cat_id) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }
    die(json_encode($resp));

}

   // die(json_encode($resp));

?>
