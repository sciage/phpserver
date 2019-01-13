<?php


ini_set("default_charset", "UTF-8");
date_default_timezone_set("Asia/Kolkata");

// header('Content-type: application/json; charset=UTF-8');

$con = mysqli_connect('35.188.66.233', 'F9ceab', 'hfhBMSKKy8h9y9YU', 'candid_database', 3306);

mysqli_query($con,"SET character_set_results = 'utf8mb4', character_set_client = 'utf8mb4', character_set_connection = 'utf8mb4', character_set_database = 'utf8mb4', character_set_server = 'utf8mb4'" );

mysqli_set_charset($con, "utf8mb4");
mysqli_query($con,"SET NAMES 'utf8mb4'")
or die(mysqli_error($con));

// $re = mysqli_query($con,"SHOW VARIABLES LIKE 'character_set_%'; ")or die(mysql_error());
// while ($r = mysqli_fetch_assoc($re)) {var_dump ($r); echo "<br />";} exit;



?>

