<?php
session_start();
include 'temp/bd.php';
if (!empty($_SESSION['role'])) {
    $role = $_SESSION['role'];
    if ($role == 'Клиент') {
        include 'temp/nav_client.php';
    } elseif ($role == 'Manager' || $role == 'Менеджер') {
        include 'temp/nav_manager.php';
    } else {
        include 'temp/nav.php';
    }
} else {
    include 'temp/nav.php';
}
if (!isset($_GET['id'])) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Секция не указана.</div></div>";
    include 'temp/footer.php';
    exit();
}

$id_sekcii = (int)$_GET['id'];

//  получаем инф о секции виде спорта и тренере напрямую из БД
$sql_section = "SELECT s.*, v.namevidsport, t.fiotrenera 
                FROM sekciya s
                JOIN vidsporta v ON s.id_vida = v.idvida
                JOIN trener t ON s.id_trenera = t.IDtrenera
                WHERE s.id_sekcii = $id_sekcii";

$res_section = $mysqli->query($sql_section);
$section = $res_section->fetch_assoc();
if (!$section) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Секция не найдена.</div></div>";
    include 'temp/footer.php';
    exit();
}

// 4. Считаем количество одобренных участников в секции
$sql_count = "SELECT COUNT(*) as total FROM zayavka WHERE id_sekcii = $id_sekcii AND status = 'Принята'";
$res_count = $mysqli->query($sql_count);
$count_data = $res_count->fetch_assoc();
$students_count = (int)$count_data['total'];

// 5. ИСПРАВЛЕНО: Привязываем картинки строго к числовому ID секции
$img_src = "img/default_sport.jpg";
if ($id_sekcii === 1) $img_src = "img/01.png";
if ($id_sekcii === 2) $img_src = "img/f.png";
if ($id_sekcii === 3) $img_src = "img/d1.png";
include 'temp/head.php'; 
?>

<div class="container my-5">
    <div class="mb-4">
        <a href="index.php" class="btn btn-outline-secondary">← На главную</a>
    </div>
    <h1 class="mb-4 text-center">Информация о секции</h1>

    <div class="card mb-5 shadow-sm">
        <div class="row g-0">
            <div class="col-md-5">
                  <img src="<?= $img_src ?>" class="img-fluid rounded-start h-100" alt="Спорт" style="object-fit: cover; min-height: 250px;">
            </div>

            <div class="col-md-7">
                <div class="card-body">
                    <span class="badge bg-primary mb-2 text-uppercase"><?= htmlspecialchars($section['namevidsport']) ?></span>
                    <h3 class="card-title fw-bold mb-3"><?= htmlspecialchars($section['name_sekcii']) ?></h3>
                    
                    <p class="card-text"><strong>ФИО Тренера:</strong> <?= htmlspecialchars($section['fiotrenera']) ?></p>
                    <p class="card-text"><strong>Расписание занятий:</strong> <?= htmlspecialchars($section['raspisanie_zanyat']) ?></p>
                    <p class="card-text"><strong>Текущее количество учеников:</strong> <?= $students_count ?> из 15 мест</p>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 'Manager' || $_SESSION['role'] == 'Менеджер')): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-warning"text-black fw-bold">
                Список обучающихся в  данной секции
            </div>
            <div class="card-body">
                <?php

                $sql_students = "SELECT u.fio, u.tel, u.lichnye_dannye 
                                 FROM zayavka z
                                 JOIN users u ON z.id_user = u.id_user
                                 WHERE z.id_sekcii = $id_sekcii AND z.status = 'Принята'";
                
                $res_students = $mysqli->query($sql_students);
                if ($res_students && $res_students->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle m-0">
                            <thead class="table-warning"">
                                <tr>
                                    <th>ФИО</th>
                                    <th>Телефон</th>
                                    <th>Возраст, пол, группа здоровья</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($student = $res_students->fetch_assoc()): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($student['fio']) ?></td>
                                        <td><?= htmlspecialchars($student['tel'] ? $student['tel'] : 'Не указан') ?></td>
                                        <td><?= htmlspecialchars($student['lichnye_dannye']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center my-3">В этой секции пока нет одобренных учеников.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
include 'temp/footer.php';
?>
