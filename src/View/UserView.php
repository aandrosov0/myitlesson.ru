<?php 
namespace App\View;
use App\Entity\UserEntity;
use App\Enums\Role;
use App\Enums\StatusCode;
use App\Security;
use PhpRouter\JSONMessage;

class UserView {

    public static function get($id) : void {
        $user = UserEntity::find($id);

        if(!isset($user)) {
            echo new JSONMessage(['message' => "User with id $id isn't exist!"], 404);
            return;
        } 

        echo new JSONMessage($user->toArray(), 200);
    }

    public static function add() : void {
        extract($_POST);

        if(!isset($username)) {
            echo new JSONMessage(['message' => 'Username isn\'t specified!'], 400);
            return;
        }

        if(!isset($password)) {
            echo new JSONMessage(['message' => 'Password isn\'t specified!'], 400);
            return;
        }

        if(!isset($role)) {
            echo new JSONMessage(['message' => 'Role isn\'t specified!'], 400);
            return;
        }

        if(!is_numeric($role)) {
            echo new JSONMessage(['message' => 'Role isn\'t a number!', 'code' => StatusCode::NUMBER_FORMAT], 400);
            return;
        }

        try {
            $user = UserEntity::new($username, password_hash(Security::getPassword($password), PASSWORD_DEFAULT), Role::from($role));
            UserEntity::add($user);
            echo new JSONMessage(['id' => $user->getId()], 200);
        } catch(\ValueError $error) {
            echo new JSONMessage(['message' => 'Role number error!'], 400);
        }
    }

    public static function delete($id) : void {
        $user = UserEntity::find($id);

        if(!isset($user)) {
            echo new JSONMessage(['message' => "User with id $id doesn't exist!"], 404);
            return;
        }

        UserEntity::delete($user);
        echo new JSONMessage(new \stdClass, 200);
    }

    public static function getAmount(int $limit, int $offset) : void {
        $foundUsers = UserEntity::getRepository()->findBy([], null, $limit, $offset);

        $users = [];
        foreach($foundUsers as $user) {
            $users[] = $user->toArray();
        }

        echo new JSONMessage($users, 200);
    }
}