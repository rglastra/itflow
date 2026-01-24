<?php

/**
 * Mollie API Client Initialization
 * Loads vendor autoloader if available, otherwise uses PSR-4 autoloader
 */

// Define base directory
define('MOLLIE_BASE_DIR', __DIR__);

// Try to load Composer autoloader first
if (file_exists(MOLLIE_BASE_DIR . '/vendor/autoload.php')) {
    require_once MOLLIE_BASE_DIR . '/vendor/autoload.php';
} else {
    // Fallback to PSR-4 autoloader
    spl_autoload_register(function($class) {
        // Only handle Mollie namespace
        if (strpos($class, 'Mollie\\Api\\') !== 0) {
            return;
        }
        
        // Convert namespace to file path
        $relativeClass = substr($class, strlen('Mollie\\Api\\'));
        $file = MOLLIE_BASE_DIR . '/src/' . str_replace('\\', '/', $relativeClass) . '.php';
        
        // Load the file if it exists
        if (file_exists($file)) {
            require_once $file;
        }
    });
}
