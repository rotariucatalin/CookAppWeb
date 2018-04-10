<?php
    $host       = "localhost";
    $db_name    = "cook_app";
    $username   = "root";
    $password   = "";
    
    $connect_to_server  = mysql_connect($host, $username, $password) or die(mysql_error());
    $selected           = mysql_select_db("cook_app",$connect_to_server) or die("Could not select examples");
?>
