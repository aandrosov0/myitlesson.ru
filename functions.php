<?php 
namespace App;

use App\Entity\UserEntity;
use App\View\TokenView;

function getOnlyClassName(string $className) {
	$className = explode('\\', $className);
    return end($className);
}

function authUser() {
    return match($_SERVER['REQUEST_METHOD']) {
        'POST' => isset($_POST['token']) ? TokenView::decryptToken($_POST['token']) : null,
        'GET' => isset($_SESSION['ID']) ? UserEntity::find($_SESSION['ID']) : null,
    };
}