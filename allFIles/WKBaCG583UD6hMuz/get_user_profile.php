<?php
//	header("Content-Type : application/json");

	//$con = mysqli_connect("localhost","devesh",'sed1058','candib_db');

 require "init_new_config.php";

 	$_POST=$_GET;

    if( empty($_GET['user_id']) ){
        echo '{"status":"Invalid request"}';
        die();
    }

    $user_id = intval( $_POST['user_id'] );

	 /* FETCH data of posts which match the set where caluse filter and pagintaion limits */
	$q= " SELECT * FROM (SELECT count(id_user_name) as facebook_friends_count from facebook_friend_id where id_user_name = ?) as t1 "
		." JOIN (SELECT count(id_user_name) as contacts_count FROM user_name WHERE phone_number  IN ( SELECT DISTINCT phone_number FROM user_contacts WHERE id_user_name = ?) ) as t2 "
		." JOIN ( SELECT count(id_user_name) as posts_count FROM posts where id_user_name = ?) as t3 "
		." JOIN ( SELECT count(group_id) as groups_count FROM groups_user where id_user_name = ? ) as t4 "
		." JOIN ( SELECT IF( givenContact = '1' , 'true', 'false'  ) as givenContact FROM user_name where id_user_name = ? ) as t5 "
		." JOIN ( SELECT IF( given_facebook = '1' , 'true', 'false'  ) as given_facebook FROM user_name where id_user_name = ? ) as t6 "
		." JOIN ( SELECT IF( location = '1' , 'true', 'false'  ) as givenLocation FROM user_name where id_user_name = ? ) as t7 ";


	$q=mysqli_prepare($con,$q) or die(mysqli_error($con));

	mysqli_stmt_bind_param( $q , "iiiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
    mysqli_stmt_execute( $q );
    $q = mysqli_stmt_get_result( $q );

	 //echo mysqli_num_rows($q);
    $records = array();
	
	$r = mysqli_fetch_assoc($q);

	$records['facebook_friends_count'] = intval($r['facebook_friends_count']);
	$records['contacts_count'] = intval($r['contacts_count']);
	$records['posts_count'] = intval($r['posts_count']);
	$records['groups_count'] = intval($r['groups_count']);
	$records['givenFacebook'] = filter_var($r['given_facebook'], FILTER_VALIDATE_BOOLEAN );
	$records['givenContact'] = filter_var($r['givenContact'], FILTER_VALIDATE_BOOLEAN );
	$records['givenLocation'] = filter_var($r['givenLocation'], FILTER_VALIDATE_BOOLEAN );

	echo json_encode( $records) ;


?>
