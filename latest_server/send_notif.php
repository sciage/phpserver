<?php

//	define("FCM_AUTH_KEY", "AAAADG3uq_Q:APA91bESlOMG-w-3D12ET82UGG-KlkdWoTUA-JMTU_QpE5wmgyhBTiylTAa2JEKyNjjINkqADEGzwVqydz9HnnxMkM2UOU_PVqCrfaFIFPpPmWs4-Cq4HVYsgTcBzDvXAQcX3Km6e-k7");
	define("FCM_AUTH_KEY", "AAAAJX8Ssr4:APA91bE-ANuvMyRd-NZTy9_itbom8RRp_X4vtdycmwglZf2SlcsHHNQT5XKWTawEucUZ_46WvhuOZLmhi8sACVhn0eauyTziOBPtWVPHS1MtB6t3G4gtoyENKBtAtAsOrSNaDIGwCIPD");

	function sendFCM( $sub_id, $notification, $data) {
		$url = 'https://fcm.googleapis.com/fcm/send';
		
		$fields = array (
			'to' => $sub_id,
			'notification' => $notification,
			'data' => $data,
			"ttl" => 3600
		);
		$fields = json_encode ( $fields );

		$headers = array (
			'Authorization: key=' . FCM_AUTH_KEY,
			'Content-Type: application/json'
		);

		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

		$result = curl_exec ( $ch );
		curl_close ( $ch );

		return json_decode($result);
	}

	function testNotif(){
		if( empty($_GET['to']) && !empty($_GET['topic']) ) {
			testNotifToTopic();
			return;
		}
		$sub_id = !empty($_GET['to']) ? $_GET['to'] : "dBslGczhvUQ:APA91bE3r8unzf0V3OrqB4lFbO-QIuHJen6eF7XH-IQGm28cuxOsLVrBmRAe2qhNjvpXUwIxe-1xt3p7A8t0F963pOBb0m9Zva8uyzBYzdU8z_j4OEg7LXgqh2P5dctlrGqV95UBdWvF";
		$notification = array (
					"title" => "Someone liked you post !",
					"body" => "Tap to check it out",
					"click_action" => "SHOW_POST_SCREEN",
					"sound" => true
				);
		$notification_data = array (
					"notification_type" => "post",
					"event_type" => "post_like",
					"post_id" => 129
				);

		$fcm_result = sendFCM($sub_id , $notification, $notification_data);

		echo json_encode($fcm_result);
	}
	function testNotifToTopic(){
		if( !empty($_GET['to']) && empty($_GET['topic']) ) {
			testNotif();
			return;
		}
		$topic = !empty($_GET['topic']) ? $_GET['topic'] : "/topics/global";
		$notification = array (
					"title" => "Someone liked you post !",
					"body" => "Tap to check it out",
					"click_action" => "SHOW_POST_SCREEN",
					"sound" => true,
					"tag" => "n_post_123"
				);
		$notification_data = array (
					"notification_type" => "post",
					"event_type" => "post_like",
					"post_id" => 123
				);

		$fcm_result = sendFCM($topic , $notification, $notification_data);

		echo json_encode($fcm_result);
	}

	// testNotif();
?>
