<?php

use App\Entity\UserEntity;
use App\Rendering\JSONMessage;
use App\Rendering\TemplateManager;
use App\Routing\Route;
use App\Routing\Router;

use App\View\CourseView;
use App\View\ModuleView;
use App\View\LessonView;
use App\View\UserView;

require_once __DIR__ . '/bootstrap.php';



if(!isset($_SESSION['ID']) && $_SERVER['REQUEST_URI'] != '/login') {
	header("Location: /login");
	exit;
}

$router = new Router();

$router->GET(new Route('/', function() { return new JSONMessage(UserEntity::find(2)->toArray());}));

$router->GET(new Route('/login', function() {
	return TemplateManager::render('login', ['pageTitle' => APP_NAME . " - Вход"]);
}));

$router->POST(new Route('/user/get', [UserView::class, 'get'], ['id']));
$router->POST(new Route('/user/remove', [UserView::class, 'remove'], ['username']));
$router->POST(new Route('/user/add', [UserView::class, 'add'], ['username', 'password']));
$router->POST(new Route('/user/addcourse', [UserView::class, 'addCourse'], ['user', 'course']));
$router->POST(new Route('/user/removecourse', [UserView::class, 'removeCourse'], ['user', 'course']));

$router->POST(new Route('/course/get', [CourseView::class, 'get'], ['id']));
$router->POST(new Route('/course/remove', [CourseView::class, 'remove'], ['id']));
$router->POST(new Route('/course/add', [CourseView::class, 'add'], ['title', 'description', 'author']));

$router->POST(new Route('/module/get', [ModuleView::class, 'get'], ['id']));
$router->POST(new Route('/module/remove', [ModuleView::class, 'remove'], ['id']));
$router->POST(new Route('/module/add', [ModuleView::class, 'add'], ['title', 'content', 'course']));

$router->POST(new Route('/lesson/get', [LessonView::class, 'get'], ['id']));
$router->POST(new Route('/lesson/remove', [LessonView::class, 'remove'], ['id']));
$router->POST(new Route('/lesson/add', [LessonView::class, 'add'], ['title', 'content', 'module']));

echo $router->route() . PHP_EOL;