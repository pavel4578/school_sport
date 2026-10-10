<?php
session_start();
include 'temp/bd.php'; 
if (isset($_POST['login']) && isset($_POST['pass'])) {
    $login = $mysqli->real_escape_string($_POST['login']);
    $pass = $mysqli->real_escape_string($_POST['pass']);
    $sql = "SELECT * FROM users WHERE login = '$login' AND pass = '$pass'";
    $result = $mysqli->query($sql);
    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Записываем данные в сессию
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fio'] = $user['fio'];
        // Перенаправляем на главную
        header("Location: index.php");
        exit();
    } else {
        // заставляем  передать  GET-параметр ошибки
        header("Location: formavto.php?error=1");
        exit();
    }
} else {
    header("Location: formavto.php");
    exit();
}
