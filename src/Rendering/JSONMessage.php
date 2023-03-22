<?php 
namespace App\Rendering;

class JSONMessage extends Message {
    public function __construct(array $message, int $responseCode = 200) {
        parent::__construct(json_encode($message, JSON_UNESCAPED_UNICODE), $responseCode, 'Content-Type: application/json; charset=utf-8');
    }
}