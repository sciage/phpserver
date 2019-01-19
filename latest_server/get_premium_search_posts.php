<?php

	require "init_new_config.php";

	if( empty($_GET['user_id']) ){
		echo '{"status":"Invalid request"}';
		die();
	}

	$user_id = intval( $_GET['user_id'] );
	$search_word = isset($_GET['search_word']) ? mysqli_real_escape_string($con, $_GET['search_word']) : "";
	$group_id = isset($_GET['group_id']) ? mysqli_real_escape_string($con, $_GET['group_id']) : "";
	$gender = isset($_GET['gender']) ? mysqli_real_escape_string($con, $_GET['gender']) : "";
	$user_date_of_birth = isset($_GET['user_date_of_birth']) ? mysqli_real_escape_string($con, $_GET['user_date_of_birth']) : "1";



	// Determine what type of groups need to be fetched
	$having_clause = "";

	if( !empty($_GET['status']) ){
		if( $_GET['status']=='joined' ){
			$having_clause = "HAVING is_joined='1' ";
		}
		elseif( $_GET['status']=='not_joined' ){
			$having_clause = "HAVING is_joined='0' ";
		}
	}


	$json = array();

	$query = "SELECT id_posts FROM candid_database.posts left join user_name on posts.id_user_name = 
		user_name.id_user_name where group_id = '". $group_id ."' and gender = '". $gender ."' 
		and user_date_of_birth = '". $user_date_of_birth ."' 
		and text_status like '%$search_word%' ";




	$q = mysqli_query($con, $query) or die(mysqli_error($con)) ;
	while($row = mysqli_fetch_assoc($q)){
		$json[] = array(
						"group_id"=> $row['group_id'],
						"group_name"=> $row['name'] ,
						"group_image_url"=> $row['group_image_url'] ,
						"group_description"=> $row['group_description'] ,
						"users_in_group"=> $row['users_in_group'] ,
						"posts_inside_groups"=> $row['posts_inside_groups'] ,
						"id_categories"=> $row['id_categories'],
						"category_name"=> $row['category_name'],
						"created_by"=> array (
									"id_user_name"=>$row['created_by_id_user_name'],
									"user_nick_name"=>$row['user_nick_name'],
									"avatar_url"=> $row['group_image_url'] 
								),
						"is_joined"=> $row['is_joined'] == '1' ? true : false ,
						"created_at"=> $row['created_at'] ,
						"joined_at"=> $row['joined_at']
					);
	}

	die(json_encode($json));

?>
