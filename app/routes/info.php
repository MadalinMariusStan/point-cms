<?php

use System\config;
use System\route;
use System\view;
use System\session;
use System\uri;

Route::collection(['before' => 'auth,csrf,install_exists'], function () {

    Route::get('admin/extend/tools/info', function () {
        // Generate CSRF token for security
        $vars['token'] = Csrf::token();

        // Gather system information
        $opcacheStatus = function_exists('opcache_get_status') ? opcache_get_status() : [];
        $gdInfo = function_exists('gd_info') ? gd_info() : [];

        $vars['system_info'] = [
            'PHP' => [
                'Version' => PHP_VERSION,
                'Operating System' => php_uname(),
                'Server API' => PHP_SAPI,
                'Loaded php.ini' => php_ini_loaded_file(),
                'Loaded Extensions' => implode(', ', get_loaded_extensions()),
                'Zend Engine Version' => zend_version(),
            ],
            'HTTP Request Headers' => $_SERVER,
            'HTTP Response Headers' => headers_list(),
            'Server' => [
                'IP Address' => $_SERVER['SERVER_ADDR'] ?? 'N/A',
                'Port' => $_SERVER['SERVER_PORT'] ?? 'N/A',
                'Name' => $_SERVER['SERVER_NAME'] ?? 'N/A',
                'Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
                'Apache Modules' => implode(', ', function_exists('apache_get_modules') ? apache_get_modules() : []),
                'Protocol' => $_SERVER['SERVER_PROTOCOL'] ?? 'N/A',
                'HTTPS' => isset($_SERVER['HTTPS']) ? 'on' : 'off',
                'Request Time' => gmdate('D, d M Y H:i:s T', $_SERVER['REQUEST_TIME'] ?? time()),
            ],
            'Client' => [
                'IP Address' => $_SERVER['REMOTE_ADDR'] ?? 'N/A',
                'Port' => $_SERVER['REMOTE_PORT'] ?? 'N/A',
            ],
            'Session' => [
                'Session Cookie Lifetime' => ini_get('session.cookie_lifetime'),
                'Session Strict Mode' => ini_get('session.use_strict_mode') ? 'true' : 'false',
            ],
            'Uploads' => [
                'File Uploads' => ini_get('file_uploads') ? 'true' : 'false',
                'POST Max Size' => ini_get('post_max_size'),
                'Maximum File Size' => ini_get('upload_max_filesize'),
                'Maximum File Uploads' => ini_get('max_file_uploads'),
            ],
            'Script' => [
                'Max Execution Time' => ini_get('max_execution_time'),
                'Max Input Time' => ini_get('max_input_time'),
                'Memory Limit' => ini_get('memory_limit'),
                'Default MIME-Type' => ini_get('default_mimetype'),
                'Default Charset' => ini_get('default_charset'),
            ],
            'Streams' => [
                'Stream Wrappers' => implode(', ', stream_get_wrappers()),
                'Allow URL Fopen' => ini_get('allow_url_fopen') ? 'true' : 'false',
            ],
            'OPcache' => [
                'Enabled' => isset($opcacheStatus['opcache_enabled']) ? 'true' : 'false',
                'Cached Scripts' => $opcacheStatus['opcache_statistics']['num_cached_scripts'] ?? 0,
                'Cache Hits' => $opcacheStatus['opcache_statistics']['hits'] ?? 0,
                'Cache Misses' => $opcacheStatus['opcache_statistics']['misses'] ?? 0,
                'Used Memory' => $opcacheStatus['memory_usage']['used_memory'] ?? 0,
                'Free Memory' => $opcacheStatus['memory_usage']['free_memory'] ?? 0,
                'Wasted Memory' => $opcacheStatus['memory_usage']['wasted_memory'] ?? 0,
                'Current Wasted Percentage' => $opcacheStatus['memory_usage']['current_wasted_percentage'] ?? 0,
                'Max Wasted Percentage' => ini_get('opcache.max_wasted_percentage'),
            ],
            'GD' => [
                'Version' => $gdInfo['GD Version'] ?? '',
                'JPEG Support' => !empty($gdInfo['JPEG Support']) ? 'true' : 'false',
                'PNG Support' => !empty($gdInfo['PNG Support']) ? 'true' : 'false',
                'GIF Read Support' => !empty($gdInfo['GIF Read Support']) ? 'true' : 'false',
                'GIF Create Support' => !empty($gdInfo['GIF Create Support']) ? 'true' : 'false',
                'WebP Support' => !empty($gdInfo['WebP Support']) ? 'true' : 'false',
            ],
            'System' => [
                'Directory Separator' => DIRECTORY_SEPARATOR,
                'EOL Symbol' => addcslashes(PHP_EOL, "\r\n"),
                'Max Path Length' => 4096, // Typical max path length
                'File Creation Mask' => sprintf('0%03o', umask()),
            ],
        ];

        // Pass the gathered system info to the view
        return View::create('extend/tools/info', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

});


