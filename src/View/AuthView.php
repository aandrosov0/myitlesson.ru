<?php 
namespace App\View;
use App\Entity\UserEntity;
use App\Security;
use PhpRouter\JSONMessage;

class AuthView {

    public static function authorize() : JSONMessage {
        extract($_POST);

        if(!isset($username)) {
            return new JSONMessage(['message' => 'Username isn\'t specified!'], 400);
        }

        if(!isset($password)) {
            return new JSONMessage(['message' => 'Password isn\'t specified!'], 400);
        }

        $user = UserEntity::getRepository()->findOneBy(['username' => $username]);

        if(!isset($user)) {
            return new JSONMessage(['message' => "User isn't found with username $username"], 404);
        }

        if(!password_verify(Security::getPassword($password), $user->getPassword())) {
            return new JSONMessage(['message' => "Authorization failed!"], 401);
        }

        return new JSONMessage([
            'token' => Security::encryptData(Security::getDataKey($user->getId())),
            'id' => $user->getId()], 200);
    }
}