<?php 

namespace App\View;

use App\Entity\UserEntity;
use App\Rendering\JSONMessage;
use App\Rendering\Message;

class TokenView {
    private const CIPHER_METHOD = "AES-128-CTR";
    private const KEY = "JFKW329%s56FJHAK";
    private const IV = "2387543216865790";

    public static function get(string $username, string $password) {
        $user = UserEntity::getRepository()->findOneBy(['username' => $username]);

		if(!isset($user)) {
			return new JSONMessage(['err' => "User with username '$username' isn't found!", 'status_code' => Message::NOT_FOUND], 404);
		}

		$password = md5(sprintf(UserEntity::PASSWORD_KEY, $password));

		if($password != $user->getPassword()) {
			return new JSONMessage(['err' => 'Incorrect password!', 'status_code' => Message::AUTH_ERROR], 404);
		}

        $json = json_encode(['id' => $user->getId()]);

        $data = openssl_encrypt($json, TokenView::CIPHER_METHOD, TokenView::KEY, 0, TokenView::IV);

        return new JSONMessage(['token' => $data]);
    }

    public static function decryptToken(string $token) {
        $json = openssl_decrypt($token, TokenView::CIPHER_METHOD, TokenView::KEY, 0, TokenView::IV);
        $id = json_decode($json, true)['id'];

        return isset($id) ? UserEntity::find($id) : null;
    }
}