<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 03/07/18
 * Time: 3:15 AM
 */

require "init_new_config.php";

$limit_clause = '';
$limit = 10;
$page1=  isset($_GET['page']) ? $_GET['page'] : '';
if ($page1 != '') {
    $page = $page1;
   
    $left = $page * $limit - $limit;
    $limit_clause = " LIMIT " . $left . "," . $limit;
} else {
    $page = 1;
    $limit_clause = " LIMIT 0 ," . $limit;

}
if(isset($_GET['user_id'])){
    $user_id = intval($_GET['user_id'] );
}else{
    $user_id = 0;
}
$json = array();
$below18 = isset($_GET['below18']) ? mysqli_real_escape_string($con, $_GET['below18']) : "";

mysqli_set_charset($con, 'utf8');
$group_ids = array();
//if ($below18 == "true"){ // if true then user will get posts that are suitable for below 18

$sql = "SELECT id_categories FROM group_category_join where id_user_name = $user_id ". $limit_clause;
$result = $mysqli->query($sql);
if($result and $result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		array_push($group_ids, $row['id_categories']);
	}
}
if(count($group_ids)){
	$sql = "SELECT groups.group_id, groups.name as group_name, groups.group_image_url, groups.group_description, (Select count(id_user_name) as users_in_group from groups_user where group_id = groups.group_id) as users_in_group, (Select count(group_id) as posts_inside_group from posts where group_id = groups.group_id) as posts_inside_groups, groups.id_categories, c.category as category_name, groups.created_by_id_user_name as created_by_id_user_name FROM groups LEFT JOIN categories c ON groups.id_categories = c.id_categories LEFT JOIN groups_user gu ON groups.group_id = gu.group_id where groups.group_id IN (".implode(",", $group_ids).") group by group_id";
}else{
	$sql = "SELECT groups.group_id, groups.name as group_name, groups.group_image_url, groups.group_description, (Select count(id_user_name) as users_in_group from groups_user where group_id = groups.group_id) as users_in_group, (Select count(group_id) as posts_inside_group from posts where group_id = groups.group_id) as posts_inside_groups, groups.id_categories, c.category as category_name, groups.created_by_id_user_name as created_by_id_user_name FROM groups LEFT JOIN categories c ON  groups.id_categories = c.id_categories LEFT JOIN groups_user gu ON groups.group_id = gu.group_id where groups.group_id NOT IN (SELECT group_id FROM groups_user where id_user_name = $user_id) group by group_id".$limit_clause;
}

$result = $mysqli->query($sql);
if($result and $result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		$json[] = array(
			"group_id"=> $row['group_id'],
			"group_name"=> $row['group_name'] ,
			"group_image_url"=> $row['group_image_url'] ,
			"group_description"=> $row['group_description'] ,
			"users_in_group"=> $row['users_in_group'] ,
			"posts_inside_groups"=> $row['posts_inside_groups'] ,
			"id_categories"=> $row['id_categories'],
			"category_name"=> $row['category_name'],
			"created_by"=> array (
				"id_user_name"=>$row['created_by_id_user_name'],
				"user_nick_name"=>"anonymous",
			)
		);
	}
}
die(json_encode($json));
?>