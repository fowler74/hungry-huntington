<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/New_York');
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(dirname(__FILE__)));
session_start();

require_once(ROOT . DS . 'src' . DS . 'HungryHuntington.php');
require_once(ROOT . DS . 'src' . DS . 'Router.php');
require_once(ROOT . DS . 'src' . DS . 'Controller.php');
require_once(ROOT . DS . 'vendor' . DS . 'autoload.php');

$hungry     = new Wappr\HungryHuntington;
$router     = new Wappr\Router;
$controller = new Wappr\Controller($_POST);
require_once(ROOT . DS . 'routes.php');
$page = $router->dispatch();
// Pass the page info to the HungryHuntington class
$hungry->page = $page;
$controller->run();
if(isset($_GET['json'])) {
    $j = 'json' . DS;
} else {
    $j = '';
}

$loader = new Twig_Loader_Filesystem(ROOT . DS . 'templates');
$twig = new Twig_Environment($loader, array('debug' => true));
$twig->addExtension(new Twig_Extension_Debug());
if(file_exists(ROOT . DS . 'templates' . DS . $j . $page['action'] . '.twig')) {
    $template = $twig->loadTemplate($page['action'] . '.twig');
} else {
    header("HTTP/1.0 404 Not Found");
    echo 'Page not found';
    die();
}
echo $template->render(array('deals' => $hungry, 'controller' => $controller));
