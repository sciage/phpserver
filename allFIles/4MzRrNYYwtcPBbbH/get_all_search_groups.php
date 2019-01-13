<?php

require "init_new_config.php";

$search_word = isset($_GET['search_word']) ? mysqli_real_escape_string($con, $_GET['search_word']) : "1";

// $checkToken = mysqli_query($con, "SELECT `user_token` FROM `user_name` where `id_user_name` = '" . $user_id . "'");
// $row = mysqli_fetch_array($checkToken);


// if ($row['user_token'] == $token) {
    
    $json = array();

    $query = "SELECT * FROM categories LEFT JOIN groups 
    		ON categories.id_categories = groups.id_categories where category LIKE '%$search_word%';";

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
