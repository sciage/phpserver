<?php
//header("Content-Type : application/json");

require "init_new_config.php";
/* FETCH ALL LIKERS OF A POST */

$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";
$messageId = isset($_POST['messageId']) ? mysqli_real_escape_string($con, $_POST['messageId']) : "";

    if (!empty($_POST['messageId'])) {

        $records = array();


        $sql = mysqli_query($con, "SELECT * FROM chat_messages WHERE messageId = '" . $_POST['messageId'] . "'");
        $r1 = mysqli_fetch_array($sql);
        if ($r1['messageId'] != '') {

            $q = mysqli_query($con, "delete  from chat_messages where messageId = '" . $_POST['messageId'] . "'");

        }
        $json = array("status" => 1, "msg" => "Success");


        die(json_encode($json));

    }
 else {
    die("invalid");
}

?>
