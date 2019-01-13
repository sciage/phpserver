<?php
//	header("Content-Type : application/json");

require "init_new_config.php";

$facebook_id = explode(",", $_POST['facebook_id']);
$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";

$q = $con->prepare("SELECT * FROM facebook_friend_id WHERE `id_user_name`=?");

$q->bind_param("i", intval($id_user_name));
$q->execute();
$q->store_result();

/* DELETE OLDER CONTACTS IF ALREADY PRESENT */
if ($q->num_rows > 0) {
    $q = $con->prepare("DELETE FROM facebook_friend_id WHERE `id_user_name`=?");
    $q->bind_param("i", intval($id_user_name));
    $q->execute();
}


$q = $con->prepare("INSERT INTO facebook_friend_id VALUES(NULL,?,?)");

$affected_rows = 0;
foreach ($facebook_id as $number) {
    $q->bind_param("ii", intval($id_user_name), intval($number));
    $q->execute();

    $affected_rows += $q->affected_rows;

}

$resp = array("inserted_rows" => $affected_rows);
die(json_encode($resp));
?>
