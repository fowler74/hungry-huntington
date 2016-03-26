<?php
date_default_timezone_set('America/New_York');
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(dirname(__FILE__)));
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(ROOT . DS . 'src' . DS . 'HungryHuntington.php');
require_once(ROOT . DS . 'src' . DS . 'Router.php');
require_once(ROOT . DS . 'src' . DS . 'Controller.php');
require_once(ROOT . DS . 'vendor' . DS . 'autoload.php');

$hungry     = new Wappr\HungryHuntington;
$router     = new Wappr\Router;
$controller = new Wappr\Controller($_POST);
$router->add('/', 'index');
$router->add('/admin/', 'admin');
$page = $router->dispatch();
$controller->run();

$loader = new Twig_Loader_Filesystem(ROOT . DS . 'templates');
$twig = new Twig_Environment($loader);
if(file_exists(ROOT . DS . 'templates' . DS . $page '.twig')) {
    $template = $twig->loadTemplate($page . '.twig');
} else {
    header("HTTP/1.0 404 Not Found");
    echo 'Page not found';
    die();
}
echo $template->render(array('deals' => $hungry, 'controller' => $controller));
