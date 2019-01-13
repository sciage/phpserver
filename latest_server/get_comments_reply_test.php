<?php
require "init_new_config.php";

$user_id = 0;
if(!isset($_GET['user_id'])){
	return_responce(false, array(), "User Authentication Failed", $action = "");
	die();
}else{
	$user_id = intval($_GET['user_id'] );
}
$id_posts = 0;
if(!isset($_GET['id_posts'])){
	return_responce(false, array(), "No Post Found", $action = "");
	die();
}else{
	$id_posts = intval($_GET['id_posts'] );
}

$usersList_array = array();
$user_array = array();
$note_array = array();

$limit_clause = '';
if( !empty($_GET['page']) ){
	$page = $_GET['page'];
	$limit = 15;
	$left = $page*$limit - $limit;
	$limit_clause=" LIMIT ".$left.",".$limit;
}else{
    $limit = 15;
	$page = 1;
	$limit_clause=" LIMIT 0 ,".$limit;
}
mysqli_set_charset($con, 'utf8');
$sql = "SELECT unr.username as user_name, 
u.id_posts as id_posts, unr.id_user_name_random as id_user_name_random, 
(select count(id_post_comments_likes) from post_comments_likes where id_post_comment = 
c.id_post_comments) as comment_likes, (SELECT comment_likes FROM post_comments_likes 
where id_user_name = $user_id AND id_post_comment = c.id_post_comments) as post_comment_like_true, 
c.id_post_comments as commentId, pcl.post_comment_id = post_comment_id as post_comment_id, 
u.id_user_name as postUserId, unr.avatar_url as avatar, c.message as comment,	
c.id_post_user_name as id_post_user_name, c.id_user_name as commentUserId, 
c.comment_time as comment_time FROM post_comments c LEFT JOIN posts u ON u.id_posts=c.id_posts 
LEFT JOIN post_comments_likes as pcl ON pcl.id_post_comment = c.id_post_comments 
LEFT JOIN user_name_random as unr ON unr.id_user_name_random =c.id_user_name_random	
WHERE c.id_posts = $id_posts ORDER BY c.comment_time asc ";

$result = $con->query($sql);
$Comments = array();
if($result){
	if($result->num_rows  > 0){
		while($row = $result->fetch_assoc()){
			$row['reply'] = get_reply($row['commentId']);
			array_push($Comments, $row);
		}
	//	return_responce(true, array('Comments'=> $Comments), "Success", $action = "");
        echo json_encode($Comments);

		die();
	}else{

        echo json_encode("No Comments");

    //    return_responce(false, array('Comments'=> array()), "No Comments Found.", $action = "");
		die();		
	}
}else{
    echo json_encode("No Comments");

  //  return_responce(false, array('groups' => array()), "Unable to Connect Server", $action = "");
	die();
}

function get_reply($commentId){
	global $con;
	$sql = "SELECT (SELECT username FROM user_name_random 
        where id_user_name_random = pcr.id_user_name_random) as user_name_reply, 
        pcr.id_posts as id_posts, pcr.id_user_name_random as id_user_name_random, 
        (SELECT count(id_post_comment_reply_likes) FROM post_comment_reply_likes where id_post_comment_reply = 
        pcr.id_post_comment_reply) as comment_likes, (SELECT likes FROM post_comment_reply_likes 
        where id_user_name = pcr.id_user_name AND id_post_comment_reply = pcr.id_post_comment_reply) 
        as comment_likes_true, pcr.id_post_comments as  id_post_comments, 
        pcr.id_post_comment_reply as  id_post_comment_reply, 
        (SELECT username FROM user_name_random where id_user_name_random = pcr.id_user_name_random) as 
        user_name, unr.avatar_url as avatar, pcr.id_user_name as id_user_name, 
        pcr.id_post_user_name as id_post_user_name, pcr.message as message, 
        crl.id_post_comment_reply as id_post_comment_reply, pcr.comment_time as comment_time 
        FROM post_comment_reply pcr LEFT JOIN user_name u ON u.id_user_name=pcr.id_user_name	
        LEFT JOIN post_comment_reply_likes as crl ON crl.id_post_comment_reply = pcr.id_post_comment_reply 
        LEFT JOIN user_name_random as unr ON unr.id_user_name_random = pcr.id_user_name_random 
        WHERE id_post_comments = $commentId ORDER BY pcr.comment_time asc";
	$Reply = array();
	$result = $con->query($sql);
	if($result and $result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			array_push($Reply, $row);
		}
	}
	return $Reply;
}
?>