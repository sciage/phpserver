<?php
//	header("Content-Type : application/json");


require "init_new_config.php";


	/* FETCH POSTS OF ALL PHONE CONTACTS OF THE USER */
	/* Extract the Post Ids And set it to a GET variable which next IF BLOCK will catch. */ 
	if(!empty($_GET['contacts']) && !empty($_GET['id_user_name'])){
		$q=mysqli_query($con,"SELECT id_posts
				FROM posts
				WHERE id_user_name
				IN (
					SELECT id_user_name
					FROM user_name
					WHERE phone_number
					IN (
						SELECT DISTINCT phone_number
							FROM user_contacts
							WHERE id_user_name =  '".$_GET['id_user_name']."'
						)
				)");

		$post_ids=array();
		while($row=mysqli_fetch_row($q)){
			$post_ids[]=$row[0];
		}

		if(count($post_ids)){
			unset($_GET['id_user_name']);
			unset($_GET['contacts']);
			$_GET['id_posts']=implode(",", $post_ids);
		}else{
			die('[]');
		}
	}

	if(!empty($_GET['facebookId']) && !empty($_GET['id_user_name'])){
		$q=mysqli_query($con,"SELECT id_posts
				FROM posts
				WHERE id_user_name
				IN (
					SELECT id_user_name
					FROM user_name
					WHERE userid
					IN (
						SELECT DISTINCT facebook_id
							FROM facebook_friend_id
							WHERE id_user_name =  '".$_GET['id_user_name']."'
						)
				)");

		$post_ids=array();
		while($row=mysqli_fetch_row($q)){
			$post_ids[]=$row[0];
		}

		if(count($post_ids)){
			unset($_GET['id_user_name']);
			unset($_GET['facebookId']);
			$_GET['id_posts']=implode(",", $post_ids);
		}else{
			die('[]');
		}
	}

	if(!empty($_GET['popularPostId'])){
		$q=mysqli_query($con,"SELECT id_posts
				FROM posts
				WHERE id_posts
				IN (
					SELECT post_id as id_posts
					FROM specific_post order by id_specific_post)");

		$post_ids=array();
		while($row=mysqli_fetch_row($q)){
			$post_ids[]=$row[0];
		}

		if(count($post_ids)){
			unset($_GET['popularPostId']);
			$_GET['id_posts']=implode(",", $post_ids);
		}else{
			die('[]');
		}
	}


	/* Return post data for all the given ids
	   @param
		=>	$_GET['id_posts'] : comma seperated list of post ids whose data is required 
	*/
	if(!empty($_GET['id_posts'])){
		$post_filter="WHERE posts.id_posts IN (".$_GET['id_posts'].")";
	}

	/* Return posts of a user , where posts have either been liked,samed,hugged or commented upon 
	   @param
		=>	$_GET['filtered'] : indicates that only posts with some reaction have to be returned
		=>	$_GET['id_user'] : user id of the person whose posts are needed
	*/
	elseif(!empty($_GET['filtered']) && !empty($_GET['id_user'])){
		$q=mysqli_query($con,"SELECT DISTINCT id_posts FROM feeling_category WHERE ( id_user_name='{$_GET['id_user']}' )  AND ( feeling_likes != 0 OR feeling_hug != 0 OR feeling_same != 0 ) UNION SELECT DISTINCT id_posts FROM post_comments WHERE id_user_name='{$_GET['id_user']}'");

		$pids=array();
		while($a=mysqli_fetch_assoc($q)){
			$pids[]=$a['id_posts'];
		}
		$pids=implode(",",$pids);
		
		$post_filter="WHERE id_posts IN ({$pids})";
	}

	/* Return all posts of a given user
	   @param
		=>	$_GET['id_user'] : user id of the person whose whose posts are required 
	*/
	elseif(!empty($_GET['id_user'])){
		$post_filter="WHERE posts.id_user_name=".$_GET['id_user'];
	}

	/* Return all posts of a particular feeling 
	   @param
		=>	$_GET['feeiling_id'] : feeling id of the posts required
	*/
	elseif(!empty($_GET['feeling_id'])){
		$post_filter="WHERE posts.id_feeling_table=".$_GET['feeling_id'];
	}

	/* Return all posts of a particular category
	   @param
		=>	$_GET['feeiling_id'] : user id of the person whose whose posts are required 
	*/
	elseif(!empty($_GET['category_id'])){
		$post_filter="WHERE posts.id_categories=".$_GET['category_id'];
	}

	/* Return all posts from people whom the user follows
	   @param
		=>	$_GET['follower'] : user id of the user
	*/
	elseif(!empty($_GET['follower'])){
		$post_filter="WHERE posts.id_user_name IN( SELECT user_id FROM tbl_follower WHERE followers='{$_GET['follower']}' ) ";
	}

	/* Return all posts that were added after the supplied timestamp
	   @param
		=>	$_GET['timestamp'] : threshold timestamp
	*/
	elseif(!empty($_GET['timestamp'])){
		$post_filter="WHERE posts.post_time > ".$_GET['timestamp'];

	}

	/* Return all posts in database*/
	else{
		$post_filter='';
	}



	/*
	  @@@@ PAGINAGTION @@@@

	  limit = Count of maximum rows to be returnd
	  page= Offeset from where to start fetching rows

	  LIMIT= (page*limit, limit)

	 */
  	  $limit_clause='';

	 /* if( isset($_GET['limit']) && isset($_GET['page']) ){
	  	$limit=intval($_GET['limit']);
	  	$page=intval($_GET['page']);

	  	if($limit>0){
	  		$limit_clause=" LIMIT ".$page*$limit.",".$limit;
	  	}
	  }*/
	  
	  
	  
	  if( $_GET['page']!=''){
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
       posts.post_time,
       posts.text_status,
       posts.audio_duration,
       posts.audio_file_link,
       posts.report_abuse_count,
       user_name.user_nick_name as user_nic_name,
       user_name.avatar_pics,
       feeling_table.emotions,
       feeling_table.id_feeling_table,
       categories.id_categories,
       categories.category
       FROM posts 
       LEFT JOIN user_name ON posts.id_user_name=user_name.id_user_name
       LEFT JOIN feeling_table on posts.id_feeling_table = feeling_table.id_feeling_table
       LEFT JOIN categories on posts.id_categories = categories.id_categories "
       .$post_filter
       ." ORDER BY posts.id_posts DESC "
       .$limit_clause;


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

 				
			$Same = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='".$_GET['user_id']."' and id_posts='".$post_id."' and feeling_same = '1'");
			$rSame = mysqli_fetch_assoc($Same);
			if($rSame['id_feeling_category']!='')  { $iValueSame  = true; }  else { $iValueSame  = false; }	
				
				
				
			
			$Huge = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='".$_GET['user_id']."' and id_posts='".$post_id."' and feeling_hug = '1'");
			$rHuge = mysqli_fetch_assoc($Huge);
			if($rHuge['id_feeling_category']!='')  { $iValueHuge  = true; }  else { $iValueHuge  = false; } 
			
			
			 
			
			
			$Listen = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='".$_GET['user_id']."' and id_posts='".$post_id."' and audio_listen = '1'");
			$rlisten = mysqli_fetch_assoc($Listen);
			if($rlisten['id_feeling_category']!='')  { $iValueListen  = true; }  else { $iValueListen  = false; } 	
				
				
				
				
				
				
				$r['user_like']=$iValueLike ;
				$r['user_Same']=$iValueSame ;
				$r['user_Huge']=$iValueHuge ;
				$r['user_Listen']=$iValueListen ;
		}
		
		
		
		
		
		
		
		
		

		$qu=mysqli_query($con,"SELECT sum(feeling_category.feeling_likes) as likes, 
					       sum(feeling_category.feeling_same) as same,
					       sum(feeling_category.feeling_hug) as hug, 
					       sum(feeling_category.audio_listen) as listen
					       FROM feeling_category
					       WHERE feeling_category.id_posts='".$post_id."' 
					       ") or die(mysqli_error());
		$row=mysqli_fetch_assoc($qu);

		foreach($row as $key=>$val){
			$r[$key]=intval($val);
		}

		$qu = mysqli_query($con, "SELECT count(post_comments.id_post_comments) as comments
					       FROM post_comments
					   WHERE post_comments.id_posts='" . $post_id . "'
					       ") or die(mysqli_error());
    $row = mysqli_fetch_assoc($qu);

    foreach ($row as $key => $val) {
        $r[$key] = intval($val);
    }

     $qu = mysqli_query($con, "SELECT 	count(post_comment_reply.id_post_comments) as comments_reply 
     						FROM candid_db.post_comment_reply where id_posts = '" . $post_id . "'
					       ") or die(mysqli_error());
    $row = mysqli_fetch_assoc($qu);

    foreach ($row as $key => $val) {
        $r[$key] = intval($val);
    }
		
		
		
		
		
		
		
		
		
		
		

		if(empty($r['likes'])){
			$r['likes']=0;
		}if(empty($r['same'])){
			$r['same']=0;
		}if(empty($r['hug'])){
			$r['hug']=0;
		}if(empty($r['listen'])){
			$r['listen']=0;
		}

		$records[]=$r;

	}

	
	function order_by_comments_like($a, $b)
	{
	  if ($a['comments'] == $b['comments'])
	  {
	    // comments is the same, sort by likes
	    if ($a['likes'] == $b['likes']) return 0;
	    return $a['likes'] < $b['likes'] ? 1 : -1;
	  }

	  // sort the higher score first:
	  return $a['comments'] < $b['comments'] ? 1 : -1;
	}

	function order_by_likes_same($a, $b)
		{
		  if ($a['likes'] == $b['likes'])
		  {
		    // likes is the same, sort by same
		    if ($a['same'] == $b['same']) return 0;
		    return $a['same'] < $b['same'] ? 1 : -1;
		  }

		  // sort the higher score first:
		  return $a['likes'] < $b['likes'] ? 1 : -1;
		}

	if(!empty($_GET['popular']))
		usort($records, 'order_by_comments_like');
	elseif(!empty($_GET['trending']))
		usort($records, 'order_by_likes_same');

	echo json_encode( encode_utf_properly($records) );


	function encode_utf_properly($d) {
	    if (is_array($d)) {
	        foreach ($d as $k => $v) {
	            $d[$k] = encode_utf_properly($v);
	        }
	    } else if (is_string ($d)) {
	        return utf8_encode($d);
	    }
	    return $d;
	}



?>
