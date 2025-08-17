<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::loginpage');
$routes->get('/admin', 'SuperAdminController::login');
$routes->get('/auth/logoutadmin', 'SuperAdminController::logout');
$routes->get('/sa', 'AdminDashboardController::adminsa');
$routes->get('/admindashboard', 'AdminDashboardController::admindashboard');

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


$routes->post('templates/create_desa', 'TemplateController::storeDesa');
$routes->post('templates/create_kecamatan', 'TemplateController::storeKecamatan');
$routes->get('/templates/get_desa', 'TemplateController::getDesaTemplates');
$routes->get('templates/delete_desa/(:num)', 'TemplateController::deleteDesa/$1');
$routes->get('templates/get_kecamatan', 'TemplateController::getKecamatanTemplates');
$routes->get('templates/delete_kecamatan/(:num)', 'TemplateController::deleteKecamatan/$1');
$routes->post('templates/update_desa', 'TemplateController::updateDesa/$1');
$routes->post('templates/update_kecamatan', 'TemplateController::updateKecamatan');


$routes->get('document-desa/getData', 'DesaController::getDataDesa');
$routes->get('document-kecamatan/getData', 'KecamatanController::getDataKecamatan');





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



// $routes->group('load-content', function($routes) {
//     $routes->get('templates/(:segment)', 'TemplateController::loadTemplateContent/$1');
//     $routes->get('templates/(:segment)/form', 'TemplateController::loadTemplateForm/$1');
//     $routes->get('templates/(:segment)/form/(:num)', 'TemplateController::loadTemplateForm/$1/$2');
// });

// $routes->group('templates', function($routes) {
//     // Desa
//     $routes->get('desa', 'TemplateController::indexDesa');
//     $routes->get('desa/create_desa', 'TemplateController::storeDesa');
//     $routes->post('desa/store', 'TemplateController::storeDesa');
//     $routes->get('desa/edit/(:num)', 'TemplateController::editDesa/$1');
//     $routes->post('desa/update/(:num)', 'TemplateController::updateDesa/$1');
//     $routes->get('desa/delete/(:num)', 'TemplateController::deleteDesa/$1');
    
//     // Kecamatan
//     $routes->get('kecamatan', 'TemplateController::indexKecamatan');
//     $routes->get('kecamatan/create', 'TemplateController::createKecamatan');
//     $routes->post('kecamatan/store', 'TemplateController::storeKecamatan');
//     // Tambahkan edit/update/delete untuk kecamatan jika diperlukan
// });