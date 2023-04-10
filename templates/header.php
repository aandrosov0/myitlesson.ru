<!DOCTYPE html>
<html lang="en" style="height: 100%;">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="/public/css/style.css" rel="stylesheet">
    <link href="/public/bootstrap5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" />
	<script src="/public/bootstrap5.3.0/js/bootstrap.min.js"></script>
    <script src="/public/js/jquery-3.6.4.min.js"></script>
    <script src="/public/js/classes/AlertElement.js"></script>
    <script src="/public/js/js.js"></script>
</head>

<body class="bg-dark text-white h-100">
    <div id="blur" style="display: none;"></div>
    <div class="fixed-top m-3" id="alerts">
    </div>
    <div class="d-flex h-100 flex-column m-2">
        <nav class="navbar navbar-dark navbar-expand-sm px-3 rounded" style="background-color: #1f2429; box-shadow: 1px 5px 20px black; flex: 0 0 auto;">
            <a href="/" class="navbar-brand text-success">MYITLESSON.RU</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link link-success" href="/" id="nav-home">Главная</a></li>
                    <li class="nav-item"><a class="nav-link link-success" href="/courses?offset=0&limit=5" id="nav-courses">Курсы</a></li>
                    <li class="nav-item"><a class="nav-link link-success" href="/users?offset=0&limit=5" id="nav-users">Пользователи</a></li>
                    <li class="nav-item"><a class="nav-link link-success" href="/user?id=" id="nav-profile">Мой профиль</a></li>
                </ul>
            </div>
        </nav>
        <div class="d-flex flex-column justify-content-center align-items-center container-fluid" style="flex: 1 0 auto;">