<?php
require_once("init_new_config.php");

if (empty($_GET['id_user_name']) || empty($_GET['id_posts'])) {
    echo '{"status":"Invalid request"}';
    die();
}

/* DETERMINE THE COMMENTOR AND RANDOM USER NAME TO BE ASSIGNED */
// Extract creator of the post and random username assigned to the post
$sql = "SELECT id_user_name,user_name_random.id_user_name_random as id_user_name_random, 
				user_name_random.username as username,
				user_name_random.avatar_url as avatar_url FROM posts LEFT JOIN user_name_random 
				ON posts.id_user_name_random= user_name_random.id_user_name_random WHERE id_posts='" . intval($_GET['id_posts']) . "' LIMIT 1";

$sql = mysqli_query($con, $sql);
if (mysqli_num_rows($sql)) {
    $random_row = mysqli_fetch_row($sql);

    // If the commentor is the original poster himself, use the already assigned random name
    if ($random_row[0] == $_GET['id_user_name']) {
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
				 WHERE id_posts='" . intval($_GET['id_posts']) . "' AND id_user_name='" . intval($_GET['id_user_name']) . "' LIMIT 1";

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
				ON post_comment_reply.id_user_name_random = user_name_random.id_user_name_random WHERE id_posts='" . intval($_GET['id_posts']) . "' AND id_user_name='" . intval($_GET['id_user_name']) . "' LIMIT 1";

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
NOT IN ( SELECT id_user_name_random FROM posts WHERE id_posts='" . intval($_GET['id_posts']) . "') 
UNION ( SELECT id_user_name_random FROM post_comments WHERE id_posts='" . intval($_GET['id_posts']) . "' ) 
UNION ( SELECT id_user_name_random FROM post_comment_reply WHERE id_posts='" . intval($_GET['id_posts']) . "' )  ORDER BY RAND() LIMIT 1";

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

if ($sql) {
    $json = array("status" => 1, 'id_user_name_random' => $id_user_name_random, 'username' => $username, 'avatar_url' => $avatar_url);
}


/* Output header */
// header('Content-type:application/json');
echo json_encode($json);
?>
