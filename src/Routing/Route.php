<?php 
namespace App\Routing;

class Route {
	public function __construct(private string $route, private $method, private array $parameters = []) {
		$this->route = $route;
		$this->parameters = array_flip($parameters);
		$this->method = $method;
	}

    public function getRoute() {
        return $this->route;
    }

    public function setRoute(string $route) {
        $this->route = $route;
        return $this;
    }

    public function getParameters() {
        return $this->parameters;
    }

    public function setParameters(array $parameters) {
        $this->parameters = $parameters;
        return $this;
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }
}