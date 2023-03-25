<?php $pageTitle = APP_NAME . " - Вход";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="/public/bootstrap5.3.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="/public/bootstrap5.3.0/js/bootstrap.min.js"></script>
</head>

<style>
	html,
	body {
		height: 100%;
	}

	body {
		display: flex;
		align-items: center;
	}

	.form-signin>form {
		max-width: 350px;
		background-color: #242222;
	}

	.navbar {
		display: none;
	}
</style>

<body class="bg-dark text-white">
    <main class="form-signin w-100">
        <form action="/login" method="post" class="m-auto bg-dark border rounded p-3 border-success" style="box-shadow: 1px 5px 20px black;">
            <div class="text-center text-warning">
                <h3>Для продолжения необходимо войти</h3>
            </div>
            <div class="mb-3 mt-3">
                <label for="name" class="form-label">Логин</label>
                <input type="text" class="form-control" name="name" id="name" require><br>
            </div>

            <div class="mb-3 mt-3">
                <label for="password" class="form-label">Пароль</label>
                <input type="password" class="form-control" name="password" id="password"><br>
            </div>

            <?php if (isset($err)) : ?>
                <p><?= $err ?></p>
            <?php endif; ?>

            <input type="submit" class="btn btn-success w-100" value="Войти">
        </form>
    </main>
</body>
