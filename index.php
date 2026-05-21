<?php

/**
 * Mito Gafas — Front Controller
 * 
 * Intercepts all incoming dynamic HTTP traffic, loads system bootstrap drivers,
 * registers RESTful endpoints, and dispatches requests.
 */

// 1. Load System Autoloader & Environmental Setup
require_once dirname(__DIR__) . '/app/Core/bootstrap.php';

use App\Core\Router;

// 2. Initialize Routing Engine
$router = new Router();

// 3. Register Core clean RESTful Routes
$router->get('api/products', 'ProductController@index');
$router->get('api/products/([0-9]+)', 'ProductController@show');
$router->get('api/stores/localities', 'StoreController@localities');
$router->get('api/stores', 'StoreController@index');

// 4. Register Secure Authentication POST Routes
$router->post('api/auth/login', 'AuthController@login');
$router->post('api/auth/logout', 'AuthController@logout');

// 5. Register Secure Administrative CRUD POST Routes (Protected by AuthMiddleware)
$router->post('api/admin/products', 'Admin\ProductCrudController@store', [\App\Middleware\AuthMiddleware::class]);
$router->post('api/admin/products/update/([0-9]+)', 'Admin\ProductCrudController@update', [\App\Middleware\AuthMiddleware::class]);
$router->post('api/admin/products/delete/([0-9]+)', 'Admin\ProductCrudController@delete', [\App\Middleware\AuthMiddleware::class]);

// 6. Dispatch the active HTTP Request URI and Method
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
