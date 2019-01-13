<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 21/12/18
 * Time: 7:35 AM
 */

//require_once("init_new_config.php");// DONE verified
//require_once("init_new_config.php");
require_once("init_new_config.php");

function update_user_token($user_id, $token){
    global $con;

    $getToken = mysqli_query ($con,"SELECT pushnotificationToken FROM candid_database.user_name where `id_user_name` = '$user_id'")or die(mysqli_error($con));

    if (mysqli_num_rows($getToken) > 0) {

        $pushnotificationToken = $getToken->fetch_object()->pushnotificationToken;

        if ($token != $pushnotificationToken){

            $sql = mysqli_query($con, "UPDATE `user_name` SET `pushnotificationToken`= '" . $token . "' WHERE id_user_name='" . $user_id . "' LIMIT 1");

            if ($sql) {

                $resp = array('success' => true);

            } else {
                $resp = array('success' => false);

            }
        } else {
            $resp = array('success' => false);

        }


    //    die(json_encode($resp));


    }
    return json_encode($resp);


}

?>
