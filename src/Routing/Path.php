<?php 

namespace App\Routing;

class Path {
	private array $entities = [];
	private array $parameters = [];

	public function __construct(string $path = null) {
		if(isset($path)) {
			$this->entities = explode('/', $path);
		}

		for($i = 0; $i < count($this->entities); $i++) {
			$entity = $this->entities[$i];

			if(!empty($entity) && $entity[0] == '{' && $entity[strlen($entity)-1] == '}') {
				$this->parameters[substr($entity, 1, strlen($entity)-2)] = $i;
			}
		}
	}

	public function getParameters() {
		return $this->parameters;
	}

	public function getEntities() {
		return $this->entities;
	}

	public function __toString() {
		$pathStr = "";

		if(count($this->entities) == 2) {
			return '/' . $this->entities[1];
		}

		foreach($this->entities as $entity) {
			$pathStr .= (end($this->entities) != $entity) ? $entity . '/': $entity;
		}

		return $pathStr;
	}
}