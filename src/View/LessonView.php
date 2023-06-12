<?php 
namespace App\View;

use App\Entity\LessonEntity;
use App\Entity\ModuleEntity;
use App\Enums\StatusCode;

use PhpRouter\JSONMessage;

class LessonView {

    public static function get($id) : void{
        $lesson = LessonEntity::find($id);

        if(!isset($lesson)) {
            echo new JSONMessage(['message' => "Lesson with id $id isn't found!"], 404);
            return;
        }

        echo new JSONMessage($lesson->toArray(), 200);
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

        if(!isset($moduleId)) {
            echo new JSONMessage(['message' => 'ModuleId isn\'t specified!'], 400);
            return;
        }

        if(!ctype_digit($moduleId)) {
            echo new JSONMessage(['message' => 'ModuleId isn\'t a number!', 'code' => StatusCode::NUMBER_FORMAT], 400);
            return;
        }
        
        $module = ModuleEntity::find($moduleId);

        if(!isset($module)) {
            echo new JSONMessage(['message' => "Module isn't with id $moduleId found!"], 404);
            return;
        }

        $lesson = LessonEntity::new($title, $content, $module);
        LessonEntity::add($lesson);

        echo new JSONMessage(['id' => $lesson->getId()], 200);
    }

    public static function delete($id) : void {
        $lesson = LessonEntity::find($id);

        if(!isset($lesson)) {
            echo new JSONMessage(['message' => "Lesson with id $id isn't found!"], 404);
            return;
        }

        LessonEntity::delete($lesson);
        echo new JSONMessage(new \stdClass, 200);
    } 
}