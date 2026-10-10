<?php
session_start();
include 'temp/bd.php'; 

// проверка авторизации
if (!isset($_SESSION['user_id']) && !isset($_SESSION['id']) && !isset($_SESSION['id_user'])) {
    header("Location: formavto.php");
    exit();
}

// Автоматически определяем ID пользователя
if (isset($_SESSION['user_id'])) {
    $id_user = (int)$_SESSION['user_id'];
} elseif (isset($_SESSION['id'])) {
    $id_user = (int)$_SESSION['id'];
} else {
    $id_user = (int)$_SESSION['id_user'];
}

$message = '';

// ОБРАБОТКА ОБНОВЛЕНИЯ ДАННЫХ ПРОФИЛЯ
if (isset($_POST['save_profile'])) {
    $fio = $mysqli->real_escape_string($_POST['fio']);
    $tel = $mysqli->real_escape_string($_POST['tel']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $lichnye_dannye = $mysqli->real_escape_string($_POST['lichnye_dannye']);

    $sql_update = "UPDATE users 
                   SET fio = '$fio', tel = '$tel', email = '$email', lichnye_dannye = '$lichnye_dannye' 
                   WHERE id_user = $id_user";

    if ($mysqli->query($sql_update)) {
        $message = "<div class='alert alert-success py-2'>Данные успешно обновлены!</div>";
    } else {
        $message = "<div class='alert alert-danger py-2'>Ошибка обновления: " . $mysqli->error . "</div>";
    }
}

//  актуальные данные пользователя для заполнения полей формы
$sql_user = "SELECT * FROM users WHERE id_user = $id_user";
$res_user = $mysqli->query($sql_user);
$user = $res_user->fetch_assoc();

// запрос на получение всех заявок данного ученика по спортивным секциям
$sql_zayavki = "SELECT z.*, s.name_sekcii, v.namevidsport 
                FROM zayavka z
                JOIN sekciya s ON z.id_sekcii = s.id_sekcii
                JOIN vidsporta v ON s.id_vida = v.idvida
                WHERE z.id_user = $id_user
                ORDER BY z.id_zayavki DESC";
//  храняем результат в переменную $res_zayavki, чтобы цикл ниже не выдавал ошибку
$res_zayavki = $mysqli->query($sql_zayavki);
?>

<?php
include 'temp/head.php';

// Подключаем навигацию в зависимости от роли
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
?>

<div class="container my-5">
    <div class="row g-4">
        <!-- БЛОК РЕДАКТИРОВАНИЯ ПРОФИЛЯ -->
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white fw-bold">Мой профиль</div>
                <div class="card-body">
                    <?= $message ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ФИО:</label>
                            <input type="text" name="fio" class="form-control" value="<?= htmlspecialchars($user['fio'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Телефон:</label>
                            <input type="text" name="tel" class="form-control" value="<?= htmlspecialchars($user['tel'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Личные данные (ТЗ):</label>
                            <textarea name="lichnye_dannye" class="form-control" rows="3" required><?= htmlspecialchars($user['lichnye_dannye'] ?? '') ?></textarea>
                            <small class="text-muted">Укажите ваш возраст, пол и группу здоровья.</small>
                        </div>
                        <button type="submit" name="save_profile" class="btn btn-warning w-100 fw-bold">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- БЛОК ВЫВОДА ЗАЯВОК -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white fw-bold">Мои заявки в секции</div>
                <div class="table-responsive">
                    <table class="table table-striped align-middle m-0">
                        <thead class="table-light">
                            <tr>
                                <th>Дата</th>
                                <th>Секция</th>
                                <th>Статус</th>
                                <th>Информация от менеджера</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($res_zayavki && $res_zayavki->num_rows > 0): ?>
                                <?php while ($zayavka = $res_zayavki->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= date('d.m.Y', strtotime($zayavka['datazayavki'])) ?></td>
                                        <td>
                                            <span class="badge bg-secondary mb-1 text-uppercase" style="font-size: 0.75rem;"><?= htmlspecialchars($zayavka['namevidsport']) ?></span><br>
                                            <strong><?= htmlspecialchars($zayavka['name_sekcii']) ?></strong>
                                        </td>
                                        <td>
                                            <?php if ($zayavka['status'] == 'Новая'): ?>
                                                <span class="badge bg-warning text-dark">Новая</span>
                                            <?php elseif ($zayavka['status'] == 'Принята'): ?>
                                                <span class="badge bg-success">Принята</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Отказ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php 
                                                if ($zayavka['status'] == 'Принята') {
                                                    echo "Собеседование назначено: " . date('d.m.Y', strtotime($zayavka['datasobesedovaniya']));
                                                } elseif ($zayavka['status'] == 'Отказ') {
                                                    echo "Причина: " . htmlspecialchars($zayavka['prichina_otkaza']);
                                                } else {
                                                    echo "Ожидает рассмотрения";
                                                }
                                                ?>
                                            </small>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Вы еще не подавали заявки в секции.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'temp/footer.php';
?>

