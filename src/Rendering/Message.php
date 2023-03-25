<?php 
namespace App\Rendering;

abstract class Message {
	public const NOT_FOUND = 0;
	public const ALREADY_EXISTS = 1;
	public const QUERY_EMPTY = 2;
	public const PAGE_NOT_FOUND = 3;
	public const AUTH_ERROR = 4;

    public function __construct(protected string $message, protected int $responseCode, protected string $headerContent) {}
    
    public function __toString(): string {
        header($this->headerContent);
        http_response_code($this->responseCode);
        return $this->message . PHP_EOL;
    }
}