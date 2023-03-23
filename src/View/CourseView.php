<?php 
namespace App\View;

use App\Entity\CourseEntity;
use App\Entity\UserEntity;
use App\Rendering\JSONMessage;

class CourseView {
	public static function get($id) {
		$course = CourseEntity::getRepository()->find($id);

		if(empty($course)) {
			return new JSONMessage(['err' => "Course with id $id not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		return new JSONMessage($course->toArray());
	}

	public static function add($title, $description, $authorId) {
		$course = CourseEntity::getRepository()->findOneBy(['title' => $title]);
		$author = UserEntity::find($authorId);

		if(isset($course)) {
			return new JSONMessage(['err' => "Course with title '$title' is already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		if(!isset($author)) {
			return new JSONMessage(['err' => "Author with id '$authorId' not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$course = CourseEntity::new($title, $description, $author);
		CourseEntity::add($course);

		return new JSONMessage(['id' => $course->getId()]);
	}

	public static function remove($id) {
		$course = CourseEntity::find($id);

		if(!isset($course)) {
			return new JSONMessage(['err' => "Course with id '$id' ins't exists!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		CourseEntity::delete($course);

		return new JSONMessage([]);
	}	
}