<?php 

    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "proj";

    try {
        $db = new PDO("mysql:host={$db_host}; dbname={$db_name}", $db_user, $db_password);
        $db->setAttribute(PDO ::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
        $db->exec("set names utf8");
        // echo "202";
    } catch(PDOEXCEPTION $e) {
        $e->getMessage();
        // echo "21";
    }
    date_default_timezone_set('Asia/Bangkok');

   
