<?php

use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Controllers\CommentsController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/user', [UserController::class, 'index']);

$routes->group('api/users', function ($routes) {
    $routes->post('register', [UserController::class, 'register']);
    $routes->post('login', [UserController::class, 'login']);
});

$routes->group('api/posts',['filter' => 'authMiddleware'], function ($routes) {
    $routes->get('/', [PostController::class, 'index']);
    $routes->get('(:num)', [PostController::class, 'read']);
    $routes->post('/', [PostController::class, 'create']);
    $routes->post('(:num)', [PostController::class, 'update']);
    $routes->delete('(:num)', [PostController::class, 'delete']);
});


$routes->group('api/comments', ['filter' => 'authMiddleware'], function ($routes) {
    $routes->get('(:num)', [CommentsController::class, 'index']);
    $routes->post('(:num)', [CommentsController::class, 'create']);
    // $routes->put('update-comment/(:num)', [CommentsController::class, 'update']);
    $routes->delete('delete-comment/(:num)', [CommentsController::class, 'delete']);
});
