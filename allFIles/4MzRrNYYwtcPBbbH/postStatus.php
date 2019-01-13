<?php
    require_once("init_new_config.php");

if( $_POST['user_id'] == '20420'){
    echo '{"status":"Invalid request"}';
    die();
}

    $user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "";
    $cat_id = isset($_POST['cat_id']) ? mysqli_real_escape_string($con, $_POST['cat_id']) : "";
    $image_url = isset($_POST['image_url']) ? mysqli_real_escape_string($con, $_POST['image_url']) : "";
    $post_text = $_POST['post_text'];
$adult_filter = isset($_POST['adult_filter']) ? mysqli_real_escape_string($con, $_POST['adult_filter']) : "false";

$feeling_id = isset($_POST['feeling_id']) ? mysqli_real_escape_string($con, $_POST['feeling_id']) : "";
    $audio = isset($_POST['audio']) ? mysqli_real_escape_string($con, $_POST['audio']) : "";
    $audio_duration = isset($_POST['audio_duration']) ? mysqli_real_escape_string($con, $_POST['audio_duration']) : "";
    $location = isset($_POST['location']) ? mysqli_real_escape_string($con, $_POST['location']) : "";
    $group_id = isset($_POST['group_id']) && is_numeric($_POST['group_id']) ? intval($_POST['group_id']) : "";
    $type = isset($_POST['type']) && is_numeric($_POST['type']) ? intval($_POST['type']) : "0";

    $date = date_create();
    $post_date = date_timestamp_get($date) * 1000;
  //  $post_date =  round(microtime(true) * 1000)

    // Obtain a random  id_user_name_random from DB
    $id_user_name_random = rand(1,55);

    // random color insertion
    $randomNumber = rand(1,9);
    $sql = "SELECT light_color, dark_color FROM color_table where id_color_table = '$randomNumber'";
    $sql = mysqli_query($con,$sql);
    $random_row_value = mysqli_fetch_row($sql);
    $light_color = $random_row_value[0];
    $dark_color = $random_row_value[1];

    /* $sql = mysqli_query ($con,"INSERT INTO `posts`(`id_user_name`, `id_feeling_table`, `id_categories`, `text_status`, `audio_duration`, `audio_file_link`, `post_time`)
    VALUES ('$user_id','$feeling_id','$cat_id','$post_text','$audio_duration','$audio','$post_date')") or die(mysqli_error($con)); */


$sql = mysqli_prepare($con, "INSERT INTO `posts`(`id_user_name`, `id_feeling_table`, `id_categories`, `id_user_name_random`, `group_id`,`text_status`, `image_url`,`audio_duration`,
 `audio_file_link`, `location`, `post_time`,`random_color_light`, `random_color_dark`,`type`,`adult_filter`)  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");

    if ($sql) {
        mysqli_stmt_bind_param($sql, "iiiiisssssissis", $user_id, $feeling_id, $cat_id, $id_user_name_random, $group_id,  $post_text, $image_url, $audio_duration, $audio, $location, $post_date, $light_color, $dark_color,$type,$adult_filter);
        mysqli_stmt_execute($sql);

        $json = array("status" => 1, "msg" => "Success");
        mysqli_stmt_close($sql);

    } else {
        $json = array("status" => 0);
    }


/* Output header */
// header('Content-type:application/json');
echo json_encode($json);


?>
