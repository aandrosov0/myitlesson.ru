<?php 
namespace App\View;

use App\Entity\LessonEntity;
use App\Entity\ModuleEntity;
use App\Rendering\JSONMessage;

class LessonView {
	public static function get(int $id) {
		$lesson = LessonEntity::getRepository()->find($id);

		if(empty($lesson)) {
			return new JSONMessage(['err' => "Lesson with id $id not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		return new JSONMessage($lesson->toArray());
	}

	public static function add(string $title, string $content, int $moduleId) {
		$lesson = LessonEntity::getRepository()->findOneBy(['title' => $title]);
		$module = ModuleEntity::find($moduleId);

		if(isset($lesson)) {
			return new JSONMessage(['err' => "Lesson with title '$title' already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		if(!isset($module)) {
			return new JSONMessage(['err' => "Module with id '$moduleId' not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$lesson = LessonEntity::new($title, $content, $module);
		LessonEntity::add($lesson);

		return new JSONMessage(['id' => $lesson->getId()]);
	}

	public static function remove(int $id) {
		$lesson = LessonEntity::find($id);

		if(!isset($lesson)) {
			return new JSONMessage(['err' => "Lesson with id '$id' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		LessonEntity::delete($lesson);

		return new JSONMessage();
	}
}