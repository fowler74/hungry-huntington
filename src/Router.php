<?php namespace Wappr;

class Router {
	private $routes;

	public function __construct() {}

	public function add($url, $action, $method = '', $hasChildren = false) {
		$this->routes[$url]['url']         = $url;
		$this->routes[$url]['action']      = $action;
		$this->routes[$url]['method']      = $method;
		$this->routes[$url]['hasChildren'] = $hasChildren;
	}

	public function dispatch() {
		foreach($this->routes as $route) {
			if($route['url'] == $_SERVER['REQUEST_URI']
				OR $route['url'] == rtrim($_SERVER['REQUEST_URI'], '/')) {
				return $this->routes[$route['url']];
			}
		}
		// If nothing matches - return the homepage
		$default = ['action' => 'index'];
		return $default;
	}
}
