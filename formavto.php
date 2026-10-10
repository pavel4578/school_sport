<?php 
include 'temp/head.php';
include 'temp/nav.php';
include 'temp/bd.php';
?>
<div class="row mt-5">
    <div class="col-3"></div>
        <form method="post" action="avto.php" class="col-6 bg-white p-4 rounded-4 shadow-sm border">
        <h1 class="mb-4 pt-2 fw-bold text-center text-warning ">Авторизация для посетителей</h1>
        
       <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger py-2 small text-center" role="alert">
        Неверный логин или пароль!
    </div>
<?php endif; ?>

        <div class="mb-3">
            <label class="form-label fw-semibold text-muted">Логин</label>
            <input type="text" class="form-control" name="login" required>
        </div>
        
        <div class="mb-4">
            <label class="form-label fw-semibold text-muted">Пароль</label>
            <input type="password" class="form-control" name="pass" required>
        </div>
        
        <div class="d-flex justify-content-between align-items-center gap-3">
            <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm">Войти</button>
            <a href="formreg.php" class="text-decoration-none small fw-semibold text-secondary">Если вы еще не зарегистрированы,Регистрация</a>
        </div>
    </form>
    
    <div class="col-3"></div>
</div>
