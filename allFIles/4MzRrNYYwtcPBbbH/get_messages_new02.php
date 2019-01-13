<?php
	require_once("init_new_config.php");

$fromUserId = isset($_GET['from_user_id']) ? mysqli_real_escape_string($con, $_GET['from_user_id']) : "";
$toUserId = isset($_GET['to_user_id']) ? mysqli_real_escape_string($con, $_GET['to_user_id']) : "";
$user_id = isset($_GET['user_id']) ? mysqli_real_escape_string($con, $_GET['user_id']) : "";


	$stmt="SELECT Messages.messageId as Id, Messages.id_posts as id_posts, Messages.chatImage as chatImage, Messages.senderAnonymous as senderAnonymous, 
			Messages.chatText as Text, Messages.receiverAnonymous as receiverAnonymous,
			Messages.custom_user_name as custom_user_name,
			            user_name_random.avatar_url as avatar_url,
			Messages.time as CreatedAt, senderUser.id_user_name as senderUser_id,senderUser.name as senderUser_Name,
			senderUser.onlineStatus as senderUser_isOnline
			FROM chat_messages Messages LEFT JOIN user_name senderUser ON Messages.senderId = senderUser.id_user_name
			left join user_name_random on Messages.senderAnonymous = user_name_random.id_user_name_random
			WHERE ( Messages.senderId='".$fromUserId."' AND Messages.receiverId='".$toUserId."' ) OR
			( Messages.senderId='".$toUserId."' AND Messages.receiverId='".$fromUserId."' )";

	$q=mysqli_query($con,$stmt);

	$json=array();
	while( $row= mysqli_fetch_assoc($q) ){

		$sender_user=array(
				"Id"=> $row['senderUser_id'],
				"Name"=> $row['senderUser_Name'],
				"Avatar"=> $row['avatar_url'],
            "id_posts"=> $row['id_posts'],
            "custom_user_name"=> $row['custom_user_name'],
            "senderAnonymous"=> $row['senderAnonymous'],
				"receiverAnonymous"=> $row['receiverAnonymous'],
				"isOnline"=> $row['senderUser_isOnline']
		);

		$chatImage=array(
				"url"=> $row['chatImage']
		);

		$message=array(
				"Id"=> $row['Id'],
				"Text"=> $row['Text'],
            "id_posts"=> $row['id_posts'],
            "chatImage"=> $chatImage,
				"CreatedAt"=> $row['CreatedAt'],
				"User"=> $sender_user
		);

		$json[]=$message;
		
	}
	echo json_encode($json);

/* Output header */
// header('Content-type:application/json');

?>
