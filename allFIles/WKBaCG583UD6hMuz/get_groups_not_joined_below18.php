<?php
/**
 * Created by PhpStorm.
 * User: harishuginval
 * Date: 03/07/18
 * Time: 3:15 AM
 */

require "init_new_config.php";

$limit_clause = '';

$page1=  isset($_GET['page']) ? $_GET['page'] : '';
if ($page1 != '') {
    $page = $page1;
    $limit = 25;
    $left = $page * $limit - $limit;
    $limit_clause = " LIMIT " . $left . "," . $limit;
} else {
    $limit = 25;
    $page = 1;
    $limit_clause = " LIMIT 0 ," . $limit;

}

$user_id = intval($_GET['user_id'] );
$below18 = isset($_GET['below18']) ? mysqli_real_escape_string($con, $_GET['below18']) : "";

mysqli_set_charset($con, 'utf8');


if ($below18 == "true"){ // if true then user will get posts that are suitable for below 18
    $query = "SELECT id_categories FROM candid_database.group_category_join where id_user_name = $user_id ". $limit_clause;

    $querySelect = mysqli_query($con, $query)
    or die(mysqli_error($con));

    $records = array();

    if (mysqli_num_rows($querySelect) > 0){
        while ($r = mysqli_fetch_assoc($querySelect)) {

            if(!empty($querySelect)){
                foreach ($querySelect as $key => $val) {

                    foreach ($val as $group_id){

                        $individual_group = "SELECT group_id FROM candid_database.groups WHERE  
                    group_id NOT IN (SELECT groups_user.group_id FROM candid_database.groups_user 
                    WHERE  id_user_name= $user_id   UNION  SELECT group_id FROM 
                    candid_database.group_not_for_below_18) and id_categories = $group_id";

                        $querySelect = mysqli_query($con, $individual_group)
                        or die(mysqli_error($con));

                        $records = array();
                        while ($r = mysqli_fetch_assoc($querySelect)) {

                            if(!empty($querySelect)){
                                foreach ($querySelect as $key => $val) {

                                    foreach ($val as $group_id){
                                        $individual_group = "SELECT groups.group_id, 
                          groups.name as group_name, 
                          groups.group_image_url, 
                          groups.group_description,
	                      (Select count(id_user_name) as users_in_group from groups_user where group_id = groups.group_id) as users_in_group,
			              (Select count(group_id) as posts_inside_group from posts where group_id = groups.group_id) as posts_inside_groups,
                          groups.id_categories,
                          c.category as category_name,
                          groups.created_by_id_user_name as created_by_id_user_name
            
            FROM candid_database.groups
            LEFT JOIN categories c ON  groups.id_categories = c.id_categories
			LEFT JOIN groups_user gu ON groups.group_id = gu.group_id where groups.group_id = $group_id group by group_id";

                                        $q = mysqli_query($con, $individual_group) or die(mysqli_error($con)) ;
                                        while($row = mysqli_fetch_assoc($q)){
                                            $json[] = array(
                                                "group_id"=> $row['group_id'],
                                                "group_name"=> $row['group_name'] ,
                                                "group_image_url"=> $row['group_image_url'] ,
                                                "group_description"=> $row['group_description'] ,
                                                "users_in_group"=> $row['users_in_group'] ,
                                                "posts_inside_groups"=> $row['posts_inside_groups'] ,
                                                "id_categories"=> $row['id_categories'],
                                                "category_name"=> $row['category_name'],

                                                "created_by"=> array (
                                                    "id_user_name"=>$row['created_by_id_user_name'],
                                                    "user_nick_name"=>"anonymous",
                                                )
                                            );

                                        }
                                    }
                                    //die(json_encode($json));
                                }
                            }
                        }



                    }
                    //die(json_encode($json));
                }
            }
        }
    } else {

        $query = "SELECT group_id FROM candid_database.groups WHERE group_id 
            NOT IN (SELECT group_id FROM candid_database.groups_user where id_user_name = $user_id)". $limit_clause;

        $querySelect = mysqli_query($con, $query)
        or die(mysqli_error($con));

        $records = array();
        while ($r = mysqli_fetch_assoc($querySelect)) {

            if(!empty($querySelect)){
                foreach ($querySelect as $key => $val) {

                    foreach ($val as $group_id){
                        $individual_group = "SELECT groups.group_id, 
                          groups.name as group_name, 
                          groups.group_image_url, 
                          groups.group_description,
	                      (Select count(id_user_name) as users_in_group from groups_user where group_id = groups.group_id) as users_in_group,
			              (Select count(group_id) as posts_inside_group from posts where group_id = groups.group_id) as posts_inside_groups,
                          groups.id_categories,
                          c.category as category_name,
                          groups.created_by_id_user_name as created_by_id_user_name
            
            FROM candid_database.groups
            LEFT JOIN categories c ON  groups.id_categories = c.id_categories
			LEFT JOIN groups_user gu ON groups.group_id = gu.group_id where groups.group_id = $group_id group by group_id";

                        $q = mysqli_query($con, $individual_group) or die(mysqli_error($con)) ;
                        while($row = mysqli_fetch_assoc($q)){
                            $json[] = array(
                                "group_id"=> $row['group_id'],
                                "group_name"=> $row['group_name'] ,
                                "group_image_url"=> $row['group_image_url'] ,
                                "group_description"=> $row['group_description'] ,
                                "users_in_group"=> $row['users_in_group'] ,
                                "posts_inside_groups"=> $row['posts_inside_groups'] ,
                                "id_categories"=> $row['id_categories'],
                                "category_name"=> $row['category_name'],

                                "created_by"=> array (
                                    "id_user_name"=>$row['created_by_id_user_name'],
                                    "user_nick_name"=>"anonymous",
                                )
                            );

                        }
                    }
                    //die(json_encode($json));
                }
            }
        }

    }
} else {
    $query = "SELECT id_categories FROM candid_database.group_category_join where id_user_name = $user_id ". $limit_clause;

    $querySelect = mysqli_query($con, $query)
    or die(mysqli_error($con));

    $records = array();

    if (mysqli_num_rows($querySelect) > 0){
        while ($r = mysqli_fetch_assoc($querySelect)) {

            if(!empty($querySelect)){
                foreach ($querySelect as $key => $val) {

                    foreach ($val as $group_id){

                        $individual_group = "SELECT group_id FROM candid_database.groups WHERE  
                    group_id NOT IN (SELECT groups_user.group_id FROM candid_database.groups_user 
                    WHERE  id_user_name= $user_id) and id_categories = $group_id";

                        $querySelect = mysqli_query($con, $individual_group)
                        or die(mysqli_error($con));

                        $records = array();
                        while ($r = mysqli_fetch_assoc($querySelect)) {

                            if(!empty($querySelect)){
                                foreach ($querySelect as $key => $val) {

                                    foreach ($val as $group_id){
                                        $individual_group = "SELECT groups.group_id, 
                          groups.name as group_name, 
                          groups.group_image_url, 
                          groups.group_description,
	                      (Select count(id_user_name) as users_in_group from groups_user where group_id = groups.group_id) as users_in_group,
			              (Select count(group_id) as posts_inside_group from posts where group_id = groups.group_id) as posts_inside_groups,
                          groups.id_categories,
                          c.category as category_name,
                          groups.created_by_id_user_name as created_by_id_user_name
            
            FROM candid_database.groups
            LEFT JOIN categories c ON  groups.id_categories = c.id_categories
			LEFT JOIN groups_user gu ON groups.group_id = gu.group_id where groups.group_id = $group_id group by group_id";

                                        $q = mysqli_query($con, $individual_group) or die(mysqli_error($con)) ;
                                        while($row = mysqli_fetch_assoc($q)){
                                            $json[] = array(
                                                "group_id"=> $row['group_id'],
                                                "group_name"=> $row['group_name'] ,
                                                "group_image_url"=> $row['group_image_url'] ,
                                                "group_description"=> $row['group_description'] ,
                                                "users_in_group"=> $row['users_in_group'] ,
                                                "posts_inside_groups"=> $row['posts_inside_groups'] ,
                                                "id_categories"=> $row['id_categories'],
                                                "category_name"=> $row['category_name'],

                                                "created_by"=> array (
                                                    "id_user_name"=>$row['created_by_id_user_name'],
                                                    "user_nick_name"=>"anonymous",
                                                )
                                            );

                                        }
                                    }
                                    //die(json_encode($json));
                                }
                            }
                        }



                    }
                    //die(json_encode($json));
                }
            }
        }
    } else {

        $query = "SELECT group_id FROM candid_database.groups WHERE group_id 
            NOT IN (SELECT group_id FROM candid_database.groups_user where id_user_name = $user_id)". $limit_clause;

        $querySelect = mysqli_query($con, $query)
        or die(mysqli_error($con));

        $records = array();
        while ($r = mysqli_fetch_assoc($querySelect)) {

            if(!empty($querySelect)){
                foreach ($querySelect as $key => $val) {

                    foreach ($val as $group_id){
                        $individual_group = "SELECT groups.group_id, 
                          groups.name as group_name, 
                          groups.group_image_url, 
                          groups.group_description,
	                      (Select count(id_user_name) as users_in_group from groups_user where group_id = groups.group_id) as users_in_group,
			              (Select count(group_id) as posts_inside_group from posts where group_id = groups.group_id) as posts_inside_groups,
                          groups.id_categories,
                          c.category as category_name,
                          groups.created_by_id_user_name as created_by_id_user_name
            
            FROM candid_database.groups
            LEFT JOIN categories c ON  groups.id_categories = c.id_categories
			LEFT JOIN groups_user gu ON groups.group_id = gu.group_id where groups.group_id = $group_id group by group_id";

                        $q = mysqli_query($con, $individual_group) or die(mysqli_error($con)) ;
                        while($row = mysqli_fetch_assoc($q)){
                            $json[] = array(
                                "group_id"=> $row['group_id'],
                                "group_name"=> $row['group_name'] ,
                                "group_image_url"=> $row['group_image_url'] ,
                                "group_description"=> $row['group_description'] ,
                                "users_in_group"=> $row['users_in_group'] ,
                                "posts_inside_groups"=> $row['posts_inside_groups'] ,
                                "id_categories"=> $row['id_categories'],
                                "category_name"=> $row['category_name'],

                                "created_by"=> array (
                                    "id_user_name"=>$row['created_by_id_user_name'],
                                    "user_nick_name"=>"anonymous",
                                )
                            );

                        }
                    }
                    //die(json_encode($json));
                }
            }
        }

    }
}




die(json_encode($json));
?>