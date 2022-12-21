<?php
    setcookie('user', $user['name'], time() - 3600, "/");
    setcookie('user', $user['name'], time() - 3600, "/");

    if (isset($_COOKIE['user_id'])) {
        unset($_COOKIE['user_id']); 
        setcookie('user_id', null, -1, '/');
    } 
    if (isset($_COOKIE['user_hash'])) {
        unset($_COOKIE['user_hash']); 
        setcookie('user_hash', null, -1, '/');
    } 
    header('Location: /');
?>