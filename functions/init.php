<?php
/**
 * Initialization File
 */
ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

require_once(__DIR__ . '/db.php');
require_once(__DIR__ . '/function.php');

// ใช้ Environment Variable หรือค่า default
$site_url = getenv('SITE_URL') ?: 'https://tiktokshop-production-d19a.up.railway.app/';
define('SITE_URL', $site_url);

define('ASSET_ROOT',
    'https://' . $_SERVER['HTTP_HOST'] .
    str_replace($_SERVER['DOCUMENT_ROOT'],
        '',
        str_replace('\\', '/', dirname(__DIR__) . '/assets')
    )
);
?>
