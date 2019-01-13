<?php
//	header("Content-Type : application/json");

//$con = mysqli_connect("localhost","devesh",'sed1058','candid_db');

require "init_new_config.php";

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
} else if(!empty($_GET['group_post']) && !empty($_GET['id_user_name'])){ /*filtered post but update needed this*/
    $q=mysqli_query($con,"SELECT id_posts FROM feeling_category WHERE id_user_name='{$_GET['id_user_name']}' union select id_posts from post_comments where id_user_name= '{$_GET['id_user_name']}' order by id_posts desc");

    $post_ids=array();
    while($row=mysqli_fetch_row($q)){
        $post_ids[]=$row[0];
    }

    if(count($post_ids)){
        unset($_GET['id_user_name']);
        unset($_GET['group_post']);
        $_GET['id_posts']=implode(",", $post_ids);
    }else{
        die('[]');
    }
} else if(!empty($_GET['filtered']) && !empty($_GET['id_user_name'])){ /*group posts. but updated needed this */
    $q=mysqli_query($con,"SELECT id_posts
				FROM posts
				WHERE group_id
				IN (
					SELECT group_id FROM candid_database.groups WHERE group_id 
			IN (SELECT group_id FROM candid_database.groups_user where id_user_name = '".$_GET['id_user_name']."'
			)
						)");

    $post_ids=array();
    while($row=mysqli_fetch_row($q)){
        $post_ids[]=$row[0];
    }

    if(count($post_ids)){
        unset($_GET['id_user_name']);
        unset($_GET['filtered']);
        $_GET['id_posts']=implode(",", $post_ids);
    }else{
        die('[]');
    }
}
else if(!empty($_GET['facebookId']) && !empty($_GET['id_user_name'])){
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

else if(!empty($_GET['popularPostId'])){
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
if (!empty($_GET['id_posts'])) {
    $post_filter = "WHERE posts.id_posts IN (" . $_GET['id_posts'] . ")";
} /* Return posts of a user , where posts have either been liked,samed,hugged or commented upon
	   @param
		=>	$_GET['filtered'] : indicates that only posts with some reaction have to be returned
		=>	$_GET['id_user'] : user id of the person whose posts are needed
	*/
elseif (!empty($_GET['id_user'])) {
    $post_filter = "WHERE posts.id_user_name=" . $_GET['id_user'];
} /* Return all posts of a particular category
	   @param
		=>	$_GET['feeiling_id'] : user id of the person whose whose posts are required
	*/
elseif (!empty($_GET['category_id'])) {
    $post_filter = "WHERE posts.group_id=" . $_GET['category_id'];
} /* Return all posts from people whom the user follows
	   @param
		=>	$_GET['follower'] : user id of the user
	*/
elseif (!empty($_GET['follower'])) {
    $post_filter = "WHERE posts.id_user_name IN( SELECT user_id FROM tbl_follower WHERE followers='{$_GET['follower']}' ) ";
} /* Return all posts that were added after the supplied timestamp
	   @param
		=>	$_GET['timestamp'] : threshold timestamp
	*/
elseif (!empty($_GET['timestamp'])) {
    $post_filter = "WHERE posts.post_time > " . $_GET['timestamp'];

} /* Return all posts inside a particular group
	   @param
		=>	$_GET['timestamp'] : threshold timestamp
	*/
elseif (!empty($_GET['group_id'])) {
    $post_filter = "WHERE posts.group_id = '" . $_GET['group_id'] . "'";

} /* Return all posts in database*/
else {
    $post_filter = '';
}


/*
  @@@@ PAGINAGTION @@@@

  limit = Count of maximum rows to be returnd
  page= Offeset from where to start fetching rows

  LIMIT= (page*limit, limit)

 */
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


/* FETCH data of posts which match the set where caluse filter and pagintaion limits */
$q = "SELECT posts.id_posts, 
       posts.id_user_name,
       posts.group_id,
       posts.post_time,
       posts.text_status,
       posts.audio_duration,
       posts.audio_file_link,
       posts.random_color_light as light_color,
       posts.random_color_dark as dark_color,
       posts.location,
       posts.adult_filter as adult_filter,
       posts.isImage as isImage,
       posts.image_url as image_url,
       posts.type as type,
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
       LEFT JOIN groups on posts.group_id = groups.group_id " . $post_filter . " ORDER BY posts.id_posts DESC "
    . $limit_clause;


$querySelect = mysqli_query($con, $q)
or die(mysqli_error($con));


//echo mysqli_num_rows($q);
$records = array();
while ($r = mysqli_fetch_assoc($querySelect)) {
    $post_id = $r['id_posts'];

    if (isset($_GET['user_id']) != '') {

        $Like = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='" . $_GET['user_id'] . "' and id_posts='" . $post_id . "' and feeling_likes = '1'");
        $rLike = mysqli_fetch_assoc($Like);
        if ($rLike['id_feeling_category'] != '') {
            $iValueLike = true;
        } else {
            $iValueLike = false;
        }


        /*	$Same = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='".$_GET['user_id']."' and id_posts='".$post_id."' and feeling_same = '1'");
            $rSame = mysqli_fetch_assoc($Same);
            if($rSame['id_feeling_category']!='')  { $iValueSame  = true; }  else { $iValueSame  = false; }	 */


        $Huge = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='" . $_GET['user_id'] . "' and id_posts='" . $post_id . "' and feeling_hug = '1'");
        $rHuge = mysqli_fetch_assoc($Huge);
        if ($rHuge['id_feeling_category'] != '') {
            $iValueHuge = true;
        } else {
            $iValueHuge = false;
        }


        /*		$Listen = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='".$_GET['user_id']."' and id_posts='".$post_id."' and audio_listen = '1'");
                $rlisten = mysqli_fetch_assoc($Listen);
                if($rlisten['id_feeling_category']!='')  { $iValueListen  = true; }  else { $iValueListen  = false; }  */


        $r['user_like'] = $iValueLike;
        $r['user_Huge'] = $iValueHuge;
        /*	$r['user_Same']=$iValueSame ;
            $r['user_Listen']=$iValueListen ; */
    }

    $qu = mysqli_query($con, "SELECT sum(feeling_category.feeling_likes) as likes, 
					     /*  sum(feeling_category.feeling_same) as same,
					     sum(feeling_category.audio_listen) as listen */
					       sum(feeling_category.feeling_hug) as hug
				
					       FROM feeling_category
					       WHERE feeling_category.id_posts='" . $post_id . "' 
					       ") or die(mysqli_error());
    $row = mysqli_fetch_assoc($qu);

    foreach ($row as $key => $val) {
        $r[$key] = intval($val);
    }

    $qu = mysqli_query($con, "SELECT count(post_comments.id_post_comments) as comments  FROM candid_database.post_comments  where post_comments.id_posts = '" . $post_id . "' 
					       ") or die(mysqli_error());
    $row = mysqli_fetch_assoc($qu);

    foreach ($row as $key => $val) {
        $r[$key] = intval($val);
    }

    $qu = mysqli_query($con, "SELECT count(post_comment_reply.id_post_comments) as comments_reply  FROM candid_database.post_comment_reply  where post_comment_reply.id_posts='" . $post_id . "' 
					       ") or die(mysqli_error());
    $row = mysqli_fetch_assoc($qu);

    foreach ($row as $key => $val) {
        $r[$key] = intval($val);
    }


    $qu = mysqli_query($con, "SELECT 	
/*count(post_comment_reply.id_post_comments) as comments_reply,
		count(post_comments.id_post_comments) as comments, */
		post_comments.message as post_comment,
		 user_name_random.username as comment_reply,
        user_name_random.avatar_url as comment_avatar
	     						FROM post_comments 
                                LEFT JOIN post_comment_reply ON post_comment_reply.id_post_comments=post_comments.id_post_comments
                                LEFT JOIN user_name_random ON user_name_random.id_user_name_random=post_comments.id_user_name_random
                                where post_comments.id_posts='" . $post_id . "' group by post_comments.id_post_comments
					       ") or die(mysqli_error());

    $row = mysqli_fetch_assoc($qu);
    if(!empty($row)){
        foreach ($row as $key => $val) {
            $r[$key] = $val;
        }}


    /* if(empty($r['likes'])){
        $r['likes']=0;
    }if(empty($r['hug'])){
        $r['hug']=0;
    } */
    /* if(empty($r['same'])){
        $r['same']=0;
    }if(empty($r['listen'])){
        $r['listen']=0;
    } */

    if (strpos($r['location'], ',') !== false) {
        $loc = explode(',', $r['location']);
        $r['location'] = array("lat" => floatval($loc[0]), "lng" => floatval($loc[1]));
    } else {
        $r['location'] = NULL;
    }

    $records[] = $r;

}


function order_by_comments_like($a, $b)
{
    if ($a['comments'] == $b['comments']) {
        // comments is the same, sort by likes
        if ($a['likes'] == $b['likes']) return 0;
        return $a['likes'] < $b['likes'] ? 1 : -1;
    }

    // sort the higher score first:
    return $a['comments'] < $b['comments'] ? 1 : -1;
}

if (!empty($_GET['popular']))
    usort($records, 'order_by_comments_like');


echo json_encode($records);


?>
