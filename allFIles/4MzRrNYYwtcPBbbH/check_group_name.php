<?php

require "init_new_config.php";

$group_name = isset($_GET['group_name']) ? mysqli_real_escape_string($con, $_GET['group_name']) : "";


$q = mysqli_query($con, "select groups.name from groups where groups.name ='" . $group_name . "'");


if (mysqli_num_rows($q) > 0) {
    $resp = array('success' => true);
} else {
    $resp = array('success' => false);
}

die(json_encode($resp));


?>
