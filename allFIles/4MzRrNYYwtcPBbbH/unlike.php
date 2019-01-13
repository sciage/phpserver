<?php
require_once("init_new_config.php");

$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";
$post_id = isset($_POST['post_id']) ? mysqli_real_escape_string($con, $_POST['post_id']) : "";
$like = isset($_POST['like']) ? mysqli_real_escape_string($con, $_POST['like']) : "";
$hug = isset($_POST['hug']) ? mysqli_real_escape_string($con, $_POST['hug']) : "";
$same = isset($_POST['same']) ? mysqli_real_escape_string($con, $_POST['same']) : "";
$audio = isset($_POST['audio']) ? mysqli_real_escape_string($con, $_POST['audio']) : "";

$checkdate = mysqli_query($con, "SELECT * FROM `feeling_category` WHERE `id_posts`='$post_id' AND `id_user_name`='$user_id'") or die(mysqli_error($con));
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
