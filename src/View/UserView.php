<?php 
namespace App\View;

use App\Enums\Role;
use App\Entity\CourseEntity;
use App\Entity\UserEntity;
use App\Rendering\JSONMessage;

class UserView {
	public static function get(int $id) {
		$user = UserEntity::find($id);

		if(empty($user)) {
			return new JSONMessage(['err' => "User with id $id not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		return new JSONMessage($user->toArray());
	}

	public static function getAll(int $limit, int $offset) {
		$users = UserEntity::getRepository()->findBy([], null, $limit, $offset);

		for($i = 0; $i < count($users); $i++) {
			$users[$i] = $users[$i]->toArray();
		}

		return new JSONMessage($users);
	}

	public static function add(string $username, string $password, int $role) {
		$user = UserEntity::getRepository()->findOneBy(['username' => $username]);

		if(isset($user)) {
			return new JSONMessage(['err' => "User with username '$username' already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		if(strlen($password) < 8) {
			return new JSONMessage(['err' => 'Password must be more than 8 characters', 'status_code' => JSONMessage::PASSWORD_LENGTH], 404);
		}

		$user = UserEntity::new($username, $password, Role::from($role));
		UserEntity::add($user);

		return new JSONMessage(['id' => $user->getId()]);
	}

	public static function remove(int $id) {
		$user = UserEntity::find($id);

		if(!isset($user)) {
			return new JSONMessage(['err' => "User with id '$id' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		UserEntity::delete($user);

		return new JSONMessage();
	}

	public static function edit(int $id, string $username, string $password, int $role) {
		$user = UserEntity::find($id);

		if(!isset($user)) {
			return new JSONMessage(['err' => "User with id '$id' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		} 

		$cmpUser = UserEntity::getRepository()->findOneBy(['username' => $username]);

		if(isset($cmpUser) && $cmpUser !== $user) {
			return new JSONMessage(['err' => "User with username '$username' already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		$user->setUsername($username)
			->setPassword(empty($password) ? $user->getPassword() : md5(sprintf(UserEntity::PASSWORD_KEY, $password)))
			->setRole(Role::from($role));
		UserEntity::add($user);

		return new JSONMessage();
	}

	public static function addCourse(int $userId, int $courseId) {
		$user = UserEntity::find($userId);

		if(!isset($user)) {
			return new JSONMessage(['err' => "User with id '$userId' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$course = CourseEntity::find($courseId);

		if(!isset($course)) {
			return new JSONMessage(['err' => "Course with id '$courseId' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		if($user->getCourses()->contains($course)) {
			return new JSONMessage(['err' => "User with id '$userId' already added at course with id '$courseId'", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		}

		$user->addCourse($course);
		UserEntity::add($user);

		return new JSONMessage();
	}

	public static function removeCourse(int $userId, int $courseId) {
		$user = UserEntity::find($userId);

		if(!isset($user)) {
			return new JSONMessage(['err' => "User with id '$userId' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$course = CourseEntity::find($courseId);

		if(!isset($course)) {
			return new JSONMessage(['err' => "Course with id '$courseId' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		if(!$user->getCourses()->contains($course)) {
			return new JSONMessage(['err' => "User with id '$userId' doesn't have course with id '$courseId'", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		}

		$user->removeCourse($course);
		UserEntity::add($user);

		return new JSONMessage();
	}
}