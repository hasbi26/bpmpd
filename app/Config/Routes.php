<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::loginpage');
$routes->get('/admin', 'SuperAdminController::login');
$routes->get('/auth/logoutadmin', 'SuperAdminController::logout');
$routes->get('/admindashboard', 'AdminDashboardController::adminsa');

$routes->post('/email/update', 'UserController::updateEmail');
$routes->post('/user/update-password', 'UserController::updatePassword');


$routes->match(['GET', 'POST'], '/login-admin', 'SuperAdminController::loginadmin');
$routes->post('/import/proses', 'SuperAdminController::proses');
$routes->post('/import/desa', 'SuperAdminController::desa');
$routes->post('/import/admin', 'SuperAdminController::prosesadmin');
$routes->post('/import/kecamatan', 'SuperAdminController::kecamatan');
$routes->get('(:segment)', 'Login::index/$1', ['as' => 'login.role']);
$routes->match(['GET', 'POST'], 'auth/login', 'AuthController::login');
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('auth/forgot', 'AuthController::forgot');
$routes->get('load-content/(:any)', 'ContentController::loadContent/$1');
$routes->match(['GET','POST'], 'forgot-password', 'AuthController::forgotPassword');

$routes->get('reset-password/(:segment)', 'AuthController::resetPassword/$1');
$routes->post('process-reset-password', 'AuthController::processResetPassword');








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

