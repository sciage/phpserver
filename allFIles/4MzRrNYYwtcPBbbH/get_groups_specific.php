<?php

	require "init_new_config.php";

	if( empty($_GET['user_id']) ){
		echo '{"status":"Invalid request"}';
		die();
	}

	$user_id = intval( $_GET['user_id'] );
	$group_id = intval( $_GET['group_id'] );


	$json = array();

	$query = "SELECT groups.*,(Select username from user_name_random where id_user_name_random=groups.created_by_id_user_name) as user_nick_name,
	(Select avatar_url from user_name_random where id_user_name_random=groups.created_by_id_user_name) as avatar_url,
			c.category as category_name, 
			(Select count(id_user_name) as users_in_group from groups_user where group_id = $group_id) as users_in_group,
			(Select count(group_id) as posts_inside_group from posts where group_id = $group_id) as posts_inside_groups,
			IF( COUNT(gu.group_user_id)>0 , 1,0 ) as 'is_joined', 
			IF( COUNT(gu.group_user_id)>0 , gu.created_at,NULL ) as 'joined_at' 
			from groups LEFT JOIN user_name un ON un.id_user_name = groups.created_by_id_user_name 
			LEFT JOIN categories c ON c.id_categories = groups.id_categories
			LEFT JOIN posts p ON p.group_id = groups.group_id  
			LEFT JOIN groups_user gu ON (gu.id_user_name='". $user_id ."' 
				AND gu.group_id=groups.group_id) where groups.group_id = '". $group_id ."'";


	$q = mysqli_query($con, $query) or die(mysqli_error($con)) ;
	while($row = mysqli_fetch_assoc($q)){
		$json[] = array( 
						"group_id"=> $row['group_id'], 
						"group_name"=> $row['name'] ,
						"created_by_id_user_name"=> $row['created_by_id_user_name'] ,
						"group_image_url"=> $row['group_image_url'] ,
						"group_description"=> $row['group_description'] ,
						"users_in_group"=> $row['users_in_group'] , 
						"posts_inside_groups"=> $row['posts_inside_groups'] ,  
						"id_categories"=> $row['id_categories'], 
						"category_name"=> $row['category_name'], 
						"created_by"=> array (
									"id_user_name"=>$row['created_by_id_user_name'], 
									"user_nick_name"=>$row['user_nick_name'],
									"avatar_url"=>$row['avatar_url']  
								), 
						"is_joined"=> $row['is_joined'] == '1' ? true : false ,
						"created_at"=> $row['created_at'] ,
						"joined_at"=> $row['joined_at'] 
					);
	}

	die(json_encode($json));

?>
