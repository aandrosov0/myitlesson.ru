<?php 
namespace App\Routing;

use App\Rendering\HTMLMessage;
use App\Rendering\JSONMessage;

class Router {
	public function __construct(private array $getRoutes = [], private array $postRoutes = []){}

	public function route() {
		switch($_SERVER['REQUEST_METHOD']) {
			case 'GET':
				return $this->routesAnalyse($this->getRoutes);
			case 'POST':
				return $this->routesAnalyse($this->postRoutes);
		}

		return 'Page not found';
	}

	public function GET(Route $route) {
		$this->getRoutes[] = $route;
	}

	public function POST(Route $route) {
		$this->postRoutes[] = $route;
	}

	private function routesAnalyse(array $routes) {
		$url = new URL($_SERVER['REQUEST_URI']);

		foreach($routes as $route) {
			$parametersDiff = array_keys($url->getQuery()) === array_keys($route->getParameters()); 

			if($route->getRoute() != $url->getPath() || !$parametersDiff) {
				continue;
			} 

			foreach($url->getQuery() as $key => $value) {
				if(trim($value) == "" && $_SERVER['REQUEST_METHOD'] == 'POST') {
					return new JSONMessage(['err' => "Entry '$key' is empty!", 'status_code' => JSONMessage::QUERY_EMPTY], 404);
				} 
				
				if(trim($value) == "" && $_SERVER['REQUEST_METHOD'] == 'GET') {
					return new HTMLMessage("Entry '$key' is empty!");
				}
			}

			return call_user_func_array($route->getMethod(), array_values($url->getQuery()));
		}

		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			return new JSONMessage(['err' => 'Page not found!', 'status_code' => JSONMessage::PAGE_NOT_FOUND], 404);
		} else {
			return new HTMLMessage('Page not found!', 404);
		}
	}
}