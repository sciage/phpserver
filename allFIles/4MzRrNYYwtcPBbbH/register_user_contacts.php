<?php
//header("Content-Type : application/json");

require "init_new_config.php";

$id_user_name = isset($_REQUEST['id_user_name']) ? mysqli_real_escape_string($con, $_REQUEST['id_user_name']) : "";
$contacts = isset($_REQUEST['contacts']) ? mysqli_real_escape_string($con, $_REQUEST['contacts']) : "";
$contacts = explode(',', $contacts);
$givenContact = isset($_REQUEST['givenContact']) ? mysqli_real_escape_string($con, $_REQUEST['givenContact']) : "";

$q = $con->prepare("SELECT * FROM user_contacts WHERE `id_user_name`=?");

$q->bind_param("i", intval($id_user_name));
$q->execute();
$q->store_result();

/* DELETE OLDER CONTACTS IF ALREADY PRESENT */
if ($q->num_rows > 0) {
    mysqli_query($con,"SET SQL_SAFE_UPDATES = 0");
    $q = $con->prepare("DELETE FROM user_contacts WHERE `id_user_name`=?");
    $q->bind_param("i", intval($id_user_name));
    $q->execute();
    mysqli_query($con,"SET SQL_SAFE_UPDATES = 1");
}


$q = $con->prepare("INSERT INTO user_contacts VALUES(NULL,?,?)");

$affected_rows = 0;
foreach ($contacts as $number) {
    $q->bind_param("ii", intval($id_user_name), intval($number));
    $q->execute();

    $affected_rows += $q->affected_rows;

}

$sqlupdate = mysqli_query ($con, "UPDATE `user_name` SET `givenContact`='$givenContact'  WHERE `id_user_name`='$id_user_name'");
$resp = array("inserted_rows" => $affected_rows);
die(json_encode($resp));
?>
