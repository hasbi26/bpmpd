<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('(:segment)', 'Login::index/$1', ['as' => 'login.role']);
$routes->match(['get', 'post'], 'auth/login', 'AuthController::login');
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('load-content', 'ContentController::loadContent');




$routes->group('desa', ['filter' => 'auth:desa'], function($routes) {
    $routes->get('dashboard', 'DesaController::dashboard');
    // route lainnya untuk desa
});

$routes->group('kecamatan', ['filter' => 'auth:kecamatan'], function($routes) {
    $routes->get('dashboard', 'KecamatanController::dashboard');
    // route lainnya untuk kecamatan
});

$routes->group('kabupaten', ['filter' => 'auth:kabupaten'], function($routes) {
    $routes->get('dashboard', 'KabupatenController::dashboard');
    // route lainnya untuk kabupaten
});

