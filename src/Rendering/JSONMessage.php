<?php 
namespace App\Rendering;

use stdClass;

class JSONMessage extends Message {
    public function __construct(array|stdClass $message = new stdClass, int $responseCode = 200) {
        parent::__construct(json_encode($message, JSON_UNESCAPED_UNICODE), $responseCode, 'Content-Type: application/json; charset=utf-8');
    }
}