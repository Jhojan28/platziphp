<?php

ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'cursophp',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();

$map->get('index', '/platziphp/', [
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction'
]);
$map->get('addJobs', '/platziphp/jobs/add', [
    'controller' => 'App\Controllers\JobController',
    'action' => 'getAddJobAction'
]);
$map->get('addUsers', '/platziphp/users/add', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'getAddUserAction'
]);
$map->post('saveJobs', '/platziphp/jobs/add', [
    'controller' => 'App\Controllers\JobController',
    'action' => 'getAddJobAction'
]);
$map->post('saveUsers', '/platziphp/users/add', [
    'controller' => 'App\Controllers\UserController',
    'action' => 'getAddUserAction'
]);
$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route) {
    echo 'No route found';
} else {
    $handlerData = $route->handler;
    $actionName = $handlerData['action'];
    $controllerName = $handlerData['controller'];
    $controller = new $controllerName;
    $response  = $controller->$actionName($request);

    echo $response->getBody();
}