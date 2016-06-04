<?php
$router->add('*', 'index');
$router->add('admin', 'admin');
$router->add('restaurants', 'restaurants', ['getCompanies', 'getCompany', 'getDeal'], true);
$router->add('weekly', 'weekly', ['getWeek', 'getDay'], true);
$router->add('bar', 'bar');
$router->add('random', 'random', ['getRandom', 'getRandomDeal'], true);
$router->add('submit', 'submit', ['doNothing', 'doNothing'], true);
$router->add('food-trucks', 'food-trucks', ['getTruckPage', 'getTruckPage'], true);
