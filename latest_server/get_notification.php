<?php
//	header("Content-Type : application/json");

	require "init_new_config.php";
/* FETCH ALL LIKERS OF A POST */
	if(!empty($_GET['id_user_name'])) {
		
		$records=array();
		
		$limit_clause='';

        $limit_clause = '';

        /* if( isset($_GET['limit']) && isset($_GET['page']) ){
             $limit=intval($_GET['limit']);
             $page=intval($_GET['page']);

             if($limit>0){
                 $limit_clause=" LIMIT ".$page*$limit.",".$limit;
             }
         }*/

        $page1=  isset($_GET['page']) ? $_GET['page'] : '';
        if ($page1 != '') {
            $page = $page1;
            $limit = 25;
            $left = $page * $limit - $limit;
            $limit_clause = " LIMIT " . $left . "," . $limit;
        } else {
            $limit = 25;
            $page = 1;
            $limit_clause = " LIMIT 0 ," . $limit;

        }

				$q=mysqli_query($con,"SELECT (SELECT avatar_url from user_name_random where id_user_name_random = p.id_user_name_random) as avatar_pic, 
					(SELECT username from user_name_random where id_user_name_random = p.id_user_name_random) as username, 
					pn.senderId as senderId, pn.notificationText as notificationText, pn.postId as postId, pn.activity, pn.time as time, p.text_status 
					FROM postNotifications as pn left join user_name as u on receiverId = id_user_name 
					left join posts as p on postId = id_posts where pn.receiverId = '".$_GET['id_user_name']."' 
					ORDER BY pn.notificationId desc " . $limit_clause) or die(mysqli_error($con));


		while($row=mysqli_fetch_assoc($q)){
			$records[]=$row;
		}	

		die( json_encode($records) );
		
	}

	die("invalid");




?>
