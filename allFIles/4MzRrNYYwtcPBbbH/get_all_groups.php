<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 03/07/18
 * Time: 3:15 AM
 */

require "init_new_config.php";

if( empty($_GET['user_id']) ){
    echo '{"status":"Invalid request"}';
    die();
}


$below18_condition = "";
if(isset($_GET['below18']) and (int)$_GET['below18'] == 1){
    $below18_condition = " and groups_below_18 = 1";
}

$numbers = array( "Politics", "Politics", "Celebrities", "Music", "Technology", "Fashion", "Business", "School",
    "Art", "Photography", "LGBT", "Relationships", "Sports", "Funny", "Confessions", "Personal", "Sex", "Family",
    "Work", "Faith", "Food", "Entertainment", "Women Issues", "Health", "Men Issues", "Science", "Teens" );

$query = "SELECT groups.group_id, groups.name as name, groups.group_image_url, groups.id_categories as category FROM candid_database.groups ";

$q = mysqli_query($con, $query);
while($row = mysqli_fetch_assoc($q)){
    $json[] = array(
        "group_id"=> $row['group_id'],
        "name"=> $row['name'] ,
        "category"=> $numbers[$row['category']]
    );
}
die(json_encode($json));
?>