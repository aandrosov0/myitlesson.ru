<?php 
namespace App\Routing;

class Route {
	private string $route;
	private array $parameters;
	private $method;

	public function __construct(string $route, array $parameters, callable $method) {
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