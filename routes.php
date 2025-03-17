<?php
class Router {
    private $routes = [];

    public function __construct() {
        $this->routes = [
            '/' => 'dashboard.php',
            '/login' => 'login.php',
            '/logout' => 'logout.php',
            '/dashboard' => 'dashboard.php',
            // Add more routes as needed
        ];
    }

    public function route() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/CAFM-Project', '', $path);
        
        if (array_key_exists($path, $this->routes)) {
            require_once $this->routes[$path];
        } else {
            // 404 handling
            header("HTTP/1.0 404 Not Found");
            require_once '404.php';
        }
    }
} 