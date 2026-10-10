<?php
session_start();
include 'temp/head.php';
include 'temp/bd.php';
include 'temp/nav_manager.php';
// Получаем выбранный статус для фильтрации из GET-запроса
$status_filter = isset($_GET['status']) ? $mysqli->real_escape_string($_GET['status']) : '';
$sql = "SELECT z.*, u.fio, s.name_sekcii 
        FROM zayavka z
        JOIN users u ON z.id_user = u.id_user
        JOIN sekciya s ON z.id_sekcii = s.id_sekcii";
// Если выбран конкретный статус фильтрации, добавляем условие WHERE
if (!empty($status_filter)) {
    $sql .= " WHERE z.status = '$status_filter'";
}
$sql .= " ORDER BY z.id_zayavki DESC";
$result = $mysqli->query($sql);
?>
<div class="container my-5">
        <h1 class="mb-4 fw-bold">Управление заявками</h1>
//фильтр по заявкам
    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <h5 class="m-0 fw-bold">Фильтрация заявок:</h5>
            <div class="btn-group">
                <a href="manager.php" class="btn btn-outline-primary <?= empty($status_filter) ? 'active' : '' ?>">Все</a>
                <a href="manager.php?status=Новая" class="btn btn-outline-primary <?= $status_filter == 'Новая' ? 'active' : '' ?>">Новые</a>
                <a href="manager.php?status=Принята" class="btn btn-outline-primary <?= $status_filter == 'Принята' ? 'active' : '' ?>">Принятые</a>
                <a href="manager.php?status=Отказ" class="btn btn-outline-primary <?= $status_filter == 'Отказ' ? 'active' : '' ?>">Отказы</a>
            </div>
        </div>
    </div>

  //табл заявок
    <div class="card shadow-sm mb-5">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle m-0">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>ФИО Пользователя</th>
                        <th>Секция</th>
                        <th>Дата заявки</th>
                        <th>Статус</th>
                        <th>Информация</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id_zayavki'] ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['fio']) ?></td>
                                <td><?= htmlspecialchars($row['name_sekcii']) ?></td>
                                <td><?= date('d.m.Y', strtotime($row['datazayavki'])) ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Новая'): ?>
                                        <span class="badge bg-warning text-dark">Новая</span>
                                    <?php elseif ($row['status'] == 'Принята'): ?>
                                        <span class="badge bg-success">Принята</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Отказ</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php 
                                        if ($row['status'] == 'Принята') {
                                            echo "Собеседование: " . date('d.m.Y', strtotime($row['datasobesedovaniya']));
                                        } elseif ($row['status'] == 'Отказ') {
                                            echo "Причина: " . htmlspecialchars($row['prichina_otkaza']);
                                        } else {
                                            echo "Ожидает решения";
                                        }
                                        ?>
                                    </small>
                                </td>
                                <td>
                               //переход на стр изм заяв
                                    <a href="update_zayavka.php?id=<?= $row['id_zayavki'] ?>" class="btn btn-sm btn-primary">Рассмотреть</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Заявок с таким статусом не найдено.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

//доп. отчет как в тз
    <h3 class="mb-4 fw-bold">Отчет о количестве обучающихся в секциях</h3>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered table-striped m-0">
                <thead class="table-primary">
                    <tr>
                        <th>Вид спорта</th>
                        <th>Название секции</th>
                        <th>ФИО Тренера</th>
                        <th class="text-center">Количество обучающихся</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Запрос  количество принятых уч-ков для каждой секции
                    $sql_report = "SELECT v.namevidsport, s.name_sekcii, t.fiotrenera, s.id_sekcii,
                                   (SELECT COUNT(*) FROM zayavka z WHERE z.id_sekcii = s.id_sekcii AND z.status = 'Принята') as total_students
                                   FROM sekciya s
                                   JOIN vidsporta v ON s.id_vida = v.idvida
                                   JOIN trener t ON s.id_trenera = t.IDtrenera";
                    $res_report = $mysqli->query($sql_report);
                                        while ($rep = $res_report->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($rep['namevidsport']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($rep['name_sekcii']) ?></td>
                            <td><?= htmlspecialchars($rep['fiotrenera']) ?></td>
                            <td class="text-center fw-bold text-success"><?= $rep['total_students'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'temp/footer.php';
?>
