<?php
/**
 * Initialize application
 */

// Load environment variables from .env file
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            putenv(trim($line));
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Include config and functions
require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/function.php');

// Define site URL
if (!defined('SITE_URL')) {
    define('SITE_URL', getenv('SITE_URL') ?: 'https://www.tkshop.wuaze.com/');
}
