<?php

require "init_new_config.php";

$id_conversation = isset($_POST['id_conversation']) ? mysqli_real_escape_string($con, $_POST['id_conversation']) : "";


mysqli_query($con,"SET SQL_SAFE_UPDATES = 0");
mysqli_query($con,"delete FROM candid_database.conversation_reply where id_conversation = '".$id_conversation."' ");
mysqli_query($con,"delete FROM candid_database.conversation where id_conversation = '".$id_conversation."';  ");
mysqli_query($con,"SET SQL_SAFE_UPDATES = 1");

$resp = array('success' => true);

die(json_encode($resp));

?>
