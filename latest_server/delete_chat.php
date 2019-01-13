<?php
//header("Content-Type : application/json");

require "init_new_config.php";
/* FETCH ALL LIKERS OF A POST */

$id_conversation_reply = isset($_GET['id_conversation_reply']) ? mysqli_real_escape_string($con, $_GET['id_conversation_reply']) : "";

    if (!empty($_GET['id_conversation_reply'])) {

        $records = array();

        $q = mysqli_query($con, "DELETE FROM `candid_database`.`conversation_reply` WHERE (`id_conversation_reply` = '$id_conversation_reply')");

        if ($q){
            $json = array("success" => true);
        } else {
            $json = array("success" => false);
        }


        die(json_encode($json));

    }
 else {
    die("invalid");
}

?>
