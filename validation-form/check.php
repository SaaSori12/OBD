<?php
    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

    if(mb_strlen($login) < 5 || mb_strlen($login) > 90) {
        echo "Длинна логина не допустимая (от 5 до 90 символов)";
        exit();
    } else if(mb_strlen($name) < 3 || mb_strlen($name) > 50) {
        echo "Недопустимая длина имени (от 3 до 50 символов)";
        exit(); 
    } else if(mb_strlen($pass) < 2 || mb_strlen($pass) > 10) {
        echo "Недопустимая длина пароля (от 2 до 6 символов)";
        exit();
    }
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
    
   $mysqli->query("INSERT INTO `users` (`login`, `pass`, `name`) VALUES('$login', '$pass', '$name')");
   
   $mysqli->close();

   header('Location: /');
?>