<?php
    $data           = file_get_contents('php://input');
    $json_to_array  = json_decode($data, true);
    $type           = $json_to_array['type'];
    $output_array   = array();

    switch($type) {
        case "login" :          checkCredentialsLogin($json_to_array, $output_array);            break;
        case "facebook_login" : checkCredentialsLoginFacebook($json_to_array, $output_array);    break;
    } 
    
    function checkCredentialsLogin($json_to_array, $output_array) {
        
        require_once('config.php');
        
        $user_email      = "";
        $user_password   = "";
        
        $user_email      = $json_to_array['email'];
        $user_password   = $json_to_array['password'];
        
        $select_users_query = mysql_query("SELECT * FROM `users` WHERE `users`.`user_email` = '".$user_email."' AND `users`.`user_password` = MD5('".$user_password."') ") or die(mysql_error());
        $result_user_query  = mysql_fetch_array($select_users_query);
        
        $user_id            = $result_user_query['user_id'];
        $user_email         = $result_user_query['user_email'];
        $user_first_name    = $result_user_query['user_first_name'];
        $user_last_name     = $result_user_query['user_last_name'];
        
        if($user_id != '')
            $output_array       = [ "code" => "success", "message" => "Username found in the database", "user_id" => $user_id, "user_email" => $user_email, "user_first_name" => $user_first_name, "user_last_name" => $user_last_name, "facebook_logged_in" => false ];
        else
            $output_array       = [ "code" => "error", "message" => "Username not found in the database", "user_id" => "", "user_email" => "", "user_first_name" => "", "user_last_name" => "", "facebook_logged_in" => false ];
        
        echo json_encode($output_array);

    } 
    
    function checkCredentialsLoginFacebook($json_to_array, $output_array) {
        
        $user_facebook_id           = "";    
        $user_facebook_email        = "";    
        $user_facebook_first_name   = "";    
        $user_facebook_last_name    = "";    
        
        $check_query                = mysql_query(" SELECT * FROM `users_facebook` WHERE `user_facebook_id` = '".$json_to_array['idFacebook']."' ");
        $result_query               = mysql_fetch_array($check_query);
        
        $user_facebook_id           = $result_query['user_facebook_id'];
        $user_facebook_email        = $result_query['user_facebook_email '];
        $user_facebook_first_name   = $result_query['user_facebook_first_name'];
        $user_facebook_last_name    = $result_query['user_facebook_last_name'];
        
        if($user_facebook_id == '') {
            
            $user_facebook_id           = $json_to_array['idFacebook'];
            $user_facebook_email        = $json_to_array['emailFacebook'];
            $user_facebook_first_name   = $json_to_array['firstNameFacebook'];
            $user_facebook_last_name    = $json_to_array['lastNameFacebook'];
                 
            $insert_user_facebook_query = mysql_query("
                                                                INSERT INTO `users_facebook` (
                                                                    `user_facebook_id`
                                                                    ,`user_facebook_email`
                                                                    ,`user_facebook_first_name`
                                                                    ,`user_facebook_last_name`
                                                                )
                                                                VALUES (
                                                                    '".$user_facebook_id."'
                                                                    ,'".$user_facebook_email."'
                                                                    ,'".$user_facebook_first_name."'
                                                                    ,'".$user_facebook_last_name."'
                                                                    )
                                                                ");
            $output_array       = [ "code" => "success", "message" => "Username registered in the database", "user_id" => $user_facebook_id, "user_email" => $user_facebook_email, "user_first_name" => $user_facebook_first_name, "user_last_name" => $user_facebook_last_name, "facebook_logged_in" => true ];                                                            
        } else {
            
            $output_array       = [ "code" => "success", "message" => "Username found in the database", "user_id" => $user_facebook_id, "user_email" => $user_facebook_email, "user_first_name" => $user_facebook_first_name, "user_last_name" => $user_facebook_last_name, "facebook_logged_in" => true ];
        }
        echo json_encode($output_array);
    }  
?>