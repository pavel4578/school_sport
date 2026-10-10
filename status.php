<?php
session_start();
include 'temp/bd.php';

// Проверяем, авторизован ли пользователь и нажата ли кнопка
if (!isset($_SESSION['user_id']) || !isset($_POST['id_sekcii'])) {
    header("Location: index.php");
    exit();
}

$id_user = (int)$_SESSION['user_id'];
$id_sekcii = (int)$_POST['id_sekcii'];
$current_date = date('Y-m-d');

// Проверяем, не подавал ли пользователь заявку в эту секцию ранее
$check_sql = "SELECT id_zayavki FROM zayavka WHERE id_user = $id_user AND id_sekcii = $id_sekcii";
$check_res = $mysqli->query($check_sql);

if ($check_res && $check_res->num_rows == 0) {
     $insert_sql = "INSERT INTO zayavka (id_user, id_sekcii, datazayavki, status) 
                   VALUES ($id_user, $id_sekcii, '$current_date', 'Новая')";
    
    if ($mysqli->query($insert_sql)) {
        // направляем пользователя на страницу с его заявками или в личный кабинет
        header("Location: lich_kab.php?success=1");
        exit();
    } else {
        echo "Ошибка при отправке заявки: " . $mysqli->error;
    }
} else {
    // Если заявка уже существует
    header("Location: lich_kab.php?exists=1");
    exit();
}
?>
