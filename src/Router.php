<?php namespace Wappr;

class Router {
	private $routes;

	public function __construct() {}

	public function add($url, $action) {
		$this->routes[$url] = $action;
	}

	public function dispatch() {
		foreach($this->routes as $url => $action) {
			if($url == $_SERVER['REQUEST_URI']) {
				return $action;
			}
		}
	}
}
