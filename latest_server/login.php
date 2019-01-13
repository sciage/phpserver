<?php
//require_once("init_new_config.php");
require_once("init_new_config_local.php");

/*
$name = isset($_POST['name']) ? mysqli_real_escape_string($con, $_POST['name']) : "";
$email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : "blank";
$user_id = isset($_POST['user_id']) ? mysqli_real_escape_string($con, $_POST['user_id']) : "null";
$socialNetwork = isset($_POST['socialNetwork']) ? mysqli_real_escape_string($con, $_POST['socialNetwork']) : 0;
*/

$token = rand(6, 8);
$random_device = bin2hex(random_bytes($token));
$randomuserID = rand(1000000, 100000000);

// email random value
$email_rand = rand(4, 7);
$random_email = bin2hex(random_bytes($email_rand));


$name = isset($_GET['name']) ? mysqli_real_escape_string($con, $_GET['name']) : NULL;
$email = isset($_GET['email']) ? mysqli_real_escape_string($con, $_GET['email']) : $random_email;
$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : $randomuserID;
$socialNetwork = isset($_GET['socialNetwork']) ? mysqli_real_escape_string($con, $_GET['socialNetwork']) : 0;
$date = date_create();
$created = date_timestamp_get($date);

$deviceId = isset($_GET['deviceId']) ? mysqli_real_escape_string($con, $_GET['deviceId']) : $random_device;
//$deviceId = isset($_POST['deviceId']) ? mysqli_real_escape_string($con, $_POST['deviceId']) : $bytes;
$sql = "SELECT * FROM `user_name` WHERE `userid` ='$user_id' && `email` ='$email' ";
$result = $con->query($sql);
if($result){
	if($result->num_rows > 0){
		$user = $result->fetch_assoc();
		$sql = "UPDATE `user_name` SET  `name`='$name',`email`='$email',`userid` ='$user_id',`user_registered`='$created', `user_token`='$random_device',`deviceId`='$deviceId',`socialNetwork`='$socialNetwork' WHERE `userid` ='$user_id' && `email` ='$email' LIMIT 1";
		if($con->query($sql)){
			$user = array(
				"id" => $user['id_user_name'],
				"name" => $user['user_nick_name'],
				"contact" => $user['givenContact'],
				"user_id" => $user['userid'],
				"user_token" => $user['user_token'],
				"facebookid" => $user['given_facebook'],
				"present" => "yes",
				"imageurl" => $user['avatar_pics']
			);

            $json = array("status" => 1, "info" => $user);

            mysqli_close($con);
            /* Output header */
            die(json_encode($json));


         //   return_responce(true, array('user' => $user), "Success", $action = "");
		}
	}else{
		$sql = "SELECT `id_user_name` FROM `user_name` WHERE `deviceId` ='$deviceId' limit 1";
		$result = $con->query($sql);
		if($result){
			if($result->num_rows > 0){
				$user = $result->fetch_assoc();
			//	$sql = "UPDATE `user_name` SET  `name`='$name',`email`='$email',  `userid` ='$randomuserID', `user_registered`='$created', `user_token`='$random_device' ,`deviceId`='$deviceId',`socialNetwork`='$socialNetwork'  WHERE `deviceId` ='$deviceId' LIMIT 1";
				if($con->query($sql)){
					$user = array(
						"id" => $user['id_user_name'],
						"name" => $user['user_nick_name'],
						"contact" => $user['givenContact'],
						"user_id" => $user['userid'],
						"user_token" => $user['user_token'],
						"facebookid" => $user['given_facebook'],
						"present" => "yes",
						"imageurl" => $user['avatar_pics']
					);

                    $json = array("status" => 1, "info" => $user);

                    mysqli_close($con);
                    /* Output header */
                    die(json_encode($json));

				//	return_responce(true, array('user' => $user), "Success", $action = "");
				}
			}else{
				$new_user_id = 0;
				if($user_id != NULL){
					$sql = "INSERT INTO `user_name`( `name`, `email`, `userid`, `user_token`, `user_registered`,`deviceId`,`socialNetwork` ) VALUES ('$name','$email','$user_id', '$random_device','$created','$deviceId','$socialNetwork')";
				}else{
					$sql = "INSERT INTO `user_name`( `name`, `email`, `userid`, `user_token`, `user_registered`,`deviceId`,`socialNetwork` ) VALUES ('$name','$email', '$randomuserID', '$random_device','$created','$deviceId','$socialNetwork')";
				}
				$result = $con->query($sql);
				if($result){
					$new_user_id = $con->insert_id;
					$sql = "select * from `user_name` where id_user_name = $new_user_id";
                    $resultnew = $con->query($sql);

                    if($resultnew){
						if($resultnew->num_rows > 0){
							$user = $resultnew->fetch_assoc();
							$user = array(
								"id" => $user['id_user_name'],
								"name" => $user['user_nick_name'],
								"contact" => $user['givenContact'],
								"user_id" => $user['userid'],
								"user_token" => $user['user_token'],
								"facebookid" => $user['given_facebook'],
								"present" => "yes",
								"imageurl" => $user['avatar_pics']
							);

                            $json = array("status" => 1, "info" => $user);

                            mysqli_close($con);
                            /* Output header */
                            die(json_encode($json));

                        //    $json = array("status" => true, "info" => $user);

                        //    return_responce(true, array('user' => $user), "Success", $action = "");
						}else{
                            $json = array("status" => false, "error" => "Couldnt insert user");

                         //   return_responce(false, array(), "No User Found", $action = "");
						}
					}else{
                        $json = array("status" => false, "error" => "Unable to Connect Server");

                   //     return_responce(false, array(), "Unable to Connect Server", $action = "");
					}
				}else{
                    $json = array("status" => false, "error" => "Unable to Register New User");

              //      return_responce(false, array(), "Unable to Register New User", $action = "");
				}
			}
		}else{
            $json = array("status" => false, "error" => "Unable to Connect Server");

          //  return_responce(false, array(), "Unable to Connect Server", $action = "");
		}
	}
}else{
    $json = array("status" => false, "error" => "Unable to Connect Server");

 //   return_responce(false, array(), $message = "Unable to Reach Server.", $action = "");
}
?>