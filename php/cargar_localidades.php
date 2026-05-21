<?php

/**
 * Backward Compatibility Wrapper
 * 
 * Delegates legacy localities lists requests securely directly to our 
 * new decoupled StoreController.
 */

// 1. Initialize Autoloaders & Setup Configuration Drivers
require_once dirname(__DIR__, 2) . '/app/Core/bootstrap.php';

// 2. Instantiate new MVC Controller and Dispatch localities action
$controller = new \App\Controllers\StoreController();
$controller->localities();
