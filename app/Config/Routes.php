<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 $routes->get('/dashboard', 'KabupatenController::landingPage');


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

$routes->post('document-desa/upload/(:any)', 'DocumentDesaController::upload/$1');
$routes->get('document-desa/getData', 'DocumentDesaController::getData');
$routes->get('document-desa/document-status', 'DesaController::getDataStatus');

$routes->get('document-kecamatan/document-status', 'KecamatanController::getDataStatusKecamatan');
$routes->get('document-kabupaten/all', 'KabupatenController::getDataStatusKabupatenAll');

$routes->get('templates/detail/(:any)', 'DocumentDesaController::documentDesaDetail/$1');
$routes->POST('templates/upload_files', 'DocumentDesaController::upload_files');
$routes->POST('templates/kecamatan/upload_files', 'DocumentKecamatanController::upload_files');
$routes->POST('templates/kabupaten/upload_files', 'KabupatenController::upload_files');

$routes->get('/templates/kecamatan/detail/(:any)', 'DocumentKecamatanController::documentKecamatanDetail/$1/$2/$3');


$routes->get('document-kabupaten/document-status', 'KabupatenController::getDataStatusKabupaten');

$routes->get('getDatadesa/all', 'DesaController::getDataDesaAll');



$routes->POST('update/profil-desa', 'DesaController::updateProfil');


$routes->get('desa/detail/(:any)', 'DesaController::DesaDetail/$1');
$routes->get('kecamatan/detail/(:any)', 'KecamatanController::KecamatanDetail/$1/$2/$3');
$routes->get('kabupaten/detail/(:any)', 'KabupatenController::KabupatenDetail/$1/$2/$3');

$routes->post('templates/create_templates', 'TemplateController::storeTemplates');
$routes->get('templates/get_templates', 'TemplateController::getDocumentTemplates');
$routes->get('templates/edit/(:any)', 'TemplateController::editDocument/$1');
$routes->post('templates/update_templates', 'TemplateController::update_templates');
$routes->get('templates/delete_desa/(:num)', 'TemplateController::deleteTemplate/$1');
$routes->post('templates/deleteWithPassword', 'TemplateController::deleteWithPassword');
$routes->post('templates/reverifikasi', 'TemplateController::reverifikasi');
$routes->post('templates/reverifikasi-kabupaten', 'TemplateController::reverifikasiKabupaten');



$routes->post('templates/create_kecamatan', 'TemplateController::storeKecamatan');
$routes->get('templates/get_kecamatan', 'TemplateController::getKecamatanTemplates');
$routes->get('templates/delete_kecamatan/(:num)', 'TemplateController::deleteKecamatan/$1');
$routes->post('templates/update_desa', 'TemplateController::updateDesa/$1');
$routes->post('templates/update_kecamatan', 'TemplateController::updateKecamatan');


$routes->get('document-desa/getStatus', 'DesaController::getDataStatus');
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
