<?php session_start();?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, rgba(251, 255, 12, 0.9), rgba(237, 240, 73, 0.9)) !important;">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center text-white" href="#" style="color: black !important;">
      <img src="img/56-cropped.png" alt="Логотип" width="100" height="100" class="d-inline-block align-text-top rounded-circle me-2" style="object-fit: cover;">
     <span style="
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        font-weight: 800; 
        font-size: 1.4rem;
        background: linear-gradient(45deg, #2b11a4, #0055ff,  #f36060); 
        -webkit-background-clip: text; 
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(2px 2px 2px rgba(0, 0, 0, 0.25));
      ">
        Сайт для спортивной школы
      </span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <!-- ИСПРАВЛЕНО: Ссылка сделана черной -->
          <a class="nav-link" href="index.php" style="color: black !important;">Главная</a>
        </li>
        <li class="nav-item">
          <!-- ИСПРАВЛЕНО: Ссылка сделана черной -->
          <a class="nav-link" href="manager.php" style="color: black !important;">Панель менеджера</a>
        </li>
        <li class="nav-item">
          <!-- ИСПРАВЛЕНО: Убран text-white, ссылка сделана черной -->
          <a class="nav-link" href="logout.php" style="color: black !important;">Выйти (<?php echo htmlspecialchars($_SESSION['fio']);?>)</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
