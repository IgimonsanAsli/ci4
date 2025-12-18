<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Parkir Routes
$routes->get('/', 'Parkir::index');
$routes->get('parkir', 'Parkir::index');
$routes->post('parkir/generate', 'Parkir::generate');
$routes->get('parkir/detail/(:segment)', 'Parkir::detail/$1');
$routes->get('parkir/pdf/(:segment)', 'Parkir::generatePDF/$1');
$routes->post('parkir/checkout', 'Parkir::checkout');
$routes->get('parkir/dashboard-data', 'Parkir::getDashboardData');

