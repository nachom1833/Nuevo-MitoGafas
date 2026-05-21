<?php

/**
 * Backward Compatibility Wrapper
 * 
 * Delegates legacy procedural endpoints requests securely directly to our 
 * new decoupled ProductController.
 */

// 1. Initialize Autoloaders & Setup Configuration Drivers
require_once dirname(__DIR__, 2) . '/app/Core/bootstrap.php';

// 2. Instantiate new MVC Controller and Dispatch
$controller = new \App\Controllers\ProductController();
$controller->index();
