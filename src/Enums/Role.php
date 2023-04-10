<?php 
namespace App\Enums;

enum Role : int {
    case ROOT = 0;
    case ADMIN = 1;
    case TEACHER = 2;
    case STUDENT = 3;

    public function strval(string $lang = 'ru'): string {
        if($lang == 'ru') {
            return match($this) {
                Role::ROOT => 'Рут',
                Role::ADMIN => 'Админ',
                Role::TEACHER => 'Учитель',
                Role::STUDENT => 'Студент',
            };
        } else if($lang == 'en') {
            return match($this) {
                Role::ROOT => 'Root',
                Role::ADMIN => 'Admin',
                Role::TEACHER => 'Teacher',
                Role::STUDENT => 'Student',
            };
        } else {
            throw new \Exception('Language not found!');
        }
    }
}