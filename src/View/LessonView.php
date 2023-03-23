<?php 
namespace App\View;

use App\Entity\LessonEntity;
use App\Entity\ModuleEntity;
use App\Rendering\JSONMessage;

class CourseView {
	public static function get($id) {
		$lesson = LessonEntity::getRepository()->find($id);

		if(empty($lesson)) {
			return new JSONMessage(['err' => "Lesson with id $id not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		return new JSONMessage($lesson->toArray());
	}

	public static function add($title, $content, $moduleId) {
		$lesson = LessonEntity::getRepository()->findOneBy(['title' => $title]);
		$module = ModuleEntity::find($moduleId);

		if(isset($lesson)) {
			return new JSONMessage(['err' => "Lesson with title '$title' is already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		if(!isset($module)) {
			return new JSONMessage(['err' => "Module with id '$moduleId' not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$lesson = LessonEntity::new($title, $content, $module);
		LessonEntity::add($lesson);

		return new JSONMessage(['id' => $lesson->getId()]);
	}

	public static function remove($id) {
		$lesson = LessonEntity::find($id);

		if(!isset($lesson)) {
			return new JSONMessage(['err' => "Lesson with id '$id' ins't exists!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		LessonEntity::delete($lesson);

		return new JSONMessage([]);
	}	
}