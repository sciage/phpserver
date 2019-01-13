<?php
//	header("Content-Type : application/json");

	//$con = mysqli_connect("localhost","devesh",'sed1058','candid_db');

 require "init_new_config.php";

  	  $limit_clause='';
	  
	  if( !empty($_GET['page']) ){
		  	$page = $_GET['page'];
			$limit=25;
			$left = $page*$limit - $limit;
			$limit_clause=" LIMIT ".$left.",".$limit;
	  }
	  else
	  	{		$limit=25;
				$page = 1;
				$limit_clause=" LIMIT 0 ,".$limit;
				
		}
	  	
	  	
		
	

	 /* FETCH data of posts which match the set where caluse filter and pagintaion limits */
	 $q="SELECT posts.id_posts, 
       posts.id_user_name,
       posts.group_id,
       posts.post_time,
       posts.text_status,
       posts.audio_duration,
       posts.audio_file_link,
       posts.random_color_light as light_color,
       posts.random_color_dark as dark_color,
       posts.location,
       posts.isImage as isImage,
       categories.category as category,
       posts.report_abuse_count,
       user_name.user_nick_name as user_nic_name,
       user_name.avatar_pics,
       user_name_random.id_user_name_random,
       user_name_random.username as user_name_random,
       user_name_random.avatar_url as avatar_url_random,
       groups.name as name
     
       FROM posts 
       LEFT JOIN user_name ON posts.id_user_name=user_name.id_user_name
       LEFT JOIN categories ON posts.id_categories= categories.id_categories
       LEFT JOIN user_name_random ON user_name_random.id_user_name_random=posts.id_user_name_random
       LEFT JOIN groups on posts.group_id = groups.group_id "
       .$post_filter
       ." ORDER BY posts.id_posts DESC "
       ;


	$q=mysqli_query($con,$q)
        or die(mysqli_error($con));


	 //echo mysqli_num_rows($q);
	$records=array();
	while($r=mysqli_fetch_assoc($q)){
		$post_id=$r['id_posts'];
		
		
		if(isset($_GET['user_id'])!='') {

			$Like = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='".$_GET['user_id']."' and id_posts='".$post_id."' and feeling_likes = '1'");
			$rLike = mysqli_fetch_assoc($Like);
			if($rLike['id_feeling_category']!='')  { $iValueLike  = true; }  else { $iValueLike  = false; } 

 				
		/*	$Same = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='".$_GET['user_id']."' and id_posts='".$post_id."' and feeling_same = '1'");
			$rSame = mysqli_fetch_assoc($Same);
			if($rSame['id_feeling_category']!='')  { $iValueSame  = true; }  else { $iValueSame  = false; }	 */
				
			
			$Huge = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='".$_GET['user_id']."' and id_posts='".$post_id."' and feeling_hug = '1'");
			$rHuge = mysqli_fetch_assoc($Huge);
			if($rHuge['id_feeling_category']!='')  { $iValueHuge  = true; }  else { $iValueHuge  = false; } 
			
			
			 
			
			
	/*		$Listen = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='".$_GET['user_id']."' and id_posts='".$post_id."' and audio_listen = '1'");
			$rlisten = mysqli_fetch_assoc($Listen);
			if($rlisten['id_feeling_category']!='')  { $iValueListen  = true; }  else { $iValueListen  = false; }  */	
				
				
				
				
				
				
				$r['user_like']=$iValueLike ;
				$r['user_Huge']=$iValueHuge ;
			/*	$r['user_Same']=$iValueSame ;
				$r['user_Listen']=$iValueListen ; */
		}
		
		$qu=mysqli_query($con,"SELECT sum(feeling_category.feeling_likes) as likes, 
					     /*  sum(feeling_category.feeling_same) as same,
					     sum(feeling_category.audio_listen) as listen */
					       sum(feeling_category.feeling_hug) as hug
				
					       FROM feeling_category
					       WHERE feeling_category.id_posts='".$post_id."' 
					       ") or die(mysqli_error());
		$row=mysqli_fetch_assoc($qu);

		foreach($row as $key=>$val){
			$r[$key]=intval($val);
		}

		$qu = mysqli_query($con, "SELECT 	count(post_comment_reply.id_post_comments) as comments_reply,
		count(post_comments.id_post_comments) as comments,
		post_comments.message as post_comment,
		 user_name_random.username as comment_reply,
        user_name_random.avatar_url as comment_avatar
	     						FROM post_comments 
                                LEFT JOIN post_comment_reply ON post_comment_reply.id_post_comments=post_comments.id_post_comments
                                LEFT JOIN user_name_random ON user_name_random.id_user_name_random=post_comments.id_user_name_random
                                where post_comments.id_posts='" . $post_id . "'
					       ") or die(mysqli_error());
	    $row = mysqli_fetch_assoc($qu);

	    foreach ($row as $key => $val) {
	        $r[$key] = $val;
	    }

		$records[]=$r;

	}
		

	echo json_encode( $records) ;


?>
