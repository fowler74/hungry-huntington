<?php
date_default_timezone_set('America/New_York');
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(dirname(__FILE__)));

require_once(ROOT . DS . 'src' . DS . 'HungryHuntington.php');
require_once(ROOT . DS . 'vendor' . DS . 'autoload.php');

$hungry = new Wappr\HungryHuntington;

$loader = new Twig_Loader_Filesystem(ROOT . DS . 'templates');
$twig = new Twig_Environment($loader);
$template = $twig->loadTemplate('index.twig');
echo $template->render(array('deals' => $hungry));
