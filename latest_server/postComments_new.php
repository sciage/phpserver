<?php
require_once("init_new_config.php");

/* DETERMINE THE COMMENTOR AND RANDOM USER NAME TO BE ASSIGNED */
// Extract creator of the post and random username assigned to the post
$sql = "SELECT id_user_name,user_name_random.id_user_name_random as id_user_name_random, 
				user_name_random.username as username,
				user_name_random.avatar_url as avatar_url FROM posts LEFT JOIN user_name_random 
				ON posts.id_user_name_random= user_name_random.id_user_name_random WHERE id_posts='" . intval($_POST['id_posts']) . "' LIMIT 1";

$sql = mysqli_query($con, $sql);
if (mysqli_num_rows($sql)) {
    $random_row = mysqli_fetch_row($sql);


    // If the commentor is the original poster himself, use the already assigned random name
    if ($random_row[0] == $_POST['id_user_name']) {

        $id_user_name_random = $random_row[1];
        $username = $random_row[2];
        $avatar_url = $random_row[3];
    } else {
        /* If not,
            Check if the calling user had previously inserted a comment, and extract the random username assigned to the comment.
        */
        $sql = "SELECT post_comments.id_user_name_random as id_user_name_random,
				user_name_random.username as username,
				user_name_random.avatar_url as avatar_url FROM post_comments LEFT JOIN user_name_random 
				ON post_comments.id_user_name_random= user_name_random.id_user_name_random
				 WHERE id_posts='" . intval($_POST['id_posts']) . "' AND id_user_name='" . intval($_POST['id_user_name']) . "' LIMIT 1";

        $sql = mysqli_query($con, $sql);

        // (RECORD FOUND) => The user has already commented on the post, so use the already assigned random name
        if (mysqli_num_rows($sql)) {
            $random_row = mysqli_fetch_row($sql);

            $id_user_name_random = $random_row[0];
            $username = $random_row[1];
            $avatar_url = $random_row[2];

        } else {
            /* If not,
                Check if the calling user had previously inserted a comment reply, and extract the random username assigned to the comment reply.
            */
            $sql = "SELECT post_comment_reply.id_user_name_random as id_user_name_random,
				user_name_random.username as username,
				user_name_random.avatar_url as avatar_url FROM post_comment_reply LEFT JOIN user_name_random 
				ON post_comment_reply.id_user_name_random = user_name_random.id_user_name_random WHERE id_posts='" . intval($_POST['id_posts']) . "' AND id_user_name='" . intval($_POST['id_user_name']) . "' LIMIT 1";

            $sql = mysqli_query($con, $sql);

            // (RECORD FOUND) => The user has already commented on the post, so use the already assigned random name
            if (mysqli_num_rows($sql)) {
                $random_row = mysqli_fetch_row($sql);

                $id_user_name_random = $random_row[0];
                $username = $random_row[1];
                $avatar_url = $random_row[2];

            } else {
                /* If not,
                    Obtain a new random  id_user_name_random from DB */
                $sql = "SELECT user_name_random.id_user_name_random as id_user_name_random FROM user_name_random WHERE user_name_random.id_user_name_random 
NOT IN ( SELECT id_user_name_random FROM posts WHERE id_posts='" . intval($_POST['id_posts']) . "') 
UNION ( SELECT id_user_name_random FROM post_comments WHERE id_posts='" . intval($_POST['id_posts']) . "' ) 
UNION ( SELECT id_user_name_random FROM post_comment_reply WHERE id_posts='" . intval($_POST['id_posts']) . "' )  ORDER BY RAND() LIMIT 1";

                $sql = mysqli_query($con, $sql);
                $random_row = mysqli_fetch_row($sql);
                $id_user_name_random = $random_row[0];


                $sql02 = "SELECT id_user_name_random, username, avatar_url FROM user_name_random where id_user_name_random = $id_user_name_random";

                $sql02 = mysqli_query($con, $sql02);

                // (RECORD FOUND) => The user has already commented on the post, so use the already assigned random name
                if (mysqli_num_rows($sql02)) {
                    $random_row = mysqli_fetch_row($sql02);

                    $id_user_name_random = $random_row[0];
                    $username = $random_row[1];
                    $avatar_url = $random_row[2];

                }
            }
        }

    }

}

/* if( empty($id_user_name_random) ){
     $json = array("status" => 0, "msg"=>"id_user_name_random erorr occured");
     die(json_encode($json));
 }

 if( empty($username) ){
     $json = array("status" => 0, "msg"=>"username erorr occured");
     die(json_encode($json));
 }

 if( empty($avatar_url) ){
     $json = array("status" => 0, "msg"=>"avatar_url erorr occured");
     die(json_encode($json));
 } */

$date = date_create();
$post_date = date_timestamp_get($date) * 1000;
//  $post_date =  round(microtime(true) * 1000)

// random color insertion
$randomNumber = rand(1, 9);
$sql = "SELECT light_color, dark_color FROM color_table where id_color_table = '$randomNumber'";
$sql = mysqli_query($con, $sql);
$random_row_value = mysqli_fetch_row($sql);
$light_color = $random_row_value[0];
$dark_color = $random_row_value[1];


$id_post_comments = isset($_POST['id_post_comments']) ? mysqli_real_escape_string($con, $_POST['id_post_comments']) : ""; // id of the comment who got reply
$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";  // id of the user who commented the post
$id_post_user_name = isset($_POST['id_post_user_name']) ? mysqli_real_escape_string($con, $_POST['id_post_user_name']) : "";  // who posted the post
$id_posts = isset($_POST['id_posts']) ? mysqli_real_escape_string($con, $_POST['id_posts']) : ""; // id of the post
$message = isset($_POST['message']) ? mysqli_real_escape_string($con, $_POST['message']) : "";


$sql = mysqli_query($con, "INSERT INTO `post_comments` (`id_user_name`, `id_posts`,  `id_post_user_name` , `id_user_name_random`, `message`, `comment_time`,`level`,`random_color_light`,`random_color_dark`) VALUES ('" . intval($id_user_name) . "', '" . intval($id_posts) . "', '" . intval($id_post_user_name) . "' , '" . intval($id_user_name_random) . "', '$message','$post_date','0','$light_color','$dark_color')") or die(mysqli_error($con));


if ($sql){
    mysqli_set_charset($con, 'utf8');
    $sql = "SELECT unr.username as user_name, 
u.id_posts as id_posts, unr.id_user_name_random as id_user_name_random, 
(select count(id_post_comments_likes) from post_comments_likes where id_post_comment = 
c.id_post_comments) as comment_likes, (SELECT comment_likes FROM post_comments_likes 
where id_user_name = $id_user_name AND id_post_comment = c.id_post_comments) as post_comment_like_true, 
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
            // echo json_encode("No Comments");

            echo json_encode($Comments);

            //    return_responce(false, array('Comments'=> array()), "No Comments Found.", $action = "");
            die();
        }
    }else{
        // echo json_encode("No Comments");
        echo json_encode($Comments);


        //  return_responce(false, array('groups' => array()), "Unable to Connect Server", $action = "");
        die();
    }
}



/* Output header */
// header('Content-type:application/json');
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
