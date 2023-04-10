<?php 
namespace App\View;

use App\Entity\UserEntity;
use App\Rendering\TemplateManager;

class AuthView {
	public static function login(string $username, string $password) {
		$user = UserEntity::getRepository()->findOneBy(['username' => $username]);

		if(!isset($user)) {
			return TemplateManager::render('login', ['err' => 'Неверный логин']);
		}

		$password = md5(sprintf(UserEntity::PASSWORD_KEY, $password));

		if($password != $user->getPassword()) {
			return TemplateManager::render('login', ['err' => 'Неверный пароль']);
		}

		$_SESSION['ID'] = $user->getId();
		header(header: 'Location: /');
	}
}