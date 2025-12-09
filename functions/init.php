<?php
/**
 * Initialize application
 */
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

// โหลดค่าจากไฟล์ .env
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

require_once(__DIR__ . '/config.php');
require_once(__DIR__ . '/function.php');

// Auto-detect SITE_URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('SITE_URL', $protocol . '://' . $host . '/');

define('ASSET_ROOT',
    $protocol . '://' . $host .
    str_replace($_SERVER['DOCUMENT_ROOT'] ?? '',
        '',
        str_replace('\\', '/', dirname(__DIR__) . '/assets')
    )
);
?>
