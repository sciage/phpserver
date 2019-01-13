<?php
require "init_new_config.php";

if(!empty($_GET['contacts']) && !empty($_GET['id_user_name'])){
    $sql = "SELECT id_posts FROM posts WHERE id_user_name IN ( SELECT id_user_name FROM user_name WHERE phone_number IN ( SELECT DISTINCT phone_number FROM user_contacts WHERE id_user_name =  '".$_GET['id_user_name']."'))";
}else if(!empty($_GET['group_post']) && !empty($_GET['id_user_name'])){
    $sql = "SELECT id_posts FROM feeling_category WHERE id_user_name='".$_GET['id_user_name']."' union select id_posts from post_comments where id_user_name= '".$_GET['id_user_name']."' order by id_posts desc";
}else if(!empty($_GET['filtered']) && !empty($_GET['id_user_name'])){ /*group posts. but updated needed this */
    $sql = "SELECT id_posts FROM posts WHERE group_id IN (SELECT group_id FROM candid_database.groups WHERE group_id IN (SELECT group_id FROM candid_database.groups_user where id_user_name = '".$_GET['id_user_name']."'))";
}else if(!empty($_GET['facebookId']) && !empty($_GET['id_user_name'])){
    $sql = "SELECT id_posts	FROM posts WHERE id_user_name IN (SELECT id_user_name FROM user_name WHERE userid IN (SELECT DISTINCT facebook_id	FROM facebook_friend_id	WHERE id_user_name =  '".$_GET['id_user_name']."'))";
}else if(!empty($_GET['popularPostId'])){
    $sql = "SELECT id_posts FROM posts WHERE id_posts IN (SELECT post_id as id_posts FROM specific_post order by id_specific_post)";
}else{
    die('[]');
}
$post_ids = array();
$result = $mysqli->query($sql);
if($result and $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $post_ids[] = $row[0];
    }
}
if(count($post_ids)){
    $_GET['id_posts'] = implode(",", $post_ids);
}else{
    die('[]');
}

/* Return post data for all the given ids
   @param
    =>	$_GET['id_posts'] : comma seperated list of post ids whose data is required
*/
if (!empty($_GET['id_posts'])) {
    $post_filter = " WHERE posts.id_posts IN (" . $_GET['id_posts'] . ")";
}
elseif (!empty($_GET['id_user'])) {
    $post_filter = " WHERE posts.id_user_name=" . $_GET['id_user'];
}
elseif (!empty($_GET['category_id'])) {
    $post_filter = "WHERE posts.group_id=" . $_GET['category_id'];
} elseif (!empty($_GET['group_id'])) {
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

        $Huge = mysqli_query($con, "SELECT id_feeling_category FROM feeling_category WHERE id_user_name='" . $_GET['user_id'] . "' and id_posts='" . $post_id . "' and feeling_hug = '1'");
        $rHuge = mysqli_fetch_assoc($Huge);
        if ($rHuge['id_feeling_category'] != '') {
            $iValueHuge = true;
        } else {
            $iValueHuge = false;
        }

        $r['user_like'] = $iValueLike;
        $r['user_Huge'] = $iValueHuge;
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
