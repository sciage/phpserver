<?php
require "init_new_config.php";

$limit_clause = '';
$page1=  isset($_GET['page']) ? $_GET['page'] : '';
if ($page1 != '') {
    $page = $page1;
    $limit = 25;
    $left = $page * $limit - $limit;
    $limit_clause = " LIMIT " . $left . "," . $limit; // LIMIT 25,50
} else {
    $limit = 25;
    $page = 1;
    $limit_clause = " LIMIT 0 ," . $limit;

}
$user_id = 0;
if(!isset($_GET['user_id'])){
//	return_responce(false, array(), "User Authentication Failed", $action = "");
	die();
}else{
	$user_id = intval($_GET['user_id'] );
}
$below18 = isset($_GET['below18']) ? mysqli_real_escape_string($con, $_GET['below18']) : "";

$below18_condition = "";

if(isset($_GET['below18']) and (int)$_GET['below18'] == 1){
	$below18_condition = " and groups_below_18 = 1";
}
$joined_condition = "";
if(isset($_GET['joined']) and (int)$_GET['joined'] == 0){
	$joined_condition = " not ";
}

mysqli_set_charset($con, 'utf8');

$sql = "SELECT groups.group_id, groups.name as group_name, groups.group_image_url, 
    groups.group_description, (Select count(id_user_name) as users_in_group 
    from groups_user where group_id = groups.group_id) as users_in_group, 
    (Select count(group_id) as posts_inside_group from posts 
    where group_id = groups.group_id) as posts_inside_groups, 
    groups.id_categories, 
    (select category from categories where categories.id_categories = groups.id_categories) as category_name, 
    groups.created_by_id_user_name as created_by_id_user_name 
    
    FROM groups WHERE  
    group_id $joined_condition in (SELECT group_id 
    FROM groups_user where id_user_name = $user_id) $below18_condition $limit_clause";

$json = array();

$result = $con->query($sql);
if($result){
	if($result->num_rows > 0){
		while($row = mysqli_fetch_assoc($result)){
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

		// return_responce(true, array('groups' => $json), "Success", $action = "");
	}else{
	//	return_responce(false, array('groups' => array()), "No Groups Found", $action = "");
	}
}else{
	// return_responce(false, array('groups' => array()), "Unable to Connect Server", $action = "");
}

die(json_encode($json));

?>