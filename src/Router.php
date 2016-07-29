<?php namespace Wappr;

class Router {
	private $routes;
	private $request_uri;
	private $segments;
	private $numSegments;

	public function __construct() {
		// trim beginning and end /'s
		$this->request_uri = trim($_SERVER['REQUEST_URI'], '/');
		// Get how many segments there are
		$this->numSegments = count(explode('/', $this->request_uri)) - 1;
		// Explode the segments into an array
		$this->segments = explode('/', $this->request_uri);
	}

	public function add($url, $action, $method = '', $hasChildren = false) {
		$this->routes[$url]['url']         = $url;
		$this->routes[$url]['action']      = $action;
		$this->routes[$url]['method']      = $method;
		$this->routes[$url]['hasChildren'] = $hasChildren;
	}

	public function dispatch() {
		$_SERVER['REQUEST_URI'] = str_replace("?json=true", "", $_SERVER['REQUEST_URI']);
		foreach($this->routes as $route) {
			if(strpos($_SERVER['REQUEST_URI'], $route['url']) !== false) {
				if($route['hasChildren']) {
					$this->routes[$route['url']]['callMethod'] = $this->routes[$route['url']]['method'][$this->numSegments];
					$this->routes[$route['url']]['urlTitle'] = $this->segments;
				}
				return $this->routes[$route['url']];
			}
		}
		// If nothing matches - return the homepage
		$default = ['action' => 'index'];
		return $default;
	}
}
