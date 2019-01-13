<?php

require "init_new_config.php";

$image_url = isset($_POST['image_url']) ? mysqli_real_escape_string($con, $_POST['image_url']) : "";
$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";


    $sql = mysqli_query($con, "UPDATE user_name SET avatar_pics = '" . $image_url . "'
								WHERE id_user_name = '" . $user_id . "' ") or die(mysqli_error($con));

    // $cat_id = mysqli_insert_id($con);

    if ($sql) {
        $resp = array('success' => true);
    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));



?>
