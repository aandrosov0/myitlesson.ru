<?php 

namespace App\View;

use App\Entity\ModuleEntity;
use App\Entity\CourseEntity;
use App\Rendering\JSONMessage;

class ModuleView {
	public static function get(int $id) {
		$module = ModuleEntity::getRepository()->find($id);

		if(empty($module)) {
			return new JSONMessage(['err' => "Module with id $id not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		return new JSONMessage($module->toArray());
	}

	public static function add(string $title, string $content, int $courseId) {
		$module = ModuleEntity::getRepository()->findOneBy(['title' => $title]);
		$course = CourseEntity::find($courseId);

		if(isset($module)) {
			return new JSONMessage(['err' => "Module with title '$title' already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		if(!isset($course)) {
			return new JSONMessage(['err' => "Course with id '$courseId' not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$module = ModuleEntity::new($title, $content, $course);
		ModuleEntity::add($module);

		return new JSONMessage(['id' => $module->getId()]);
	}

	public static function remove(int $id) {
		$module = ModuleEntity::find($id);

		if(!isset($module)) {
			return new JSONMessage(['err' => "Module with id '$id' doesn't exist!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		ModuleEntity::delete($module);

		return new JSONMessage([]);
	}	
}