<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 14/07/18
 * Time: 10:19 PM
 */

require "init_new_config.php";

mysqli_set_charset($con, 'utf8');
$query1 = "SELECT count(messageId) FROM candid_database.chat_messages";
$query2 = "SELECT messageId FROM candid_database.chat_messages";



$querySelect1 = mysqli_query($con, $query1)
or die(mysqli_error($con));

$querySelect2 = mysqli_query($con, $query2)
or die(mysqli_error($con));

$r = mysqli_fetch_row($querySelect1);
$r2 = mysqli_fetch_row($querySelect2);

$countnumer = r[0];

for($i=0;$i<$countnumer;$i++){
    $query3 = "UPDATE `chat_messages` SET `id_posts`='17177' where `messageId` = $r2[$i]";
    $querySelect3 = mysqli_query($con, $query3);

}

?>