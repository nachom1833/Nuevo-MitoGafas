<?php

/**
 * Backward Compatibility Wrapper
 * 
 * Delegates legacy single-product details requests securely directly to our 
 * new decoupled ProductController.
 */

// 1. Initialize Autoloaders & Setup Configuration Drivers
require_once dirname(__DIR__, 2) . '/app/Core/bootstrap.php';

// 2. Extract dynamic ID parameter from GET requests
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

// 3. Instantiate new MVC Controller and Dispatch show action
$controller = new \App\Controllers\ProductController();
$controller->show($id);
