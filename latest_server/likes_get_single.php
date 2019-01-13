<?php
require_once("init_new_config.php");
//require_once("init_new_config_local.php");
//require_once("send_notif.php");

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";
$post_id = isset($_GET['post_id']) ? mysqli_real_escape_string($con, $_GET['post_id']) : "";
$like = isset($_GET['like']) ? mysqli_real_escape_string($con, $_GET['like']) : "0";


    $checkdate = mysqli_query ($con,"SELECT * FROM `feeling_category` WHERE `id_posts`='$post_id' AND `id_user_name`='$user_id'")or die(mysqli_error($con));
    if(mysqli_num_rows($checkdate) > 0){
        $row = mysqli_fetch_row($checkdate);

        if($like == 1){
            $query = "UPDATE `feeling_category` SET `feeling_likes`='$like'";
        }

        if ($like == 0){
            $query = "delete  FROM candid_database.feeling_category ";
        }

        $run = mysqli_query($con,$query."WHERE `id_feeling_category`= $row[0]")or die(mysqli_error($con));
        if($run){
            $json = array("status" => 1, "msg"=>"Success");
        }
    }
    else{
        $sql = mysqli_query ($con,"INSERT INTO `feeling_category`(`id_posts`, `id_user_name`, `feeling_likes`) VALUES ('$post_id','$user_id','$like')") or die(mysqli_error($con));
        //$sql = mysqli_query ($con,"INSERT INTO tbl_follower_contact (user_id,contact) VALUES('$user_id','$post_id')");
        if($sql){
            $json = array("status" => 1, "msg"=>"Success");
        }
    }

/* Output header */
//	header('Content-type:application/json');
echo json_encode($json);
?>
