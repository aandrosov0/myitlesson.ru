<?php 
namespace App;

use App\Entity\UserEntity;
use App\View\TokenView;

function getOnlyClassName(string $className) {
	$className = explode('\\', $className);
    return end($className);
}

function authUser(): UserEntity|null {
    return match($_SERVER['REQUEST_METHOD']) {
        'POST' => getTokenFromHeader(),
        'GET' => isset($_SESSION['ID']) ? UserEntity::find($_SESSION['ID']) : null,
    };
}

function getTokenFromHeader(): UserEntity|null {
    $headers = getallheaders();

    $header = key_exists('Authorization', $headers) ? $headers['Authorization'] : '';

    if(!preg_match('/^Basic\s\w+\W*/', $header)) {
        return null;
    }

    $words = explode(' ', $header, 2);

    return TokenView::decryptToken($words[1]);
}