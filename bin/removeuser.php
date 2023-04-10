#!/usr/bin/php
<?php

use App\Entity\UserEntity;

require_once __DIR__ . '/../bootstrap.php';

$username = trim(readline('Input a username: '));

if(str_contains($username, ' ')) {
    echo 'Username mustn\'t contain spaces' . PHP_EOL;
    return;
}

$user = UserEntity::getRepository()->findOneBy(['username' => $username]);

if(!$user) {
    echo "User with username '$username' doesn't exist!" . PHP_EOL;
    return;
}

UserEntity::delete($user);
echo "User with username '$username' has deleted!" . PHP_EOL;