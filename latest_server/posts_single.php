<?php
require "init_new_config.php";
require "all_arrays.php";


$user_id = 0;
global $random_names;
global $random_avatar;
global $numbers;

if(!isset($_GET['user_id'])){
	// return_responce(false, array(), "User Authentication Failed", $action = "");
	die();
}else{
	$user_id = intval($_GET['user_id'] );
	$id_posts = intval($_GET['id_posts'] );
}

$sql = "SELECT posts.id_posts, posts.id_user_name, posts.group_id, posts.post_time, posts.text_status, posts.audio_duration, posts.audio_file_link, posts.random_color_light as light_color,  posts.random_color_dark as dark_color, posts.location,  posts.adult_filter as adult_filter,  posts.isImage as isImage,  posts.image_url as image_url,   posts.type as type,  posts.id_categories as category, posts.report_abuse_count, posts.id_user_name_random as id_user_name_random,  groups.name as name,  (SELECT feeling_likes FROM feeling_category WHERE id_user_name= $user_id and feeling_category.id_posts = posts.id_posts) as feeling_like, (SELECT count(feeling_likes) FROM feeling_category WHERE feeling_category.id_posts = posts.id_posts and feeling_likes = 1) as likes, (SELECT count(feeling_likes) FROM feeling_category WHERE feeling_category.id_posts = posts.id_posts and feeling_likes = 2) as hug,  (SELECT count(post_comments.id_post_comments) as comments FROM candid_database.post_comments  where post_comments.id_posts = posts.id_posts) as comments, (SELECT count(post_comment_reply.id_post_comments) as comment_reply  FROM candid_database.post_comment_reply  where post_comment_reply.id_posts = posts.id_posts) as comments_reply  FROM posts LEFT JOIN groups on posts.group_id = groups.group_id where posts.id_posts = $id_posts";

$result = $con->query($sql);
$records = array();
if($result){
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){

            $row['user_nic_name'] = null;
            $row['avatar_pics'] = null;
            $row['location'] = null;
			$row['user_name_random'] = $random_names[$row['id_user_name_random']];
			$row['avatar_url_random'] = $random_avatar[$row['id_user_name_random']];
			$row['category'] = $numbers[$row['category']];
			$row['user_like'] = false;
			$row['user_Huge'] = false;
			if($row['feeling_like'] == 1){
				$row['user_like'] = true;
			}else{
				$row['user_Huge'] = false;
			}
			$records[] = $row;
		}
	}
}



echo json_encode($records);
?>