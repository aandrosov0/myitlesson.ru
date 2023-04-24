<?php

use App\Entity\CourseEntity;
use App\Rendering\JSONMessage;
use App\Rendering\Message;
use App\Rendering\TemplateManager;
use App\Routing\Route;
use App\Routing\Router;
use App\View\AuthView;
use App\View\CourseView;
use App\View\ModuleView;
use App\View\LessonView;
use App\View\UserView;
use App\View\TokenView;

use function App\authUser;

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/bootstrap.php';

$superUser = authUser();

if($superUser == null && $_SERVER['REQUEST_METHOD'] == 'GET' && $_SERVER['REQUEST_URI'] != '/login') {
	header("Location: /login");
	exit;
} else if($superUser == null && $_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] != '/token/get') {
	echo new JSONMessage(['err' => 'Token auth failed!', 'status_code' => Message::AUTH_ERROR], 404);
	exit;
}

unset($_POST['token']);

$router = new Router();

$router->GET(new Route('/', function() { 
	return TemplateManager::render('index', ['pageTitle' => APP_NAME . " - Панель"]);
}));

$router->GET(new Route('/users', function($limit, $offset) {
	global $superUser;
	return TemplateManager::render('users', ['limit' => $limit, 'offset' => $offset, 'superUser' => $superUser]);
}, true));

$router->GET(new Route('/users', function($limit, $offset, $username) {
	global $superUser;
	return TemplateManager::render('users', ['limit' => $limit, 'offset' => $offset, 'username' => $username, 'superUser' => $superUser]);
}, true));

$router->GET(new Route('/login', function() {
	return TemplateManager::render('login', ['pageTitle' => APP_NAME . " - Вход"]);
}));

$router->GET(new Route('/courses', function($limit, $offset) {
	global $superUser;
	return TemplateManager::render('courses', ['limit' => $limit, 'offset' => $offset, 'superUser' => $superUser]);
}));

$router->GET(new Route('/course', function($id) {
	global $superUser;
	return TemplateManager::render('course', ['course' => CourseEntity::find($id), 'superUser' => $superUser]);
}));

$router->POST(new Route('/login', [AuthView::class, 'login'], true));
$router->POST(new Route('/token/get', [TokenView::class, 'get']));
$router->POST(new Route('/', function() {
	return new JSONMessage();
}));

$router->POST(new Route('/user/get', [UserView::class, 'get']));
$router->POST(new Route('/user/remove', [UserView::class, 'remove']));
$router->POST(new Route('/user/edit', [UserView::class, 'edit'], true));
$router->POST(new Route('/user/add', [UserView::class, 'add']));
$router->POST(new Route('/user/addcourse', [UserView::class, 'addCourse']));
$router->POST(new Route('/user/removecourse', [UserView::class, 'removeCourse']));

$router->POST(new Route('/course/get', [CourseView::class, 'get']));
$router->POST(new Route('/course/remove', [CourseView::class, 'remove']));
$router->POST(new Route('/course/add', [CourseView::class, 'add']));
$router->POST(new Route('/course/addmodule', [CourseView::class, 'addModule']));
$router->POST(new Route('/course/removemodule', [CourseView::class, 'removeModule']));

$router->POST(new Route('/module/get', [ModuleView::class, 'get']));
$router->POST(new Route('/module/edit', [ModuleView::class, 'edit']));
$router->POST(new Route('/module/remove', [ModuleView::class, 'remove']));
$router->POST(new Route('/module/add', [ModuleView::class, 'add']));
$router->POST(new Route('/module/addlesson', [ModuleView::class, 'addLesson']));
$router->POST(new Route('/module/removelesson', [ModuleView::class, 'removeLesson']));

$router->POST(new Route('/lesson/get', [LessonView::class, 'get']));
$router->POST(new Route('/lesson/remove', [LessonView::class, 'remove']));
$router->POST(new Route('/lesson/add', [LessonView::class, 'add']));

echo $router->route() . PHP_EOL;