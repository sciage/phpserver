<?php

require "init_new_config.php";

$id_posts = isset($_POST['id_posts']) ? mysqli_real_escape_string($con, $_POST['id_posts']) : "";
$adult_filter = isset($_POST['adult_filter']) ? mysqli_real_escape_string($con, $_POST['adult_filter']) : "";

if (!empty($adult_filter)) {

    $sql = mysqli_prepare($con, "UPDATE `posts` SET `adult_filter`=? WHERE `id_posts`=? LIMIT 1");

    if ($sql) {
        mysqli_stmt_bind_param($sql, "ss", $adult_filter, $id_posts);
        mysqli_stmt_execute($sql);

        $resp = array('success' => true);

        mysqli_stmt_close($sql);

    } else {
        $resp = array('success' => false);
    }

    die(json_encode($resp));

}


die('Invalid Request');
?>
