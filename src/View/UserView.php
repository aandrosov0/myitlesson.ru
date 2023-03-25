<?php 
namespace App\View;

use App\Entity\CourseEntity;
use App\Entity\UserEntity;
use App\Rendering\JSONMessage;

class UserView {
	public static function get(int $id) {
		$user = UserEntity::getRepository()->find($id);

		if(empty($user)) {
			return new JSONMessage(['err' => "User with id $id not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		return new JSONMessage($user->toArray());
	}

	public static function add(string $username, string $password) {
		$user = UserEntity::getRepository()->findOneBy(['username' => $username]);

		if(isset($user)) {
			return new JSONMessage(['err' => "User with username '$username' already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		$user = UserEntity::new($username, $password);
		UserEntity::add($user);

		return new JSONMessage(['id' => $user->getId()]);
	}

	public static function remove(string $username) {
		$user = UserEntity::getRepository()->findOneBy(['username' => $username]);

		if(!isset($user)) {
			return new JSONMessage(['err' => "User with username '$username' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		UserEntity::delete($user);

		return new JSONMessage([]);
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

		return new JSONMessage([]);
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

		return new JSONMessage([]);
	}
}