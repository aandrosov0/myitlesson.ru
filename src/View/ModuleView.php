<?php 
namespace App\View;
use App\Entity\ModuleEntity;
use App\Entity\CourseEntity;
use App\Enums\StatusCode;
use PhpRouter\JSONMessage;

class ModuleView {

    public static function get($id) : void {
        $module = ModuleEntity::find($id);

        if(!isset($module)) {
            echo new JSONMessage(['message' => "Module with id $id isn't found!"], 404);
            return;
        }

        echo new JSONMessage($module->toArray(), 200);
    }

    public static function add() : void {
        extract($_POST);

        if(!isset($title)) {
            echo new JSONMessage(['message' => 'Title isn\'t specified!'], 400);
            return;
        }

        if(!isset($content)) {
            echo new JSONMessage(['message' => 'Content isn\'t specified!'], 400);
            return;
        }

        if(!isset($courseId)) {
            echo new JSONMessage(['message' => 'CourseId isn\'t specified!'], 400);
            return;
        }

        if(!ctype_digit($courseId)) {
            echo new JSONMessage(['message' => 'CourseId isn\'t a number!', 'code' => StatusCode::NUMBER_FORMAT], 400);
            return;
        }
        
        $course = CourseEntity::find($courseId);

        if(!isset($course)) {
            echo new JSONMessage(['message' => "Author isn't with id $courseId found!"], 404);
            return;
        }

        $module = ModuleEntity::new($title, $content, $course);
        ModuleEntity::add($module);

        echo new JSONMessage(['id' => $module->getId()], 200);
    }

    public static function delete($id) : void {
        $module = ModuleEntity::find($id);

        if(!isset($module)) {
            echo new JSONMessage(['message' => "Module with id $id isn't found!"], 404);
            return;
        }

        ModuleEntity::delete($module);
        echo new JSONMessage(new \stdClass, 200);
    } 
}