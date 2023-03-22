<?php 
namespace App\Routing;

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

	private function routesAnalyse(array $routes) {
		$url = new URL($_SERVER['REQUEST_URI']);

		foreach($routes as $route) {
			$parametersDiff = array_diff_key($route->getParameters(), $url->getQuery()); 

			if($route->getRoute() != $url->getPath() || !empty($parametersDiff)) {
				continue;
			}

			return call_user_func_array($route->getMethod(), $url->getQuery());
		}

		return 'Page not found!';
	}
}