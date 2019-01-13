<?php

require "init_new_config.php";


if (empty($_POST['user_id']) || empty($_POST['group_ids'])) {
    echo '{"status":"Invalid request"}';
    die();
}

$date = date_create();
$post_date = date_timestamp_get($date) * 1000;
$group_ids = explode(",", $_POST['group_ids']);

// Clean params for insertion
$user_id = intval($_POST['user_id']);
$group_id = 0;

// Prepare insert query
//mysqli_autocommit($con, FALSE);
//mysqli_begin_transaction($con, MYSQLI_TRANS_START_READ_WRITE);

$sql = mysqli_prepare($con, "INSERT INTO `groups_user`(`group_id`, `id_user_name`, `created_at`) VALUES (?,?,?)");
mysqli_stmt_bind_param($sql, "iis", $group_id, $user_id, $post_date);

// Make the user join each group in the passed list
$successful_queries = 0;
if ($sql) {
    foreach ($group_ids as $key => $group_id) {

        // Check valid groupId
        if (is_numeric($group_id)) {

            $group_id = intval($group_id);

            // Insert the record and increment number of successful inserts
            if (mysqli_stmt_execute($sql)) {
                $successful_queries++;
            }
        }

    }

  

    $resp = array("status" => 1, "success" => true, "count_groups_requested" => count($group_ids), "count_groups_joined" => $successful_queries);
} else {
    $json = array("status" => 0, "message" => "Some error occured while joining groups");
}

die(json_encode($resp));

?>
