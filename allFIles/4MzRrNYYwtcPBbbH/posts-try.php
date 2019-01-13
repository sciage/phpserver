<?php
require "init_new_config.php";

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
$user_id = 0;
$user_id = intval($_GET['user_id'] );


$post_ids = array();

$sql = "";
if(!empty($_GET['contacts']) && !empty($_GET['id_user_name'])){
    $sql = "SELECT id_posts FROM posts WHERE id_user_name IN (SELECT id_user_name FROM user_name WHERE phone_number IN ( SELECT DISTINCT phone_number FROM user_contacts WHERE id_user_name =  '".$_GET['id_user_name']."'))";
} else if(!empty($_GET['group_post']) && !empty($_GET['id_user_name'])){ /*filtered post but update needed this*/
    $sql = "SELECT id_posts FROM feeling_category WHERE id_user_name='".$_GET['id_user_name']."' union select id_posts from post_comments where id_user_name= '".$_GET['id_user_name']."' order by id_posts desc";
} else if(!empty($_GET['filtered']) && !empty($_GET['id_user_name'])){ /*group posts. but updated needed this */
    $sql = "SELECT id_posts	FROM posts WHERE group_id IN (SELECT group_id FROM candid_database.groups WHERE group_id IN (SELECT group_id FROM candid_database.groups_user where id_user_name = '".$_GET['id_user_name']."'))";
} else if(!empty($_GET['facebookId']) && !empty($_GET['id_user_name'])){
    $sql = "SELECT id_posts	FROM posts WHERE id_user_name IN (SELECT id_user_name FROM user_name WHERE userid IN (SELECT DISTINCT facebook_id	FROM facebook_friend_id	WHERE id_user_name =  '".$_GET['id_user_name']."'))";
} else if(!empty($_GET['popularPostId'])){
    $sql = "SELECT id_posts FROM posts WHERE id_posts IN (SELECT post_id as id_posts	FROM specific_post order by id_specific_post)";
}
if($sql != ""){
    $result = $mysqli->query($sql);
    if($result){
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $post_ids[]=$row[0];
            }
        }
    }
}
$_GET['id_posts']=implode(",", $post_ids);

if (!empty($_GET['id_posts'])) {
    $post_filter = "WHERE posts.id_posts IN (" . $_GET['id_posts'] . ")";
}elseif (!empty($_GET['id_user'])) {
    $post_filter = "WHERE posts.id_user_name=" . $_GET['id_user'];
}elseif (!empty($_GET['category_id'])) {
    $post_filter = "WHERE posts.group_id=" . $_GET['category_id'];
}elseif (!empty($_GET['follower'])) {
    $post_filter = "WHERE posts.id_user_name IN( SELECT user_id FROM tbl_follower WHERE followers='".$_GET['follower']."') ";
}elseif (!empty($_GET['timestamp'])) {
    $post_filter = "WHERE posts.post_time > " . $_GET['timestamp'];
}elseif (!empty($_GET['group_id'])) {
    $post_filter = "WHERE posts.group_id = '" . $_GET['group_id'] . "'";
}else {
    $post_filter = '';
}

$sql = "SELECT posts.id_posts, posts.id_user_name, posts.group_id, posts.post_time, posts.text_status, posts.audio_duration, posts.audio_file_link, posts.random_color_light as light_color, posts.random_color_dark as dark_color, posts.location, posts.adult_filter as adult_filter, posts.isImage as isImage, posts.image_url as image_url, posts.type as type, categories.category as category, posts.report_abuse_count, user_name.user_nick_name as user_nic_name, user_name.avatar_pics, user_name_random.id_user_name_random, user_name_random.username as user_name_random, user_name_random.avatar_url as avatar_url_random, groups.name as name, (SELECT feeling_likes FROM feeling_category WHERE id_user_name= $user_id and feeling_category.id_posts = posts.id_posts) as feeling_like, (SELECT count(feeling_likes) FROM feeling_category WHERE feeling_category.id_posts = posts.id_posts and feeling_likes = 1) as likes, (SELECT count(feeling_likes) FROM feeling_category WHERE feeling_category.id_posts = posts.id_posts and feeling_likes = 2) as hugs, (SELECT count(post_comments.id_post_comments) as comments  FROM candid_database.post_comments  where post_comments.id_posts = posts.id_posts) as total_comments, (SELECT count(post_comment_reply.id_post_comments) as comments_reply  FROM candid_database.post_comment_reply  where post_comment_reply.id_posts == posts.id_posts) as total_replies FROM posts LEFT JOIN user_name ON posts.id_user_name=user_name.id_user_name LEFT JOIN categories ON posts.id_categories= categories.id_categories LEFT JOIN user_name_random ON user_name_random.id_user_name_random=posts.id_user_name_random LEFT JOIN groups on posts.group_id = groups.group_id $post_filter ORDER BY posts.id_posts DESC $limit_clause";

$result = $mysqli->query($sql);
$records = array();
if($result){
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $row['user_like'] = 0;
            $row['user_Huge'] = 0;
            if($row['feeling_like'] == 1){
                $row['user_like'] = 1;
            }else{
                $row['user_hug'] = 1;
            }
            $post_id = $row['id_posts'];

            $qu = mysqli_query($con, "SELECT post_comments.message as post_comment, user_name_random.username as comment_reply, user_name_random.avatar_url as comment_avatar FROM post_comments LEFT JOIN post_comment_reply ON post_comment_reply.id_post_comments=post_comments.id_post_comments LEFT JOIN user_name_random ON user_name_random.id_user_name_random=post_comments.id_user_name_random where post_comments.id_posts='" . $post_id . "' group by post_comments.id_post_comments") or die(mysqli_error());

            $row = mysqli_fetch_assoc($qu);
            if(!empty($row)){
                foreach ($row as $key => $val) {
                    $r[$key] = $val;
                }
            }

            $records[] = $row;
        }
    }
}

function order_by_comments_like($a, $b){
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