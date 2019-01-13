<?php
require_once("init_new_config.php");


if (empty($_POST['id_post_comments']) || empty($_POST['id_user_name']) || empty($_POST['id_posts']) || empty($_POST['id_post_user_name']) || empty($_POST['message'])) {
    echo '{"status":"Invalid request"}';
    die();
}

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


                $sql02 = "SELECT id_user_name_random, username, avatar_url FROM candid_database.user_name_random where id_user_name_random = $id_user_name_random";

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


$sql = mysqli_query($con, "INSERT INTO `post_comment_reply`
								(`id_post_comments`, `id_user_name`,  `id_post_user_name` , `id_posts`, `id_user_name_random`,  `message`, `comment_time`,`random_color_light`,`random_color_dark`     )
								VALUES ('$id_post_comments', '$id_user_name', '$id_post_user_name', '$id_posts', '$id_user_name_random',  '$message','$post_date','$light_color','$dark_color')") or die(mysqli_error($con));

$cat_id = mysqli_insert_id($con);

if ($sql) {
    $json = array("status" => 1, 'id' => $cat_id, 'id_user_name_random' => $id_user_name_random, 'username' => $username, 'avatar_url' => $avatar_url, 'message' => $message);
}


/* Output header */
// header('Content-type:application/json');
echo json_encode($json);
?>
