<?php
// header('Content-type: application/json');

// Include config file
require_once("init_new_config.php");

$name = isset($_POST['name']) ? mysqli_real_escape_string($con, $_POST['name']) : "";
$email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : "blank";
$gender = isset($_POST['gender']) ? mysqli_real_escape_string($con, $_POST['gender']) : "4";
$user_date_of_birth = isset($_POST['user_date_of_birth']) ? mysqli_real_escape_string($con, $_POST['user_date_of_birth']) : "blank";
$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "null";
$socialNetwork = isset($_POST['socialNetwork']) ? mysqli_real_escape_string($con, $_POST['socialNetwork']) : 0;
$date = date_create();
$created = date_timestamp_get($date);
$token = rand(8, 10);
$randomuserID = rand(1000000, 100000000);
$bytes = bin2hex(random_bytes($token));

$deviceId = isset($_POST['deviceId']) ? mysqli_real_escape_string($con, $_POST['deviceId']) : $bytes;

$email_id_duplicate = mysqli_query($con, "SELECT `id_user_name` FROM `user_name` WHERE `userid` ='$user_id' && `email` ='$email' ");

if (mysqli_num_rows($email_id_duplicate) > 0) {

    mysqli_query($con, "UPDATE `user_name` SET  `name`='$name',`email`='$email',`userid` ='$user_id',`user_registered`='$created',`gender`='$gender',`user_date_of_birth`='$user_date_of_birth', `user_token`='$bytes',`deviceId`='$deviceId',`socialNetwork`='$socialNetwork' WHERE `userid` ='$user_id' && `email` ='$email' LIMIT 1");

    $duplicates = mysqli_query($con, "SELECT * FROM `user_name` WHERE `userid` ='$user_id'");
    $row = mysqli_fetch_array($duplicates);

    $result = array(
        "id" => $row['id_user_name'],
        "name" => $row['user_nick_name'],
        "contact" => $row['givenContact'],
        "user_id" => $row['userid'],
        "user_token" => $row['user_token'],
        "facebookid" => $row['given_facebook'],
        "present" => "yes",
        "imageurl" => $row['avatar_pics'],

    );
    $json = array("status" => 1, "info" => $result);

    mysqli_close($con);
    /* Output header */
    die(json_encode($json));
} else {

    $device_id_duplicate = mysqli_query($con, "SELECT `id_user_name` FROM `user_name` WHERE `deviceId` ='$deviceId' limit 1");

    if (mysqli_num_rows($device_id_duplicate) > 0) {

        mysqli_query($con, "UPDATE `user_name` SET  `name`='$name',`email`='$email',  `userid` ='$randomuserID', `user_registered`='$created', `gender`='$gender',`user_date_of_birth`='$user_date_of_birth',`user_token`='$bytes' ,`deviceId`='$deviceId',`socialNetwork`='$socialNetwork'  WHERE `deviceId` ='$deviceId' LIMIT 1");

        $duplicates = mysqli_query($con, "SELECT * FROM `user_name` WHERE `deviceId` ='$deviceId'");
        $row = mysqli_fetch_array($duplicates);

        $result = array(
            "id" => $row['id_user_name'],
            "name" => $row['user_nick_name'],
            "contact" => $row['givenContact'],
            "user_id" => $row['userid'],
            "user_token" => $row['user_token'],
            "facebookid" => $row['given_facebook'],
            "present" => "yes",
            "imageurl" => $row['avatar_pics'],

        );
        $json = array("status" => 1, "info" => $result);

        mysqli_close($con);
        /* Output header */
        die(json_encode($json));
    } else {

        if ( isset($_POST['user_id'])) {
            // Insert data into data base                                                                                      `about_me`='$about_me', `user_nick_name`='$user_nick_name',
            $sql = "INSERT INTO `user_name`( `name`, `email`, `userid`, `user_token`,`gender`,`user_date_of_birth`, `user_registered`,`deviceId`,`socialNetwork` ) VALUES ('$name','$email','$user_id', '$bytes','$gender','$user_date_of_birth','$created','$deviceId','$socialNetwork')";
            $qur = mysqli_query($con, $sql);

            if ($qur) {
                $new_user_id = mysqli_insert_id($con);
                $duplicates = mysqli_query($con, "SELECT * FROM `user_name` WHERE id_user_name='$new_user_id'");
                $row = mysqli_fetch_array($duplicates);
                $result = array(
                    "id" => $row['id_user_name'],
                    "name" => $row['user_nick_name'],
                    "contact" => $row['givenContact'],
                    "user_id" => $row['userid'],
                    "user_token" => $row['user_token'],
                    "facebookid" => $row['given_facebook'],
                    "present" => "yes",
                    "imageurl" => $row['avatar_pics']
                );
                $json = array("status" => 1, "info" => $result);
            } else {
                $json = array("status" => 0, "error" => "Couldnt insert user");
            }

            die(json_encode($json));
        } else {
// Insert data into data base                                                                                      `about_me`='$about_me', `user_nick_name`='$user_nick_name',
            $sql = "INSERT INTO `user_name`( `name`, `email`, `userid`, `user_token`,`gender`,`user_date_of_birth`, `user_registered`,`deviceId`,`socialNetwork` ) VALUES ('$name','$email', '$randomuserID', '$bytes','$gender','$user_date_of_birth','$created','$deviceId','$socialNetwork')";
            $qur = mysqli_query($con, $sql);

            if ($qur) {
                $new_user_id = mysqli_insert_id($con);
                $duplicates = mysqli_query($con, "SELECT * FROM `user_name` WHERE id_user_name='$new_user_id'");
                $row = mysqli_fetch_array($duplicates);
                $result = array(
                    "id" => $row['id_user_name'],
                    "name" => $row['user_nick_name'],
                    "contact" => $row['givenContact'],
                    "user_id" => $row['userid'],
                    "user_token" => $row['user_token'],
                    "facebookid" => $row['given_facebook'],
                    "present" => "yes",
                    "imageurl" => $row['avatar_pics']
                );
                $json = array("status" => 1, "info" => $result);
            } else {
                $json = array("status" => 0, "error" => "Couldnt insert user");
            }

            die(json_encode($json));

        }


    }


}


?>