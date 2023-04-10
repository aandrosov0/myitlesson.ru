#!/usr/bin/php
<?php

use App\Entity\UserEntity;

require_once __DIR__ . '/../bootstrap.php';

$username = trim(readline('Input a username: '));

if(str_contains($username, ' ')) {
    echo 'Username mustn\'t contain spaces' . PHP_EOL;
    return;
}

$password = trim(readline('Input a password: '));

if(str_contains($password, ' ')) {
    echo 'Password mustn\'t contain spaces' . PHP_EOL;
    return;
}

$role = readline('Input a role (0 - ROOT, 1 - ADMIN, 2 - TEACHER, 3 - STUDENT): ');

try {
    $role = \App\Enums\Role::from($role);
} catch(ValueError $ignored) {
    echo 'Role isn\'t found!' . PHP_EOL;
    return;
}

$user = UserEntity::getRepository()->findOneBy(['username' => $username]);

if($user) {
    echo "User with username '$username' already exists!" . PHP_EOL;
    return;
}

$user = UserEntity::new($username, md5(sprintf(UserEntity::PASSWORD_KEY, $password)), $role);
$role = $role->strval('en');

UserEntity::add($user);

echo "User with username '$username' and role '$role' has created!" . PHP_EOL;