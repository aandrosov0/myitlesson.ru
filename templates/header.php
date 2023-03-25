<?php global $user; ?>

<!DOCTYPE html>
<html lang="en" style="height: 100%;">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="/app/public/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="/app/public/js/AlertElement.js"></script>
    <script src="/app/public/js/js.js"></script>
</head>

<body class="bg-dark text-white h-100">
    <div id="blur" style="display: none;"></div>
    <div class="alerts" id="alerts">
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
                    <li class="nav-item"><a class="nav-link link-success" href="/courses" id="nav-courses">Курсы</a></li>
                    <li class="nav-item"><a class="nav-link link-success" href="/users?min=0&max=10" id="nav-users">Пользователи</a></li>
                    <li class="nav-item"><a class="nav-link link-success" href="/user?id=<?= $user->getId(); ?>" id="nav-profile">Мой профиль</a></li>
                </ul>
            </div>
        </nav>
        <div class="d-flex flex-column justify-content-center align-items-center container-fluid" style="flex: 1 0 auto;">