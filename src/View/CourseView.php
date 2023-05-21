<?php 
namespace App\View;

use App\Entity\CourseEntity;
use App\Entity\ModuleEntity;
use App\Entity\UserEntity;
use App\Rendering\JSONMessage;

class CourseView {
	public static function get(int $id) {
		$course = CourseEntity::getRepository()->find($id);

		if(empty($course)) {
			return new JSONMessage(['err' => "Course with id $id not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		return new JSONMessage($course->toArray());
	}

	public static function getAll(int $limit, int $offset) {
		$courses = CourseEntity::getRepository()->findBy([], null, $limit, $offset);

		for($i = 0; $i < count($courses); $i++) {
			$courses[$i] = $courses[$i]->toArray();
		}

		return new JSONMessage($courses);
	}

	public static function add(string $title, string $description, string $authorId) {
		$course = CourseEntity::getRepository()->findOneBy(['title' => $title]);
		$author = UserEntity::find($authorId);

		if(isset($course)) {
			return new JSONMessage(['err' => "Course with title '$title' already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		if(!isset($author)) {
			return new JSONMessage(['err' => "Author with id '$authorId' not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$course = CourseEntity::new($title, $description, $author);
		CourseEntity::add($course);

		return new JSONMessage(['id' => $course->getId()]);
	}

	public static function remove(int $id) {
		$course = CourseEntity::find($id);

		if(!isset($course)) {
			return new JSONMessage(['err' => "Course with id '$id' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		CourseEntity::delete($course);

		return new JSONMessage();
	}
	
	public static function addModule(int $courseId, int $moduleId) {
		$course = CourseEntity::find($courseId);

		if(!isset($course)) {
			return new JSONMessage(['err' => "Course with id '$courseId' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$module = ModuleEntity::find($moduleId);

		if(!isset($module)) {
			return new JSONMessage(['err' => "Module with id '$moduleId' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$course->addModule($module);
		CourseEntity::add($course);
		return new JSONMessage();
	}

	public static function removeModule(int $courseId, int $moduleId) {
		$course = CourseEntity::find($courseId);

		if(!isset($course)) {
			return new JSONMessage(['err' => "Course with id '$courseId' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$module = ModuleEntity::find($moduleId);

		if(!isset($module)) {
			return new JSONMessage(['err' => "Module with id '$moduleId' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$course->removeModule($module);
		CourseEntity::add($course);
		return new JSONMessage();
	}

	public static function imageSet(int $id) {
		$file = isset($_FILES['image']) ?  $_FILES['image'] : null;

		if(!isset($file)) {
			return new JSONMessage(['err' => 'file not found!', 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		move_uploaded_file($file['tmp_name'], UPLOAD_DIR . "/course/$id.png");

		return new JSONMessage();
	}

	public static function imageGet(int $id) {

		$fileName = UPLOAD_DIR . "/course/$id.png";
		
		if(!file_exists($fileName)) {
			return new JSONMessage(['err' => 'file not found!', 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($fileName));
		readfile($fileName);
		exit;
	}
}