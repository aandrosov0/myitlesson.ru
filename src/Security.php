<?php 
namespace App;

class Security {

    private const PASSWORD_KEY = 'C1F1BB4AF6465%s4D2A659C8FD832A4';
    private const DATA_KEY1 = 'F1CF6F7D37F7ED';
    private const DATA_KEY2 = '225DFB288FDB859';

    private const CHIPER_ALGO = "AES-128-CTR";
    private const ENCRYPTION_KEY = "F29D3B61CA5CF54B592B1C93218FD";

    private const ENCRYPTION_IV = "A69FF7EB28C3CE1A";

    public static function passwordHash(string $password) : string {
        return password_hash(static::getPassword($password), PASSWORD_DEFAULT);
    }

    public static function getPassword(string $password) : string {
        return sprintf(static::PASSWORD_KEY, $password);
    }

    public static function getDataKey(string $data) : string {
        return static::DATA_KEY1 . $data . static::DATA_KEY2;
    }

    public static function getFromDataKey(string $dataKey) : string|null {
        $match = '@^'.static::DATA_KEY1.'(\w+)'.static::DATA_KEY2.'$@';
        preg_match($match, $dataKey, $matches);


        if(isset($matches[1])) {
            return $matches[1];
        }

        return null;
    }

    public static function encryptData(string $data) : string {
        return openssl_encrypt($data, static::CHIPER_ALGO, static::ENCRYPTION_KEY, 0, static::ENCRYPTION_IV);
    }

    public static function decryptData(string $data) : string {
        return openssl_decrypt($data, static::CHIPER_ALGO, static::ENCRYPTION_KEY, 0, static::ENCRYPTION_IV);
    }
}