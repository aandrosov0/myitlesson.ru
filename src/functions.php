<?php 
namespace App;
use App\Entity\UserEntity;
use PhpRouter\JSONMessage;

function authorize(string $route, callable $callback) : void {
    if(preg_match("@^$route\w*@", $_SERVER['REQUEST_URI'])) {
        $headers = getallheaders();
    
        if(!key_exists('Authorization', $headers)) {
            echo new JSONMessage(['message' => "Header 'Authorization' isn't specified!"], 401) . PHP_EOL;
            exit;
        }
    
        $header = explode(' ', $headers['Authorization'], 2);
        $scheme = $header[0];
    
        if($scheme != 'Basic') {
            echo new JSONMessage(['message' => 'Authorization scheme failed. Try Basic scheme'], 401) . PHP_EOL;
            exit;
        }
    
        if(!isset($header[1])) {
            echo new JSONMessage(['message' => "Token isn't specified!"], 401) . PHP_EOL;
            exit;
        }
    
        $token = $header[1];
    
        $userId = Security::getFromDataKey(Security::decryptData($token));
    
        if(!isset($userId)) {
            echo new JSONMessage(['message' => 'Token authorization failed!'], 401) . PHP_EOL;
            exit;
        }
    
        $user = UserEntity::find($userId);
    
        if(!isset($user)) {
            echo new JSONMessage(['message' => 'Token authorization failed!'], 401). PHP_EOL;
            exit;
        }
    
        $callback($user);
    }
}

function getOnlyClassName(string $className) : string {
	$className = explode('\\', $className);
    return end($className);
}

function isImageByMimeType(string $mime) : bool {
    return match(explode('/', $mime, 2)[1]) {
        'png', 'jpg', 'jpeg' => true,
        default => false,
    };
}

function saveFile(mixed $resource, string $filename) : void {
    $fp = fopen($filename, "w");

    while($data = fread($resource, 1024)) {
        fwrite($fp, $data);
    }

    fclose($fp);
}

function sendFile(string $filename) {
    $tmp = explode('.', basename($filename));
    $fileextension = end($tmp);

    http_response_code(200);
    header("Content-Type: image/$fileextension");
    header("Accept: image/$fileextension");
    header("Content-Disposition: attachment; filename=\"". basename($filename) ."\"");
    header('Content-Length: ' . filesize($filename));
    echo file_get_contents($filename);
}