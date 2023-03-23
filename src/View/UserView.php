<?php 
namespace App\View;

use App\Entity\UserEntity;
use App\Rendering\JSONMessage;

class UserView {
	public static function get($id) {
		$user = UserEntity::getRepository()->find($id);

		if(empty($user)) {
			return new JSONMessage(['err' => "User with id $id not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		return new JSONMessage($user->toArray());
	}

	public static function add($username, $password) {
		$user = UserEntity::getRepository()->findOneBy(['username' => $username]);

		if(isset($user)) {
			return new JSONMessage(['err' => "User with username '$username' is already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		$user = UserEntity::new($username, $password);
		UserEntity::add($user);

		return new JSONMessage(['id' => $user->getId()]);
	}

	public static function remove($username) {
		$user = UserEntity::getRepository()->findOneBy(['username' => $username]);

		if(!isset($user)) {
			return new JSONMessage(['err' => "User with username '$username' ins't exists!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		UserEntity::delete($user);

		return new JSONMessage([]);
	}
}