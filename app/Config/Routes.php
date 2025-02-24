<?php

use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\CommentsController;
use App\Controllers\FlightSearch;
use App\Controllers\FlightSearchController;
use App\Controllers\FlightSelectController;
use App\Controllers\Home;

use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */
$routes->get("api/flight-search", [FlightSearchController::class, 'index']);
$routes->get("api/flight-select", [FlightSelectController::class, 'index']);



$routes->get('/',   [Home::class, 'index']);

$routes->get('/home', [Home::class, 'home']);
$routes->get('/dd', [Home::class, 'getFlights']);

$routes->group('users', function ($routes) {
    $routes->get('register', [UserController::class, 'registerForm']);
    $routes->get('login', [UserController::class, 'loginForm']);
});

$routes->group('api/users', function ($routes) {
    $routes->post('register', [UserController::class, 'register']);
    $routes->post('login', [UserController::class, 'login']);
});

$routes->group('api/posts', ['filter' => 'authMiddleware'], function ($routes) {
    $routes->get('/', [PostController::class, 'index']);
    $routes->get('(:num)', [PostController::class, 'read']);
    $routes->post('/', [PostController::class, 'create']);
    $routes->post('(:num)', [PostController::class, 'update']);
    $routes->delete('(:num)', [PostController::class, 'delete']);
});


$routes->group('api/comments', ['filter' => 'authMiddleware'], function ($routes) {
    $routes->get('(:num)', [CommentsController::class, 'index']);
    $routes->post('(:num)', [CommentsController::class, 'create']);
    $routes->post('update-comment/(:num)', [CommentsController::class, 'update']);
    $routes->delete('delete-comment/(:num)', [CommentsController::class, 'delete']);
});
