<?php 
namespace App\View;
use App\Entity\CourseEntity;
use App\Entity\UserEntity;
use App\Enums\StatusCode;
use PhpRouter\JSONMessage;

class CourseView {

    public static function get(int $id) : void {
        $course = CourseEntity::find($id);

        if(!isset($course)) {
            echo new JSONMessage(['message' => "Course with id $id isn't found!"], 404);
            return;
        }

        echo new JSONMessage($course->toArray(), 200);
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

        if(!isset($authorId)) {
            echo new JSONMessage(['message' => 'AuthorId isn\'t specified!'], 400);
            return;
        }

        if(!ctype_digit($authorId)) {
            echo new JSONMessage(['message' => 'AuthorId isn\'t a number!', 'code' => StatusCode::NUMBER_FORMAT], 400);
            return;
        }
        
        $author = UserEntity::find($authorId);

        if(!isset($author)) {
            echo new JSONMessage(['message' => "Author isn't with id $authorId found!"], 404);
            return;
        }

        $course = CourseEntity::new($title, $content, $author);
        CourseEntity::add($course);

        echo new JSONMessage(['id' => $course->getId()], 200);
    }

    public static function delete(int $id) : void {
        $course = CourseEntity::find($id);

        if(!isset($course)) {
            echo new JSONMessage(['message' => "Course with id $id isn't found!"], 404);
            return;
        }

        CourseEntity::delete($course);
        echo new JSONMessage(new \stdClass, 200);
    } 

    public static function uploadImage(int $id) : void {
        $course = CourseEntity::find($id);

        if(!isset($course)) {
            echo new JSONMessage(['message' => "Course with id $id isn't found!"], 404);
            return;
        }

        $headers = getallheaders();

        error_log(print_r($headers, true));
        
        if(!key_exists('Content-Type', $headers)) {
            echo new JSONMessage(['message' => 'Header Content-Type isn\'t set'], 400);
            return;
        }

        if(!\App\isImageByMimeType($headers['Content-Type'])) {
            echo new JSONMessage(['message' => 'File isn\'t image type(png, jpeg, jpg)', 'code' => StatusCode::FILE_FORMAT], 400);
            return;
        }

        $image = fopen('php://input', 'r');
        \App\saveFile($image, IMAGE_DIR . "/course_$id." . explode('/', $headers['Content-Type'])[1]);
        fclose($image);
        
        echo new JSONMessage(new \stdClass, 200);
    }

    public static function getImage(int $id) : void {
        $course = CourseEntity::find($id);

        if(!isset($course)) {
            echo new JSONMessage(['message' => "Course with id $id isn't found!"], 404);
            return;
        }

        $matches = glob(IMAGE_DIR . "/course_$id.*");

        if(!$matches) {
            echo new JSONMessage(['message' => "Image ins't found!"], 404);
            return;
        }

        \App\sendFile(end($matches));
    }

    public static function getAmount(int $limit, int $offset) : void {
        $foundCourses = CourseEntity::getRepository()->findBy([], null, $limit, $offset);
        
        error_log(count($foundCourses));

        $courses = [];
        foreach($foundCourses as $course) {
            $courses[] = $course->toArray();
        }

        echo new JSONMessage($courses, 200);
    }
}