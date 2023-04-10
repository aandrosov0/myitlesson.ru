<?php $pageTitle = APP_NAME . " - Вход";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="/public/css/style.css" rel="stylesheet">
    <link href="/public/bootstrap5.3.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="/public/bootstrap5.3.0/js/bootstrap.min.js"></script>
    <script src="/public/js/jquery-3.6.4.min.js"></script>
    <script src="/public/js/classes/AlertElement.js"></script>
    <script src="/public/js/js.js"></script>
</head>
<style>

</style>
<body class="bg-dark text-white d-flex w-100 p-2 align-items-center justify-content-center h-100">
    <div id="alerts" class="fixed-top m-3"></div>
    <main style="width: 400px">
        <form method="post" class="m-auto bg-dark border rounded p-3 border-success" style="box-shadow: 1px 5px 20px black;">
            <div class="text-center text-warning">
                <h3>Для продолжения необходимо войти</h3>
            </div>
            <div class="mb-3 mt-3">
                <label for="username" class="form-label">Логин</label>
                <input type="text" class="form-control" name="username" id="username" require><br>
            </div>
            <div class="mb-3 mt-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" name="password" id="password" require><br>
            </div>

            <?php if(isset($err)): ?>
                <p class="text-center text-danger"><?= $err ?></p>
            <?php endif; ?>
            <button class="btn btn-success w-100">Войти</button>
        </form>
    </main>
    <script>
        showAlert("Hello, world!", AlertElement.TypeError);
    </script>
</body>
