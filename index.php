<?php
session_start(); 
include 'temp/bd.php'; 

// ОБРАБОТКА НАЖАТИЯ НА КНОПКУ "ЗАПИСАТЬСЯ"
if (isset($_POST['id_sekcii'])) {
    // Берем ID пользователя, который мы увидели в сессии
    $id_user = isset($_SESSION['id_user']) ? (int)$_SESSION['id_user'] : 0;
    $id_sekcii = (int)$_POST['id_sekcii'];
    $current_date = date('Y-m-d');

    if ($id_user > 0 && $id_sekcii > 0) {
        // Проверяем, нет ли уже поданной заявки
        $check_sql = "SELECT id_zayavki FROM zayavka WHERE id_user = $id_user AND id_sekcii = $id_sekcii";
        $check_res = $mysqli->query($check_sql);

        if ($check_res && $check_res->num_rows == 0) {
            // Вставляем новую заявку напрямую в БД со статусом 'Новая'
            $insert_sql = "INSERT INTO zayavka (id_user, id_sekcii, datazayavki, status) 
                           VALUES ($id_user, $id_sekcii, '$current_date', 'Новая')";
            
            if ($mysqli->query($insert_sql)) {
                // Перенаправляем на страницу со списком заявок
                header("Location: moi_zayvki.php");
                exit();
            }
        } else {
            header("Location: moi_zayvki.php");
            exit();
        }
    }
}

include 'temp/head.php';

if(!empty($_SESSION['role'])){
    $role = $_SESSION['role'];
    if($role == 'Клиент'){
        include 'temp/nav_client.php';
    }
    if($role == 'Manager'){
        include 'temp/nav_manager.php';
    }
}else{
    include 'temp/nav.php';
}

// Проверяем, авторизован ли пользователь как Клиент для активации кнопок записей
$can_join = (isset($_SESSION['role']) && $_SESSION['role'] == 'Клиент');
?>

<div class="container my-5">
    <h1 class="mb-4 text-center">Наши спортивные секции</h1>
    <div class="card-group shadow-sm">
        <!-- КАРТОЧКА 1: Плавание -->
        <div class="card">
             <img src="img/01.png" class="card-img-top" alt="Плавание" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
                <span class="badge bg-primary align-self-start mb-2 text-uppercase">Плавание</span>
                <h5 class="card-title fw-bold">Секция плавания "Дельфин"</h5>
                <p class="card-text text-muted mb-1"><strong>Тренер:</strong> Петрова Анна Владимировна</p>
                <p class="card-text text-muted mb-3"><strong>Расписание:</strong> Пн, Ср, Пт — 16:00-17:30</p>
                <div class="mt-auto">
                    <p class="card-text mb-3"><small class="text-body-secondary">Занято мест: 0 из 15</small></p>
                    <div class="d-flex gap-2">
                        <a href="prosmotr_zay.php?id=1" class="btn btn-warning w-100">Посмотреть</a>
                        <!-- ИСПРАВЛЕНО: строго прописан метод POST -->
                        <form action="" method="POST" class="w-100 m-0">
                            <input type="hidden" name="id_sekcii" value="1">
                            <button type="submit" class="btn btn-danger w-100" <?= !$can_join ? 'disabled' : '' ?>>Записаться</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- КАРТОЧКА 2: Футбол -->
        <div class="card">
            <img src="img/f.png" class="card-img-top" alt="Футбол" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
                <span class="badge bg-primary align-self-start mb-2 text-uppercase">Футбол</span>
                <h5 class="card-title fw-bold">Юношеская футбольная команда "Старт"</h5>
                <p class="card-text text-muted mb-1"><strong>Тренер:</strong> Иванов Сергей Петрович</p>
                <p class="card-text text-muted mb-3"><strong>Расписание:</strong> ВТ, ЧТ — 15:00-16:30, СБ — 12:00</p>
                <div class="mt-auto">
                    <p class="card-text mb-3"><small class="text-body-secondary">Занято мест: 1 из 15</small></p>
                    <div class="d-flex gap-2">
                        <a href="prosmotr_zay.php?id=2" class="btn btn-warning w-100">Посмотреть</a>
                        <!-- ИСПРАВЛЕНО: строго прописан метод POST -->
                        <form action="" method="POST" class="w-100 m-0">
                            <input type="hidden" name="id_sekcii" value="2">
                            <button type="submit" class="btn btn-danger w-100" <?= !$can_join ? 'disabled' : '' ?>>Записаться</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- КАРТОЧКА 3: Дзюдо -->
        <div class="card">
            <img src="img/d1.png" class="card-img-top" alt="Дзюдо" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
                <span class="badge bg-primary align-self-start mb-2 text-uppercase">Дзюдо</span>
                <h5 class="card-title fw-bold">Секция Дзюдо "Тигр"</h5>
                <p class="card-text text-muted mb-1"><strong>Тренер:</strong> Сидоров Алексей Николаевич</p>
                <p class="card-text text-muted mb-3"><strong>Расписание:</strong> Пн, Ср — 18:00-19:30</p>
                    <div class="mt-auto">
                    <p class="card-text mb-3"><small class="text-body-secondary">Занято мест: 0 из 15</small></p>
                    <div class="d-flex gap-2">
                        <a href="prosmotr_zay.php?id=3" class="btn btn-warning w-100">Посмотреть</a>
                        <!-- ИСПРАВЛЕНО: строго прописан метод POST -->
                        <form action="" method="POST" class="w-100 m-0">
                            <input type="hidden" name="id_sekcii" value="3">
                            <button type="submit" class="btn btn-danger w-100" <?= !$can_join ? 'disabled' : '' ?>>Записаться</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
include 'temp/footer.php';
?>
