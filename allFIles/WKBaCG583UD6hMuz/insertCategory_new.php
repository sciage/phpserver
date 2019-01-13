<?php

require "init_new_config.php";

$category = isset($_POST['category']) ? mysqli_real_escape_string($con, $_POST['category']) : "";

$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";




    $q = mysqli_query($con, "SELECT category FROM categories where category = '" . $category . "'");

    if (mysqli_num_rows($q) > 0) {

        $catId = mysqli_query($con, "SELECT id_categories FROM categories where category = '" . $category . "'");


        $row = mysqli_fetch_array($catId);
        $categoryId = $row['id_categories'];

        $resp = array('success' => false, 'id' => $categoryId);
    } else {
        $sql = mysqli_query($con, "INSERT INTO `categories` (`category`)
	VALUES ('$category')") or die(mysqli_error($con));

        $cat_id = mysqli_insert_id($con);

        if ($cat_id) {
            $resp = array('success' => true, 'id' => $cat_id);
        } else {
            $resp = array('success' => false);
        }

    }

    die(json_encode($resp));


?>
