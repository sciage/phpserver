<?php

	require "init_new_config.php";

	/* if( empty($_GET['user_id']) || empty($_GET['category_ids']) ){
		echo '{"status":"Invalid request"}';
		die();
	}
	*/

	$user_id = intval( $_GET['user_id'] );


	// Extract valid integer category ids
	$category_ids = explode(",",$_GET['category_ids']);
	$category_ids_valid = array();
	foreach ($category_ids as $key => $cat_id) {
		if( is_numeric($cat_id) ){
			$category_ids_valid[] = "'". intval($cat_id) ."'";
		}
	}
	
	$json = array();

	$query = "SELECT groups.*,un.user_nick_name,c.category as category_name, IF( COUNT(gu.group_user_id)>0 , 1,0 ) as 'is_joined', IF( COUNT(gu.group_user_id)>0 , gu.created_at,NULL ) as 'joined_at' from groups LEFT JOIN user_name un ON un.id_user_name = groups.created_by_id_user_name LEFT JOIN categories c ON c.id_categories = groups.id_categories LEFT JOIN groups_user gu ON (gu.id_user_name='". $user_id ."' AND gu.group_id=groups.group_id)  WHERE groups.id_categories IN ( ". implode(",", $category_ids_valid) ." ) GROUP BY groups.group_id ";


	$q = mysqli_query($con, $query) or die(mysqli_error($con)) ;
	while($row = mysqli_fetch_assoc($q)){
		$json[] = array( 
						"group_id"=> $row['group_id'], 
						"group_name"=> $row['name'] , 
						"id_categories"=> $row['id_categories'], 
						"category_name"=> $row['category_name'], 
						"created_by"=> array (
									"id_user_name"=>$row['created_by_id_user_name'], 
									"user_nick_name"=>$row['user_nick_name'] 
								), 
						"is_joined"=> $row['is_joined'] == '1' ? true : false ,
						"created_at"=> $row['created_at'] ,
						"joined_at"=> $row['joined_at'] 
					);
	}

	die(json_encode($json));

?>
