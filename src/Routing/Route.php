<?php 
namespace App\Routing;

use Closure;

class Route {
	public function __construct(private string $route, private $method, private bool $isView = false) {
		$this->method = Closure::fromCallable($method);
	}

    public function getRoute() {
        return $this->route;
    }

    public function setRoute(string $route) {
        $this->route = $route;
        return $this;
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    public function isView() {
        return $this->isView;
    }

    public function setView(bool $isView) {
        $this->isView = $isView;
        return $this;
    }
}