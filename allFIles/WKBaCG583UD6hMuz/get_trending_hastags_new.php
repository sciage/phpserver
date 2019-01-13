<?php

require "init_new_config.php";

$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";

    $q = mysqli_query($con, "SELECT cat.id_categories as id,
							cat.category as hashtag,
							COUNT(p.id_posts) as count
							FROM posts p LEFT JOIN categories cat
							ON p.id_categories=cat.id_categories
							WHERE cat.category!='' GROUP BY p.id_categories order by COUNT(p.id_posts) desc");

    $records = array();

    while ($row = mysqli_fetch_row($q)) {
        $records[] = array(
            "id" => $row[0],
            "name" => $row[1],
            "count" => $row[2]
        );;
    }
    $records = array_slice($records, 0, 20);

    die(json_encode($records));



?>
