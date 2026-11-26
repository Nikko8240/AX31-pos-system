<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attempt');
$routes->post('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'DashboardController::index');

    $routes->get('products', 'ProductController::index');
    $routes->get('products/new', 'ProductController::new');
    $routes->post('products', 'ProductController::create');
    $routes->get('products/(:num)/edit', 'ProductController::edit/$1');
    $routes->post('products/(:num)', 'ProductController::update/$1');
    $routes->post('products/(:num)/delete', 'ProductController::delete/$1');

    $routes->get('customers', 'CustomerController::index');
    $routes->get('customers/new', 'CustomerController::new');
    $routes->post('customers', 'CustomerController::create');
    $routes->get('customers/(:num)/edit', 'CustomerController::edit/$1');
    $routes->post('customers/(:num)', 'CustomerController::update/$1');
    $routes->post('customers/(:num)/delete', 'CustomerController::delete/$1');

    $routes->get('users', 'UserController::index');
    $routes->get('users/new', 'UserController::new');
    $routes->post('users', 'UserController::create');
    $routes->get('users/(:num)/edit', 'UserController::edit/$1');
    $routes->post('users/(:num)', 'UserController::update/$1');
    $routes->post('users/(:num)/delete', 'UserController::delete/$1');

    $routes->get('sales', 'SaleController::index');
    $routes->get('sales/new', 'SaleController::new');
    $routes->post('sales', 'SaleController::create');
});
