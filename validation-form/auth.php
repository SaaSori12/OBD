<?php
    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

    $pass = md5($pass."fkgfksdds4423");

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "AirTrasport";

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try{
        $mysqli = new mysqli($host, $user, $password, $database);
     
    } catch (mysqli_sql_exception $ex) {
        echo 'Error';
    }
    
   $result = $mysqli->query("SELECT * FROM `users` WHERE `login` = '$login' AND `pass` = '$pass'");
   $user = $result->fetch_assoc();
   if(!$user) {
    echo "Такого користувача не існує";
    exit();
   }

    setcookie('user_id', $user['id'], time() + 3600, "/");
    setcookie('user_hash', md5($user['id'].$user['pass'].$user['name'].$user['type']."fkgfksdds4423"), time() + 3600, "/");
   
   
   $mysqli->close();

   header('Location: /customer.php');
?>