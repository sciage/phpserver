<?php

require "init_new_config.php";

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "1";

// $checkToken = mysqli_query($con, "SELECT `user_token` FROM `user_name` where `id_user_name` = '" . $user_id . "'");
// $row = mysqli_fetch_array($checkToken);


// if ($row['user_token'] == $token) {
    
    $json = array();

    $query = "SELECT id_categories FROM candid_database.group_category_join where id_user_name = '". $user_id ."'  ";

    $q = mysqli_query($con, $query);
    while($row = mysqli_fetch_assoc($q)){
        $json[] = $row;
    }

    die(json_encode($json));

// } else {
//     echo '{"status":"Invalid request"}';
// 	die();
// }


?>
