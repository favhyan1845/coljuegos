<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->post('/create-users', 'UserApiController::create');
$routes->post('/login',         'OperadorApiController::login');
$routes->post('/all-user',      'OperadorApiController::allUser');
$routes->post('/add-user',      'OperadorApiController::addUser');
$routes->post('/edit-user',     'OperadorApiController::editUser');
$routes->post('/user',          'OperadorApiController::getUserById');
$routes->post('/report-csv',    'OperadorApiController::reportCSV');
$routes->post('/jwt-encrypt',   'OperadorApiController::JWTEncrypt');
$routes->post('/valid-token',   'OperadorApiController::validaToken');
$routes->post('/reports',       'ReporteApiController::Report');
$routes->get('/up-report',      'ReportApiController::index');

// Endpoints backend


// $routes->get('/', 'Home::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
