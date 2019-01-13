<?php
//header("Content-Type : application/json");

require "init_new_config.php";

/* FETCH ALL HAShTAGS */

$records = array();

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";
$token = isset($_GET['token']) ? mysqli_real_escape_string($con, $_GET['token']) : "1";

$checkToken = mysqli_query($con, "SELECT `user_token` FROM `user_name` where `id_user_name` = '" . $user_id . "'      ");

$row = mysqli_fetch_array($checkToken);

/* FETCH ALL LIKERS OF A POST */
if ($row['user_token'] == $token) {
    $q = mysqli_query($con, "SELECT * FROM specific_post ");
    while ($row = mysqli_fetch_row($q)) {
        $records[] = array(
            "post_id" => $row[1],
            "id" => $row[0]
        );
    }

    die(json_encode($records));
} else {
    die("invalid");
}


?>
