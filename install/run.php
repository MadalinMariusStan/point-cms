<?php

use System\arr;
use System\autoloader;
use System\config;
use System\cookie;
use System\uri;

/*
 * Set your applications current timezone
 */
/** @noinspection PhpUndefinedMethodInspection */
date_default_timezone_set(Config::app('timezone', 'UTC'));

/*
 * Define the application error reporting level based on your environment
 */
switch (constant('ENV')) {
    case 'dev':
        ini_set('display_errors', true);
        error_reporting(-1);
        break;

    default:
        error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
}

/*
 * Set autoload directories to include your app models and libraries
 */
Autoloader::directory([
    APP . 'models',
    APP . 'libraries',
    PATH . 'app/libraries'
]);

/*
 * Set the current uri from get
 */
if ($route = Arr::get($_GET, 'route', '/')) {
    Uri::$current = trim($route, '/') ?: '/';
}

/*
 *   Helper functions
 */

/**
 * Retrieves all time zones
 *
 * @return array
 */
function timezones()
{
    $timezones = [];
    $utcTime = new DateTime('now', new DateTimeZone('UTC'));
    $identifiers = DateTimeZone::listIdentifiers();

    foreach ($identifiers as $timezone) {
        $targetTimeZone = new DateTimeZone($timezone);
        $offset = $targetTimeZone->getOffset($utcTime);
        $hours = intval($offset / 3600);
        $minutes = abs(intval($offset % 3600 / 60));

        // Create the label
        $label = 'GMT' . ($offset >= 0 ? '+' : '') . $hours . ($minutes ? '.' . $minutes : '');
        $label .= ' ' . $timezone;

        $timezones[] = [
            'offset' => $offset,
            'timezone_id' => $timezone,
            'label' => $label
        ];
    }

    // Sort by offset, and then by timezone_id.
    usort($timezones, function ($a, $b) {
        if ($a['offset'] === $b['offset']) {
            return strcmp($a['timezone_id'], $b['timezone_id']);
        }
        return $a['offset'] - $b['offset'];
    });

    return $timezones;
}

/**
 * Retrieves the current timezone
 *
 * @return float|int
 */
function current_timezone()
{
    return Cookie::read('blog-install-timezone', 0) * 3600;
}

/**
 * Retrieves all supported languages with their full names
 *
 * @return array list of supported languages with their names
 */
function languages()
{
    $languages = [];

    $path = PATH . 'app/language';
    $iterator = new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS);

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            $langCode = $file->getBasename();
            $translationFile = $path . '/' . $langCode . '/global.php';

            // Default to language code if translation not found
            $langName = $langCode;

            if (file_exists($translationFile)) {
                $translations = include $translationFile;
                if (isset($translations['language'])) {
                    $langName = $translations['language'];
                }
            }

            $languages[$langCode] = $langName;
        }
    }

    return $languages;
}


/**
 * Retrieves a list of preferred languages
 * TODO: Correct the typo. It makes me twitch.
 *
 * @return array
 */
function prefered_languages()
{
    $preferences = ['en-GB'];

    if ($lang = Arr::get($_SERVER, 'HTTP_ACCEPT_LANGUAGE')) {
        $pattern = '/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i';

        if (preg_match_all($pattern, $lang, $matches)) {
            $preferences = $matches[1];
        }
    }

    return array_map(function ($str) {
        return str_replace('-', '_', $str);
    }, $preferences);
}

/**
 * Checks whether the current web server is an Apache httpd
 *
 * @return bool
 */
function is_apache()
{
    return str_contains(PHP_SAPI, 'apache');
}

/**
 * Checks whether PHP is running as a CGI daemon
 *
 * @return bool
 */
function is_cgi()
{
    return str_contains(PHP_SAPI, 'cgi');
}

/**
 * Checks whether the current web server is Nginx
 *
 * @return bool
 */
function is_nginx()
{
    // Check if the server software header indicates Nginx
    if (isset($_SERVER['SERVER_SOFTWARE']) && stripos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false) {
        return true;
    }

    // Check if the HTTP server is set to Nginx
    if (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] === 'nginx') {
        return true;
    }

    // Check for specific Nginx environment variables
    if (getenv('NGINX') === 'YES' || getenv('SERVER_SOFTWARE') === 'nginx') {
        return true;
    }

    // Check if Nginx-specific variables are set
    if (isset($_SERVER['HTTP_X_NGINX_VERSION']) || isset($_SERVER['HTTP_X_REAL_IP'])) {
        return true;
    }

    return false;
}


/**
 * Checks whether mod_rewrite is enabled for Apache
 *
 * @return bool
 */
function mod_rewrite_apache()
{
    if (is_apache() && function_exists('apache_get_modules')) {
        return in_array('mod_rewrite', apache_get_modules(), true);
    }

    return false;
}

/**
 * Checks whether mod_rewrite is enabled for Nginx
 *
 * @return bool
 */
function mod_rewrite_nginx()
{
    // Nginx does not support mod_rewrite in the same way Apache does
    // You may implement custom logic here based on Nginx configuration
    // For simplicity, we'll assume mod_rewrite-like functionality is always available in Nginx
    return is_nginx();
}

/**
 * Checks whether mod_rewrite is enabled based on the server type
 *
 * @return bool
 */
function mod_rewrite()
{
    if (is_apache()) {
        return mod_rewrite_apache();
    } elseif (is_nginx()) {
        return mod_rewrite_nginx();
    }

    return false;
}


/*
 *   Pre install checks
 */
$GLOBALS['errors'] = [];

/**
 * Checks a precondition for the installation
 *
 * @param string   $message User facing HTML message
 * @param \Closure $action  action to execute for the check
 *
 * @return void
 */
function check($message, $action)
{
    if ( ! $action()) {
        $GLOBALS['errors'][] = $message;
    }
}

check(
    '<code>content</code> directory needs to be writable
	so we can upload your images and files.',
    function () {
        return is_writable(PATH . 'content');
    }
);

check(
    '<code>app/config</code> directory needs to be temporarily writable
	so we can create your application and database configuration files.',
    function () {
        return is_writable(PATH . 'app/config');
    }
);

check(
    'The blog script requires the php module <code>pdo_mysql</code> to be installed.',
    function () {
        return extension_loaded('PDO') and extension_loaded('pdo_mysql');
    }
);

check(
    'The blog script requires the php module <code>GD</code> to be installed.',
    function () {
        return extension_loaded('gd');
    }
);

// mb_strtolower() in app\helpers.php
check(
    'The blog script requires the php module <code>mbstring</code> to be installed.',
    function () {
        return extension_loaded('mbstring');
    }
);

if (count($GLOBALS['errors'])) {
    $vars['errors'] = $GLOBALS['errors'];

    echo Layout::create('halt', $vars)->render();

    exit(0);
}

/*
 * Import defined routes
 */
/** @noinspection PhpIncludeInspection */
require APP . 'routes' . EXT;
