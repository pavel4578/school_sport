<?php
session_start();
include 'temp/bd.php'; 
include 'temp/head.php';
include 'temp/nav_client.php';
if (empty($_SESSION['id_user'])) {
    header("Location: formavto.php");
    exit();
}
$id_user = (int)$_SESSION['id_user'];
$sql_user = "SELECT fio FROM users WHERE id_user = $id_user";
$res_user = $mysqli->query($sql_user);
$user_data = $res_user->fetch_assoc();
$user_fio = $user_data ? $user_data['fio'] : '';
$sql_zayavki = "SELECT z.*, s.name_sekcii, v.namevidsport 
                FROM zayavka z
                JOIN sekciya s ON z.id_sekcii = s.id_sekcii
                JOIN vidsporta v ON s.id_vida = v.idvida
                WHERE z.id_user = $id_user
                ORDER BY z.id_zayavki DESC";
$result = $mysqli->query($sql_zayavki);
?>

<div class="container my-5">
    <h1 class="mb-4 text-center fw-bold">Мои заявки: <?= htmlspecialchars($user_fio) ?></h1>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle m-0">
                <thead class="table-primary">
                    <tr>
                        <th>ID заявки</th>
                        <th>Вид спорта</th>
                        <th>Название секции</th>
                        <th>Дата подачи</th>
                        <th>Статус заявки</th>
                        <th>Ответ от спортивной школы</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id_zayavki'] ?></td>
                                <td><span class="badge bg-primary text-uppercase"><?= htmlspecialchars($row['namevidsport']) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['name_sekcii']) ?></td>
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
                                            echo "<strong class='text-success'>Назначено собеседование:</strong> " . date('d.m.Y', strtotime($row['datasobesedovaniya']));
                                        } elseif ($row['status'] == 'Отказ') {
                                            echo "<strong class='text-danger'>Причина отказа:</strong> " . htmlspecialchars($row['prichina_otkaza']);
                                        } else {
                                            echo "Заявка находится на рассмотрении у менеджера";
                                        }
                                        ?>
                                    </small>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">У вас пока нет активных заявок в спортивные секции.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'temp/footer.php';
?>
