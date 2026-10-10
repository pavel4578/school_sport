<?php
session_start();
include 'temp/head.php';
include 'temp/bd.php'; 
if (empty($_SESSION['role']) || ($_SESSION['role'] != 'Manager' && $_SESSION['role'] != 'Менеджер')) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Доступ запрещен.</div></div>";
    include 'temp/footer.php';
    exit();
}
include 'temp/nav_manager.php';
// проверка, передан ли ID заявки
if (!isset($_GET['id'])) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Заявка не указана.</div></div>";
    include 'temp/footer.php';
    exit();
}

$id_zayavki = (int)$_GET['id'];
$message = '';

// ОБРАБОТКА ИЗМЕНЕНИЯ СТАТУСА ПРИ ОТПРАВКЕ ФОРМЫ
if (isset($_POST['update_status'])) {
    $status = $mysqli->real_escape_string($_POST['status']);
    
    if ($status == 'Принята') {
        $datasobesedovaniya = $mysqli->real_escape_string($_POST['datasobesedovaniya']);
        // Обновляем дату собеседования, очищаем причину отказа
        $sql_update = "UPDATE zayavka 
                       SET status = '$status', datasobesedovaniya = '$datasobesedovaniya', prichina_otkaza = NULL 
                       WHERE id_zayavki = $id_zayavki";
    } else {
        $prichina_otkaza = $mysqli->real_escape_string($_POST['prichina_otkaza']);
        // Обновляем причину отказа, очищаем дату собеседования
        $sql_update = "UPDATE zayavka 
                       SET status = '$status', prichina_otkaza = '$prichina_otkaza', datasobesedovaniya = NULL 
                       WHERE id_zayavki = $id_zayavki";
    }

    if ($mysqli->query($sql_update)) {
        // Перенаправляем обратно в панель менеджера после успешного обновления
        header("Location: manager.php");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Ошибка обновления: " . $mysqli->error . "</div>";
    }
}

// Получаем текущие данные выбранной заявки и пользователя
$sql_zayavka = "SELECT z.*, u.fio, u.tel, u.email, u.lichnye_dannye, s.name_sekcii 
                FROM zayavka z
                JOIN users u ON z.id_user = u.id_user
                JOIN sekciya s ON z.id_sekcii = s.id_sekcii
                WHERE z.id_zayavki = $id_zayavki";

$res_zayavka = $mysqli->query($sql_zayavka);
$zayavka = $res_zayavka->fetch_assoc();

if (!$zayavka) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Заявка не найдена.</div></div>";
    include 'temp/footer.php';
    exit();
}
?>

<div class="container my-5" style="max-width: 700px;">
    <div class="mb-4">
        <a href="manager.php" class="btn btn-outline-secondary">← Назад к заявкам</a>
    </div>

    <h2 class="mb-4 text-center fw-bold">Рассмотрение заявки №<?= $zayavka['id_zayavki'] ?></h2>
    
    <?= $message ?>

//карточка с инф о уч-ка
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold">Данные заявителя</div>
        <div class="card-body">
            <p><strong>ФИО пользователя:</strong> <?= htmlspecialchars($zayavka['fio']) ?></p>
            <p><strong>Выбранная секция:</strong> <span class="text-primary fw-bold"><?= htmlspecialchars($zayavka['name_sekcii']) ?></span></p>
            <p><strong>Телефон:</strong> <?= htmlspecialchars($zayavka['tel'] ? $zayavka['tel'] : 'Не указан') ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($zayavka['email'] ? $zayavka['email'] : 'Не указан') ?></p>
            <p><strong>Личные данные (Возраст, пол, группа здоровья):</strong> <br>
               <span class="text-muted"><?= htmlspecialchars($zayavka['lichnye_dannye']) ?></span>
            </p>
            <p><strong>Дата подачи заявки:</strong> <?= date('d.m.Y', strtotime($zayavka['datazayavki'])) ?></p>
            <p><strong>Текущий статус:</strong> 
                <span class="badge <?= $zayavka['status']=='Новая' ? 'bg-warning text-dark' : ($zayavka['status']=='Принята' ? 'bg-success' : 'bg-danger') ?>">
                    <?= $zayavka['status'] ?>
                </span>
            </p>
        </div>
    </div>

//изм статуса
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-bold">Выберите решение:</label>
                    <select name="status" class="form-select" required>
                        <option value="Принята" <?= $zayavka['status'] == 'Принята' ? 'selected' : '' ?>>Принять заявку</option>
                        <option value="Отказ" <?= $zayavka['status'] == 'Отказ' ? 'selected' : '' ?>>Отклонить заявку</option>
                    </select>
                </div>

           //блок для ввода даты собеседования
                <div class="mb-3">
                    <label class="form-label fw-bold">Дата собеседования (заполнить при принятии):</label>
                    <input type="date" name="datasobesedovaniya" class="form-control" 
                           value="<?= $zayavka['datasobesedovaniya'] ? $zayavka['datasobesedovaniya'] : date('Y-m-d') ?>">
                </div>

              //блок для ввода причины отказа
                <div class="mb-3">
                    <label class="form-label fw-bold">Причина отказа (заполнить при отклонении):</label>
                    <textarea name="prichina_otkaza" class="form-control" rows="3" placeholder="Укажите причину отказа..."><?= htmlspecialchars($zayavka['prichina_otkaza']) ?></textarea>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" name="update_status" class="btn btn-success w-50">Сохранить решение</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'temp/footer.php';
?>
