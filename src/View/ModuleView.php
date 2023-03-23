<?php 

namespace App\View;

use App\Entity\ModuleEntity;
use App\Entity\CourseEntity;
use App\Rendering\JSONMessage;

class CourseView {
	public static function get($id) {
		$module = ModuleEntity::getRepository()->find($id);

		if(empty($module)) {
			return new JSONMessage(['err' => "Module with id $id not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		return new JSONMessage($module->toArray());
	}

	public static function add($title, $content, $courseId) {
		$module = ModuleEntity::getRepository()->findOneBy(['title' => $title]);
		$course = CourseEntity::find($courseId);

		if(isset($module)) {
			return new JSONMessage(['err' => "Module with title '$title' is already exists!", 'status_code' => JSONMessage::ALREADY_EXISTS], 404);
		} 

		if(!isset($course)) {
			return new JSONMessage(['err' => "Course with id '$courseId' not found!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		$module = ModuleEntity::new($title, $content, $course);
		ModuleEntity::add($module);

		return new JSONMessage(['id' => $module->getId()]);
	}

	public static function remove($id) {
		$module = ModuleEntity::find($id);

		if(!isset($module)) {
			return new JSONMessage(['err' => "Module with id '$id' ins't exists!", 'status_code' => JSONMessage::NOT_FOUND], 404);
		}

		ModuleEntity::delete($module);

		return new JSONMessage([]);
	}	
}