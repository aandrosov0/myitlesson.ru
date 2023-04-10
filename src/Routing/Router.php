<?php 
namespace App\Routing;

use App\Rendering\HTMLMessage;
use App\Rendering\JSONMessage;

class Router {
	public function __construct(private array $getRoutes = [], private array $postRoutes = []){}

	public function route() {
		switch($_SERVER['REQUEST_METHOD']) {
			case 'GET':
				return $this->routesAnalyse($this->getRoutes, $_GET);
			case 'POST':
				return $this->routesAnalyse($this->postRoutes, $_POST);
		}
	}

	public function GET(Route $route) {
		$this->getRoutes[] = $route;
	}

	public function POST(Route $route) {
		$this->postRoutes[] = $route;
	}

	private function routesAnalyse(array $routes, array $query) {
		$url = new URL($_SERVER['REQUEST_URI']);

		foreach($routes as $route) {
			$func = (new \ReflectionFunction($route->getMethod()));

			$parameterNames = [];
			foreach($func->getParameters() as $parameter) {
				$parameterNames[$parameter->getName()] = null;
			}

			$parametersDiff = array_diff_key($parameterNames, $query);

			if(($route->getRoute() != $url->getPath()) || !empty($parametersDiff) || count($parameterNames) != count($query)) {
				continue;
			} 

			if(!$route->isView()) {
				foreach($query as $key => $value) {
					if(trim($value) == "" && $_SERVER['REQUEST_METHOD'] == 'POST') {
						return new JSONMessage(['err' => "Entry '$key' is empty!", 'status_code' => JSONMessage::QUERY_EMPTY], 404);
					} 
					
					if(trim($value) == "" && $_SERVER['REQUEST_METHOD'] == 'GET') {
						return new HTMLMessage("Entry '$key' is empty!");
					}
				}
			}
			
			foreach($parameterNames as $name => $_) {
				$parameterNames[$name] = $query[$name];
			}

			return $route->getMethod()(...$parameterNames);
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			return new JSONMessage(['err' => 'Page not found!', 'status_code' => JSONMessage::PAGE_NOT_FOUND], 404);
		} else {
			return new HTMLMessage('Page not found!', 404);
		}
	}
}