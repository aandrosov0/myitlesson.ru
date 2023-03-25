<?php 
namespace App\View;

use App\Entity\UserEntity;
use App\Rendering\JSONMessage;

class AuthView {
	public static function login(string $username, string $password) {
		$user = UserEntity::getRepository()->findOneBy(['username' => $username]);

		if(!isset($user)) {
			return new JSONMessage(['err' => "User with username '$username' isn't found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$password = md5(sprintf(UserEntity::PASSWORD_KEY, $password));

		if($password != $user->getPassword()) {
			return new JSONMessage(['err' => "Password are incorrect!", 'status_code' => JSONMessage::AUTH_ERROR], 404);
		}

		$_SESSION['ID'] = $user->getId();
		header(header: '/', response_code: 200);
	}
}