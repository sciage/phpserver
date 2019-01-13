<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 07/10/18
 * Time: 10:14 AM
 */

require "init_new_config.php";

$age_range = isset($_POST['age_range']) ? mysqli_real_escape_string($con, $_POST['age_range']) : "6";
$gender = isset($_POST['gender']) ? mysqli_real_escape_string($con, $_POST['gender']) : "4";
$adult_filter = isset($_POST['adult_filter']) ? mysqli_real_escape_string($con, $_POST['adult_filter']) : "0";
$block_premium_search = isset($_POST['block_premium_search']) ? mysqli_real_escape_string($con, $_POST['block_premium_search']) : "0";
$id_user_name = isset($_POST['id_user_name']) ? mysqli_real_escape_string($con, $_POST['id_user_name']) : "";

// Attempt insert query execution
$sql = "UPDATE user_name SET gender='".$gender."',user_date_of_birth='".$age_range."', block_premium_search='".$block_premium_search."', adult_filter='".$adult_filter."' WHERE id_user_name = '".$id_user_name."'; ";
if(mysqli_query($con, $sql)){
    // Obtain last inserted id
    $resp = array('success' => true);
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
}

die(json_encode($resp));


// Close connection
?>