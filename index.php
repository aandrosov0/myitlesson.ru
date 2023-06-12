<?php 
use PhpRouter\JSONMessage;
use PhpRouter\Message;
require_once 'bootstrap.php';

try {
    PhpRouter\POST('/api/auth', [App\View\AuthView::class, 'authorize']);

    App\authorize('/api', function(App\Entity\UserEntity $user) {
        PhpRouter\GET('/api/user/(\d+)', [App\View\UserView::class, 'get']);
        PhpRouter\POST('/api/user', [App\View\UserView::class, 'add']);
        PhpRouter\DELETE('/api/user/(\d+)', [App\View\UserView::class, 'delete']);

        PhpRouter\GET('/api/users/(\d+)/offset/(\d+)', [App\View\UserView::class, 'getAmount']);
        
        PhpRouter\GET('/api/course/(\d+)', [App\View\CourseView::class, 'get']);
        PhpRouter\GET('/api/course/(\d+)/image', [App\View\CourseView::class, 'getImage']);
        PhpRouter\POST('/api/course', [App\View\CourseView::class, 'add']);
        PhpRouter\DELETE('/api/course/(\d+)', [App\View\CourseView::class, 'delete']);
        PhpRouter\PUT('/api/course/(\d+)/image', [App\View\CourseView::class, 'uploadImage']);

        PhpRouter\GET('/api/courses/(\d+)/offset/(\d+)', [App\View\CourseView::class, 'getAmount']);
    
        PhpRouter\GET('/api/module/(\d+)', [App\View\ModuleView::class, 'get']);
        PhpRouter\POST('/api/module', [App\View\ModuleView::class, 'add']);
        PhpRouter\DELETE('/api/module/(\d+)', [App\View\ModuleView::class, 'delete']);
    
        PhpRouter\GET('/api/lesson/(\d+)', [App\View\LessonView::class, 'get']);
        PhpRouter\POST('/api/lesson', [App\View\LessonView::class, 'add']);
        PhpRouter\DELETE('/api/lesson/(\d+)', [App\View\LessonView::class, 'delete']);

        echo new JSONMessage(['message' => 'Resource not found!'], 404) . PHP_EOL;
        exit;
    });
    
    echo new Message('<h1>Resource not found!</h1>' . PHP_EOL, 404, 'text/html; charset=utf-8');
} catch(Throwable $error) {
    echo new Message('<h1>Internal Server Error</h1>' . PHP_EOL, 500, 'text/html; charset=utf-8');
    error_log($error);
}