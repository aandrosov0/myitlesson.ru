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
}