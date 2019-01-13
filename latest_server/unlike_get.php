<?php
require_once("init_new_config.php");

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";
$post_id = isset($_GET['post_id']) ? mysqli_real_escape_string($con, $_GET['post_id']) : "";
$like = isset($_GET['like']) ? mysqli_real_escape_string($con, $_GET['like']) : "";
$hug = isset($_GET['hug']) ? mysqli_real_escape_string($con, $_GET['hug']) : "";
$same = isset($_GET['same']) ? mysqli_real_escape_string($con, $_GET['same']) : "";
$audio = isset($_GET['audio']) ? mysqli_real_escape_string($con, $_GET['audio']) : "";

$checkdate = mysqli_query($con, "SELECT id_feeling_category FROM `feeling_category` WHERE `id_posts`='$post_id' AND `id_user_name`='$user_id'") or die(mysqli_error($con));
if (mysqli_num_rows($checkdate) > 0) {
    $row = mysqli_fetch_row($checkdate);

    if ($like == 0) {
        $query = "UPDATE `feeling_category` SET `feeling_likes`='$like'";
    } elseif ($hug == 0) {
        $query = "UPDATE `feeling_category` SET `feeling_hug`='$hug'";
    } elseif ($same == 0) {
        $query = "UPDATE `feeling_category` SET `feeling_same`='$same'";
    } elseif ($audio == 0) {
        $query = "UPDATE `feeling_category` SET `audio_listen`='$audio'";
    }
    $run = mysqli_query($con,$query."WHERE `id_feeling_category`= $row[0]")or die(mysqli_error($con));
    if ($run) {
        $json = array("status" => 1, "msg" => "Success");
    }
}
/* Output header */
//header('Content-type:application/json');
echo json_encode($json);
?>
