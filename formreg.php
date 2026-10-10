<?php
include 'temp/head.php';
include 'temp/nav.php';
include 'temp/bd.php';
?>
<form method="post" action="reg.php">
  <h1 class="mb-3 mt-3 text-warning  ">Регистрация</h1>
  <div class="mb-3">
    <label for="fio" class="form-label">ФИО</label>
    <input type="text" class="form-control" id="fio" name="fio"  required pattern="[А-ЯЁа-яё\s\-]+" >
  </div>
  <div class="mb-3">
    <label for="login" class="form-label">Логин</label>
    <input type="text" class="form-control" id="login" name="login" minlength="6" pattern="[a-zA-Z0-9]+" required>
  </div>
  <div class="mb-3">
    <label  for="pass">Пароль</label>
    <input type="password" class="form-control" id="pass" name="pass" minlenght="8" required>
  </div>
   <div class="mb-3">
    <label  for="email">E-mail</label>
    <input type="email" class="form-control" id="email" name="email" required>
    <div class="invalid-feedback">Пожалуйста, введите корректный адрес электронной почты.</div> 
        <div class="form-text">Введите  адрес электронной почты  пример: Ivanov.Ivan@gmail.com </div>
  </div>
   <div class="mb-3">
    <label  for="tel">Телефон</label>
   <input type="tel" class="form-control" id="tel" name="tel" placeholder="8(XXX)XXX-XX-XX"
   pattern="^(8\d{10}|8-\d{3}-\d{3}-\d{2}-\d{2}|8$\d{3}$\d{3}-\d{2}-\d{2})$" required>
  </div>
  <div class="mb-3">
    <label for="lichnye_dannye" class="form-label">Личные данные</label>
    <input type="text" class="form-control" id="lichnye_dannye" name="lichnye_dannye"  required  placeholder="Возраст: 13 лет, Пол: Мужской, Группа здоровья: I " >
  </div>
  <button type="submit" class="btn btn-warning " >Добавить пользователя</button>
</form>

