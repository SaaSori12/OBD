<?php
$type = 0;
if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'] && isset($_COOKIE['user_hash']) && $_COOKIE['user_hash']){
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "AirTrasport";

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    // connect to mysql database
    try{
        $mysqli = new mysqli($host, $user, $password, $database);
     
    } catch (mysqli_sql_exception $ex) {
        echo 'Error';
    }
    
   $result = $mysqli->query("SELECT * FROM `users` WHERE `id` = ".$_COOKIE['user_id']);
   $user = $result->fetch_assoc();
   if(!$user) {
    echo "Такого користувача не існує";
   }
   if(md5($user['id'].$user['pass'].$user['name'].$user['type']."fkgfksdds4423" == $_COOKIE['user_hash'])){
    $type = $user['type'];
   }
   else{
    header('Location: register_login.php');
   }
}
else{
    header('Location: register_login.php');
}
?>