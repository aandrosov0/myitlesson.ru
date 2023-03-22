<?php 
namespace App\Rendering;

class HTMLMessage extends Message {
    public function __construct(string $message, int $responseCode = 200) {
        parent::__construct($message, $responseCode, 'Content-Type: text/html; charset=utf-8');
    }

    public function __toString(): string {
        $message = parent::__toString();

        if($this->responseCode >= 400) {
            ob_start();
            require __DIR__ . '/../../templates/resource_not_found.php';
            $htmlContent = ob_get_contents();
            ob_end_clean();
            return $htmlContent;
        }

        return $message . PHP_EOL;
    }
}