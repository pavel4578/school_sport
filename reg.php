<?php
include 'temp/bd.php';
$fio=$_POST['fio'];
$login=$_POST['login'];
$pass=$_POST['pass'];
$email=$_POST['email'];
$tel=$_POST['tel'];
$lichnye_dannye=$_POST['lichnye_dannye'];
$sql="INSERT INTO `users`(`login`, `pass`, `email`, `tel`, `role`, `fio`,`lichnye_dannye`) VALUES
 ('$login','$pass','$email','$tel','Клиент','$fio','$lichnye_dannye')";
$res=$mysqli->query($sql);
header('Location: formavto.php');
?>
