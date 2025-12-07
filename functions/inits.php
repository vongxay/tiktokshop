<?php
/**
 * Created by PhpStorm.
 * User: ceeb
 * Date: 8/4/16
 * Time: 2:16 PM
 */
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

require_once('db.php');
require_once('function.php');

// ใช้ environment variable หรือ detect จาก request
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('SITE_URL', getenv('SITE_URL') ?: $protocol . $host . '/');
define('ASSET_ROOT',
    'http://' . $_SERVER['HTTP_HOST'] .
    str_replace($_SERVER['DOCUMENT_ROOT'],
        '',
        str_replace('\\', '/', dirname(__DIR__) . '/assets')
    )

);

//echo SITE_URL;