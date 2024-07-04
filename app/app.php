<?php defined('App') or die('PointCMS');

/**
 *
 * Simple, fast, super extensible
 *
 * Fork of BoidCMS
 * @link https://boidcms.github.io
 *
 * @package PointCMS
 * @author Madalin-Marius Stan (Pixel)
 * @link https://pixel.com.ro
 * @version 0.0.2
 * @licence MIT
 */
#[AllowDynamicProperties]
class App
{
    /**
     * Current working directory
     * @var string $root
     */
    public $root;

    /**
     * Current page
     * @var string $page
     */
    public $page;

    /**
     * Array list of uploaded files
     * @var array $medias
     */
    public $medias;

    /**
     * Array list of all themes
     * @var array $themes
     */
    public $themes;

    /**
     * Array list of all plugins
     * @var array $plugins
     */
    public $plugins;

    /**
     * Current installed version
     * @var string $version
     */
    public $version;

    /**
     * Admin login status
     * @var bool $logged_in
     */
    public $logged_in;

    /**
     * Array container of actions
     * @var array $actions
     */
    protected $actions;

    /**
     * Decoded version of database
     * @var array $database
     */
    protected $database;


    protected $pluginDetails = [];


    protected $themeDetails = [];


    protected $language = 'en'; // Default language


    protected $translations = [];

    // Existing properties...
    protected $githubRepo = 'MadalinMariusStan/point-cms';
    protected $backupDir = 'backups';


    private function getLatestReleaseFromGitHub()
    {
        $url = "https://api.github.com/repos/{$this->githubRepo}/releases/latest";
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP'
                ]
            ]
        ];
        $context = stream_context_create($opts);
        $json = file_get_contents($url, false, $context);
        return json_decode($json, true);
    }

    public function getLatestVersion(): string
    {
        $release = $this->getLatestReleaseFromGitHub();
        return $release['tag_name'] ?? $this->version;
    }

    public function downloadUpgrade($version): bool
    {
        $release = $this->getLatestReleaseFromGitHub();
        $downloadUrl = $release['zipball_url'];
        $destination = $this->root('upgrade.zip');

        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP'
                ]
            ]
        ];
        $context = stream_context_create($opts);
        if (file_put_contents($destination, file_get_contents($downloadUrl, false, $context))) {
            return true;
        }
        return false;
    }

    public function applyUpgrade(): bool
    {
        $zip = new ZipArchive();
        if ($zip->open($this->root('upgrade.zip')) === TRUE) {
            $zip->extractTo($this->root);
            $zip->close();
            unlink($this->root('upgrade.zip'));
            return true;
        }
        return false;
    }

    public function upgradeCMS(): bool
    {
        if ($this->isUpgradeAvailable()) {
            if ($this->createBackup()) {
                $latestVersion = $this->getLatestVersion();
                if ($this->downloadUpgrade($latestVersion)) {
                    if ($this->applyUpgrade()) {
                        $this->version = $latestVersion;
                        return $this->save();
                    }
                }
            }
        }
        return false;
    }

    public function isUpgradeAvailable(): bool
    {
        return version_compare($this->getLatestVersion(), $this->getCurrentVersion(), '>');
    }

    public function getCurrentVersion(): string
    {
        return $this->version;
    }

    /**
     * Constructor
     * @param string $root
     */
    public function __construct(string $root)
    {
        $this->version = '0.0.2';

        $this->root = $root;
        if (!is_file($this->root('data/database.json'))) {
            $config = require $this->root('config.php');
            (is_dir($this->root('data')) ?: mkdir($this->root('data')));
            (is_dir($this->root('media')) ?: mkdir($this->root('media')));
            (is_dir($this->root('plugins')) ?: mkdir($this->root('plugins')));
            $json = json_encode($config, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
            file_put_contents($this->root('data/database.json'), $json, LOCK_EX);
        }
        $this->actions = array();
        $this->logged_in = (isset($_SESSION['logged_in'], $_SESSION['root']) && $this->root === $_SESSION['root']);
        $this->database = json_decode(file_get_contents($this->root('data/database.json')), true);
        $this->plugins = array_map('basename', glob($this->root('plugins/*'), GLOB_ONLYDIR));
        $this->themes = array_map('basename', glob($this->root('themes/*'), GLOB_ONLYDIR));
        $this->medias = array_map('basename', array_filter(glob($this->root('media/*')), 'is_file'));
        $this->page = $this->esc($_GET['p'] ?? '');
        $this->setLanguage($this->config['site']['lang'] ?? 'en');

        // Initialize pages if not set
        if (!isset($this->database['pages'])) {
            $this->database['pages'] = array();
        }
    }

    private function loadPluginDetails()
    {
        foreach ($this->plugins as $plugin) {
            $jsonPath = $this->root("plugins/$plugin/plugin.json");
            if (file_exists($jsonPath)) {
                $this->pluginDetails[$plugin] = json_decode(file_get_contents($jsonPath), true);
            }
        }
    }


    /**
     * Load details for all themes from their respective theme.json files.
     */
    private function loadThemeDetails()
    {
        $themes = array_map('basename', glob($this->root('themes/*'), GLOB_ONLYDIR));
        foreach ($themes as $theme) {
            $jsonPath = $this->root("themes/$theme/theme.json");
            if (file_exists($jsonPath)) {
                $this->themeDetails[$theme] = json_decode(file_get_contents($jsonPath), true);
            }
        }
    }

    /**
     * Alias of get_filter() method
     * @param mixed $value
     * @param string $action
     * @param mixed ...$args
     * @return mixed
     */
    public function _(mixed $value, string $action = 'default', mixed ...$args): mixed
    {
        return $this->get_filter($value, $action, ...$args);
    }

    /**
     * Dynamic indexed array creation
     * @param string $action
     * @param array $custom
     * @param string $del
     * @return array
     */
    public function _l(string $action, array $custom = array(), string $del = ','): array
    {
        $option = $this->get_action($action);
        $option = ($option ?? '');
        $option = explode($del, $option);
        $option = array_map('trim', $option);
        $option = array_filter($option);
        $option = array_unique($option);
        return array_merge($option, $custom);
    }

    /**
     * Log a debug message
     * @param string $message
     * @param string $type
     * @return bool
     */
    public function log(string $message, string $type = 'debug'): bool
    {
        $type = addslashes($type);
        $message = addslashes($message);
        $page = addslashes($this->page);
        $this->get_action('log', $message, $type);
        $msg = sprintf('[%s] [%d "%s"] : [%s] - %s', date('c'), http_response_code(), $page, strtoupper($type), $message . PHP_EOL);
        return error_log($msg, 3, $this->root('data/debug.log'));
    }

    /**
     * Set pair of key value to database
     * @param mixed $value
     * @param string $index
     * @return bool
     */
    public function set(mixed $value, string $index): bool
    {
        $value = (in_array($index, ['lang', 'sitename', 'title', 'keywords', 'descr']) ? htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false) : $value);
        $this->database['site'][$index] = $value;
        return $this->save();
    }

    /**
     * Delete a key from database
     * @param string $index
     * @return bool
     */
    public function unset(string $index): bool
    {
        unset($this->database['site'][$index]);
        return $this->save();
    }

    /**
     * Get value of key from database
     * @param string $index
     * @return mixed
     */
    public function get(string $index): mixed
    {
        return ($this->data('site')[$index] ?? null);
    }

    /**
     * Get full site url
     * @param string $location
     * @return string
     */
    public function url(string $location = ''): string
    {
        return $this->get_filter($this->get('url') . $location, 'url', $location);
    }

    /**
     * Absolute or relative admin url
     * @param string $location
     * @param bool $abs
     * @return string
     */
    public function admin_url(string $location = '', bool $abs = false): string
    {
        $location = ($this->get('admin') . $location);
        return ($abs ? $this->url($location) : $location);
    }

    /**
     * Get pathname from root
     * @param string $location
     * @return string
     */
    public function root(string $location): string
    {
        return ($this->root . '/' . $location);
    }

    /**
     * Generate URL for admin assets.
     * @param string $assetPath Relative path to the asset inside the admin assets folder.
     * @return string The full URL to the asset.
     */
    public function assetUrl(string $assetToInclude): string
    {
        return $this->url('app/admin/assets/' . $assetToInclude);
    }

    /**
     * Generate URL for admin assets.
     * @param string $assetPath Relative path to the asset inside the admin assets folder.
     * @return string The full URL to the asset.
     */
    public
    function asset(string $assetToInclude): string
    {
        return $this->url('assets/' . $assetToInclude);
    }

    /**
     * Get pathname from current theme directory
     * @param string $location
     * @param string $system
     * @return string
     */
    public
    function theme(string $location, bool $system = true): string
    {
        $location = ('themes/' . $this->get('theme') . '/' . $location);
        return ($system ? $this->root($location) : $this->url($location));
    }

    /**
     * Save database changes
     * @param ?array $data
     * @return bool
     */
    public
    function save(?array $data = null): bool
    {
        $data ??= $this->data();
        $json = json_encode($data, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
        $whole = isset($data['site'], $data['pages'], $data['installed']);
        if (empty($data) || !$whole || json_last_error() !== JSON_ERROR_NONE) {
            $this->log('An error occurred while trying to save database.', 'error');
            return false;
        }
        $this->get_action('save');
        $database = $this->root('data/database.json');
        return ( bool )file_put_contents($database, $json, LOCK_EX);
    }

    /**
     * Readonly version of database
     * @param ?string $index
     * @return array
     */
    public
    function data(?string $index = null): array
    {
        $data = $this->database;
        if (null !== $index) {
            return ($data[$index] ?? array());
        }
        return $data;
    }

    /**
     * CSRF token
     * @return string
     */
    public
    function token(): string
    {
        return ($_SESSION['token'] ??= bin2hex(random_bytes(32)));
    }

    /**
     * Set a callback function to action
     * @param string | array $action
     * @param callable $callback
     * @param int $priority
     * @return void
     */
    public function set_action(string|array $action, callable $callback, int $priority = 10): void
    {
        if (is_array($action)) {
            foreach ($action as $act) {
                $this->actions[$act][$priority][] = $callback;
                ksort($this->actions[$act]);
            }
            return;
        }
        $this->actions[$action][$priority][] = $callback;
        ksort($this->actions[$action]);
    }

    /**
     * Unset a given action
     * @param string $action
     * @return void
     */
    public function unset_action(string $action): void
    {
        unset($this->actions[$action]);
    }

    /**
     * Trigger an event
     * @param string $action
     * @param mixed ...$args
     * @return mixed
     */
    public function get_action(string $action, mixed ...$args): mixed
    {
        $result = null;
        if (isset($this->actions[$action])) {
            foreach ($this->actions[$action] as $priorities) {
                foreach ($priorities as $callback) {
                    $result .= $callback(...$args);
                }
            }
        }
        return $result;
    }

    /**
     * Apply filter
     * @param mixed $value
     * @param string $action
     * @param mixed ...$args
     * @return mixed
     */
    public function get_filter(mixed $value, string $action, mixed ...$args): mixed
    {
        if (isset($this->actions[$action])) {
            $actions = $this->actions[$action];
            $priorities = array_keys($actions);
            do {
                $offset = current($priorities);
                $priority = $actions[$offset];
                foreach ($priority as $callback) {
                    $value = $callback($value, ...$args);
                }
            } while (next($priorities) !== false);
        }
        return $value;
    }

    /**
     * Load plugins and theme functions
     * @return void
     */
    public
    function load_actions(): void
    {
        foreach ($this->data('installed') as $plugin) {
            $plugin = $this->root('plugins/' . $plugin . '/plugin.php');
            if (is_file($plugin)) include_once($plugin);
        }
        $functions = $this->theme('functions.php');
        if (is_file($functions)) include_once($functions);
    }

    /**
     * Set an alert for admin
     * @param string $message
     * @param string $type
     * @return void
     */
    /**
     * Set an alert for admin
     * @param string $message
     * @param string $type
     * @return void
     */
    public
    function alert(string $message, string $type = 'info'): void
    {
        $alert = array('message' => $message, 'type' => $type);
        if (isset($_SESSION['alerts'])) {
            if (in_array($alert, $_SESSION['alerts'])) {
                return;
            }
        }
        $_SESSION['alerts'][] = $alert;
    }


    /**
     * Get all admin alerts
     * @return void
     */
    /**
     * Get all admin alerts
     * @return void
     */
    public
    function alerts(): void
    {
        if (isset($_SESSION['alerts'])) {
            foreach ($_SESSION['alerts'] as $alert) {
                echo '<div class="alert alert-' . $alert['type'] . ' alert-dismissible fade show" role="alert">';
                echo $alert['message'];
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
            }
            unset($_SESSION['alerts']);
        }
    }


    /**
     * Get page field value
     * @param string $index
     * @param ?string $slug
     * @return mixed
     */
    public
    function page(string $index, ?string $slug = null): mixed
    {
        $slug ??= $this->page;
        if ($this->is_page($slug)) {
            $page = $this->data('pages')[$slug];
            if (isset($page[$index])) {
                $value = $page[$index];
                $args = array($index, $slug, $page);
                return $this->get_filter($value, 'page', ...$args);
            }
        }
        return $this->get_filter(null, 'page', $index, $slug, array());
    }

    /**
     * Create new page
     * @param string $slug
     * @param array $details
     * @return bool
     */
    public function create_page(string $slug, array $details): bool
    {
        $this->get_action('create_page', $slug, $details);
        $keys = array_keys($details);
        $details = array_map(fn($value, $key) => in_array($key, ['title', 'descr', 'keywords']) ? htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false) : $value, $details, $keys);
        $this->database['pages'][$slug] = array_combine($keys, $details);
        return $this->save();
    }

    /**
     * Modify page
     * @param string $slug
     * @param string $permalink
     * @param array $updates
     * @return bool
     */
    public function update_page(string $slug, string $permalink, array $updates): bool
    {
        $this->get_action('update_page', $slug, $permalink, $updates);
        $keys = array_keys($updates);
        $updates = array_map(fn($value, $key) => in_array($key, ['title', 'descr', 'keywords']) ? htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false) : $value, $updates, $keys);
        $updates = array_merge($this->data('pages')[$slug], array_combine($keys, $updates));
        $this->database['pages'][$slug] = $updates;
        $data = json_encode($this->database, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
        $data = str_replace('"' . addcslashes($slug, '\/') . '":', '"' . addcslashes($permalink, '\/') . '":', $data);
        return $this->save(json_decode($data, true));
    }

    /**
     * Delete page
     * @param string $slug
     * @return bool
     */
    public function delete_page(string $slug, string $id): bool
    {
        $pages = $this->database['pages'];
        $likes = $this->database['likes'];

        // Check if the page slug and id exist before attempting to delete
        if (isset($pages[$slug]) && $pages[$slug]['id'] === $id) {
            unset($pages[$slug]);
            unset($likes[$id]); // Remove likes associated with the post
            unset($views[$id]); // Remove views associated with the post

            $this->database['pages'] = $pages;
            $this->database['likes'] = $likes;
            $this->database['views'] = $views;

            // Save the updated database
            return $this->save();
        }

        return false;
    }


    /**
     * Tells whether a page exists
     * @param string $slug
     * @return bool
     */
    public function is_page(string $slug): bool
    {
        return isset($this->data('pages')[$slug]);
    }

    /**
     * Upload media file
     * @param ?string $msg
     * @param ?string $basename
     * @return bool
     */
    public function upload_media(?string &$msg = null, ?string &$basename = null): bool
    {
        if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error'])) {
            $msg = 'Invalid parameters';
            return false;
        }
        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                $msg = 'No file has been sent';
                return false;
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $msg = 'File too large';
                return false;
                break;
            default:
                $msg = 'An unexpected error occurred';
                return false;
                break;
        }
        $tmp_name = $_FILES['file']['tmp_name'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $type = $finfo->file($tmp_name);
        $types = $this->_l('media_mime',
            array(
                'application/msword',
                'application/octet-stream',
                'application/ogg',
                'application/pdf',
                'application/vnd.rar',
                'application/x-rar',
                'application/vnd.ms-excel',
                'application/vnd.ms-powerpoint',
                'application/vnd.oasis.opendocument.spreadsheet',
                'application/vnd.oasis.opendocument.text',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/zip',
                'audio/mp4',
                'audio/mpeg',
                'image/avif',
                'image/gif',
                'image/jpeg',
                'image/png',
                'image/vnd.microsoft.icon',
                'image/webp',
                'image/x-icon',
                'text/plain',
                'video/mp4',
                'video/mpeg',
                'video/ogg',
                'video/quicktime',
                'video/webm',
                'video/x-flv',
                'video/x-matroska',
                'video/x-ms-wmv',
                'video/x-msvideo'
            )
        );
        if (!in_array($type, $types, true)) {
            $msg = sprintf('File format <b>%s</b> is not allowed', $type);
            return false;
        }
        $name = $this->esc_slug($_FILES['file']['name']);
        $basename = basename(empty($basename) ? strip_tags($name) : $basename);
        $extension = explode('.', $basename);
        $extension = strtolower(end($extension));
        $extensions = $this->_l('media_extension',
            array(
                'avi',
                'avif',
                'doc',
                'docx',
                'flv',
                'gif',
                'ico',
                'jpeg',
                'jpg',
                'm4a',
                'mkv',
                'mov',
                'mp3',
                'mp4',
                'mpg',
                'ods',
                'odt',
                'ogg',
                'ogv',
                'pdf',
                'png',
                'ppt',
                'pptx',
                'rar',
                'txt',
                'webm',
                'webp',
                'wmv',
                'xls',
                'xlsx',
                'zip'
            )
        );
        if (!in_array($extension, $extensions, true)) {
            if ($extension !== $basename || 'text/plain' !== $type) {
                $msg = sprintf('File extension <b>%s</b> is not allowed', $extension);
                return false;
            }
        }
        if (move_uploaded_file($tmp_name, $this->root('media/' . $basename))) {
            $msg = sprintf('File <b>%s</b> has been uploaded successfully', $basename);
            $this->get_action('upload_media', $basename);
            return true;
        }
        $msg = 'Failed to move uploaded file';
        return false;
    }

    /**
     * Delete media file
     * @param string $media
     * @return bool
     */
    public
    function delete_media(string $media): bool
    {
        if (in_array($media, $this->medias)) {
            $this->get_action('delete_media', $media);
            return unlink($this->root('media/' . $media));
        }
        return false;
    }

    /**
     * Install a plugin
     * @param string $plugin
     * @return bool
     */
    public function install(string $plugin): bool
    {
        if (in_array($plugin, $this->plugins)) {
            if (!$this->installed($plugin)) {
                $this->database['installed'][] = $plugin;
                $this->load_actions();
                $this->get_action('install', $plugin);
                return $this->save();
            }
        }
        return false;
    }

    /**
     * Uninstall a plugin
     * @param string $plugin
     * @return bool
     */
    public function uninstall(string $plugin): bool
    {
        if ($this->installed($plugin)) {
            $this->get_action('uninstall', $plugin);
            $index = array_search($plugin, $this->data('installed'));
            unset($this->database['installed'][$index]);
            return $this->save();
        }
        return false;
    }

    /**
     * Tells whether a plugin is installed
     * @param string $plugin
     * @return bool
     */
    public function installed(string $plugin): bool
    {
        return in_array($plugin, $this->data('installed'));
    }

    /**
     * Slugify text
     * @param string $text
     * @return string
     */
    public function slugify(string $text): string
    {
        $slug = preg_replace('|[^a-z0-9\-]+|i', '-', $text);
        $slug = preg_replace('|[\-]+|', '-', $slug);
        $slug = substr($slug, 0, 50);
        $slug = strtolower($slug);
        $slug = trim($slug, '-');
        $pages = $this->data('pages');
        $pages = array_keys($pages);
        $taken = $this->_l('slug_taken', $pages);
        $taken[] = $this->admin_url();
        if (in_array($slug, $taken) || file_exists($this->root($slug))) {
            $slug = ($slug . '-' . bin2hex(random_bytes(2)));
        }
        $slug = (empty($slug) ? bin2hex(random_bytes(2)) : $slug);
        return $this->get_filter($slug, 'slugify', $text);
    }

    /**
     * Redirections
     * @param string $location
     * @return void
     */
    public function go(string $location = ''): void
    {
        $this->get_action('go', $location);
        $location = $this->url($location);
        if (!headers_sent()) {
            header('Location: ' . $location, true, 302);
            exit;
        }
        exit('<meta http-equiv="refresh" content="0; url=' . $location . '">');
    }

    /**
     * Sanitize custom permalink
     * @param string $text
     * @param string $alt
     * @return string
     */
    public function esc_slug(string $text, string $alt = ''): string
    {
        $slug = stripslashes($text);
        $slug = filter_var($slug, FILTER_SANITIZE_URL);
        $slug = str_replace(array('?', '&', '#', '"'), '', $slug);
        $slug = trim(ltrim(empty($slug) ? $alt : $slug, './'));
        return $this->get_filter($slug, 'esc_slug', $text, $alt);
    }

    /**
     * Sanitize text
     * @param string|array|null $text
     * @param bool $trim
     * @return string
     */
    public function esc(string|array|null $text, bool $trim = true): string
    {
        if (is_array($text)) {
            $text = null;
        }
        $text ??= '';
        $text = stripslashes($text);
        $text = htmlspecialchars($text);
        return ($trim ? trim($text) : $text);
    }

    /**
     * Validate csrf token
     * @param ?string $location
     * @param bool $post
     * @return void
     */
    public function auth(?string $location = null, bool $post = true): void
    {
        $location ??= $this->page;
        $token = $this->esc($post ? ($_POST['token'] ?? '') : ($_GET['token'] ?? ''));
        if (!hash_equals($this->token(), $token)) {
            $this->get_action('token_error', $token);
            $this->alert($this->translate('invalid_token'), 'error');
            $this->go($location);
        }
    }

    public function generateID()
    {
        return md5(uniqid() . time());
    }

    public function getSystemInfo(): array
    {
        $opcacheStatus = function_exists('opcache_get_status') ? opcache_get_status() : [];
        $gdInfo = function_exists('gd_info') ? gd_info() : [];

        return [
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
    }

    /**
     * Get social media links as an array.
     */
    public function getSocialMediaLinks()
    {
        $socialLinks = [
            'facebook' => $this->get('facebook'),
            'instagram' => $this->get('instagram'),
            'twitter' => $this->get('twitter'),
            'youtube' => $this->get('youtube'),
            'linkedin' => $this->get('linkedin'),
            'pinterest' => $this->get('pinterest')
        ];

        $socialIcons = [
            'facebook' => 'bi-facebook',
            'instagram' => 'bi-instagram',
            'twitter' => 'bi-twitter',
            'youtube' => 'bi-youtube',
            'linkedin' => 'bi-linkedin',
            'pinterest' => 'bi-pinterest'
        ];

        $socialTooltips = [
            'facebook' => $this->translate('Facebook'),
            'instagram' => $this->translate('Instagram'),
            'twitter' => $this->translate('Twitter X'),
            'youtube' => $this->translate('YouTube'),
            'linkedin' => $this->translate('LinkedIn'),
            'pinterest' => $this->translate('Pinterest')
        ];

        $socialMediaData = [];

        foreach ($socialLinks as $key => $link) {
            if (!empty($link)) {
                $socialMediaData[$key] = [
                    'link' => $link,
                    'icon' => $socialIcons[$key],
                    'tooltip' => $socialTooltips[$key]
                ];
            }
        }

        return $socialMediaData;
    }

    /**
     * Admin backend handler
     * @return void
     */
    public function admin(): void
    {
        global $layout, $action, $page;
        $page = $this->esc($_GET['page'] ?? '');
        $action = ($_GET['action'] ?? '');
        $action = (is_array($action) ? '' : $action);
        $layout = array('title' => '', 'content' => '');

        if (!$this->logged_in) {
            $this->get_action('login');
            $layout['title'] = 'Login';
            ob_start();
            include $this->root('app/admin/view/login.tpl'); // Ensure this path is correct
            $layout['content'] = ob_get_clean();

            if (isset($_POST['login'])) {
                $this->auth();
                $this->get_action('on_login');
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                if (hash_equals($this->get('username'), $username) && password_verify($password, $this->get('password'))) {
                    session_regenerate_id(true);
                    $_SESSION['logged_in'] = true;
                    $_SESSION['root'] = $this->root;
                    $this->get_action('login_success');
                    $this->go($this->admin_url());
                }
                $this->get_action('login_error', $username, $password);
                $this->alert($this->translate('incorrect_credentials'), 'danger');
                $this->go($this->admin_url());
            }
        } else {
            $this->get_action('admin');
            switch ($page) {

                case 'search':
                    // PHP Code for handling the search request
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])) {
                        $query = trim($_POST['query']);
                        $results = []; // This should be your function to fetch results based on $query
                        $pages = $this->searchPagesByKeywords2($query);

                        foreach ($pages as $slug => $page) {
                            $results[] = [
                                'title' => $page['title'],
                                'url' => $this->url($slug),
                                'type' => $page['type'], // Assuming type is part of your page data
                                'editUrl' => $this->admin_url("?page=update&action=$slug", true), // URL for editing the page
                                'viewUrl' => $this->url($slug) // URL for viewing the page
                            ];
                        }

                        header('Content-Type: application/json');
                        echo json_encode($results);
                        exit;
                    }
                    break;

                case 'content':
                    $layout['title'] = $this->translate('content_management');
                    // Get all pages from data
                    $allPages = $this->data('pages');
                    // Filter pages based on URL parameters if set
                    $filteredPages = array_filter($allPages, function ($page) {
                        // Filter by type
                        if (isset($_GET['type']) && $_GET['type'] !== 'all' && $_GET['type'] !== '' && $page['type'] !== $_GET['type']) {
                            return false;
                        }
                        // Filter by publish status
                        if (isset($_GET['pub']) && $_GET['pub'] !== 'all' && ((bool)$page['pub'] !== (bool)$_GET['pub'])) {
                            return false;
                        }
                        return true;
                    });

                    // Sort pages by date, newest first
                    uasort($filteredPages, function ($a, $b) {
                        return strtotime($b['date']) - strtotime($a['date']);
                    });

                    // Pagination logic
                    $totalPages = count($filteredPages);
                    $perPage = 10;
                    $currentPage = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
                    $start = ($currentPage - 1) * $perPage;
                    $paginatedPages = array_slice($filteredPages, $start, $perPage, true);

                    ob_start();
                    include $this->root('app/admin/view/content.tpl');
                    $layout['content'] = ob_get_clean();
                    break;

                case 'create':
                    $layout['title'] = $this->translate('create_page');
                    ob_start();
                    include $this->root('app/admin/view/create_page.tpl');
                    $layout['content'] = ob_get_clean();

                    if (isset($_POST['create'])) {
                        $this->auth();
                        $this->get_action('on_create');

                        // Validate content
                        $content = trim($_POST['content'] ?? '');
                        if (empty($content)) {
                            $_POST['pub'] = false;
                        } else {
                            $_POST['pub'] = filter_input(INPUT_POST, 'pub', FILTER_VALIDATE_BOOL);
                        }

                        $_POST['showInMenu'] = filter_input(INPUT_POST, 'showInMenu', FILTER_VALIDATE_BOOL) ?? false;
                        $_POST['showInFooter'] = filter_input(INPUT_POST, 'showInFooter', FILTER_VALIDATE_BOOL) ?? false;

                        // Handle cover upload
                        if (!empty($_FILES['cover-upload']['name'])) {
                            $cover = $_FILES['cover-upload'];
                            $uploadDir = $this->root('media/');
                            $uploadFile = $uploadDir . basename($cover['name']);
                            if (move_uploaded_file($cover['tmp_name'], $uploadFile)) {
                                $_POST['cover'] = basename($cover['name']);
                            } else {
                                $this->alert($this->translate('upload_failed'), 'danger');
                                $this->go($this->admin_url('?page=create'));
                                return;
                            }
                        } elseif (!empty($_POST['cover']) && in_array($_POST['cover'], $this->medias)) {
                            $_POST['cover'] = $_POST['cover']; // Preserve the cover if already in media and submitted
                        } else {
                            $_POST['cover'] = ''; // Do not set a default cover if none was uploaded or selected
                        }

                        $permalink = $this->esc_slug($_POST['permalink'], $this->slugify($_POST['title']));
                        unset($_POST['permalink'], $_POST['token'], $_POST['create']);
                        $taken = $this->_l('slug_taken');
                        $taken[] = $this->admin_url();

                        if ($this->is_page($permalink) || in_array($permalink, $taken)) {
                            $suggestedPermalink = $this->slugify($permalink);
                            $message = str_replace('{permalink}', $suggestedPermalink, $this->translate('page_exists'));
                            $this->alert($message, 'danger');
                            $this->go($this->admin_url('?page=create'));
                        } else if (file_exists($this->root($permalink))) {
                            $suggestedPermalink = $this->slugify($permalink);
                            $message = str_replace('{permalink}', $suggestedPermalink, $this->translate('dir_file_exists'));
                            $this->alert($message, 'danger');
                            $this->go($this->admin_url('?page=create'));
                        } else {
                            if ($this->create_page($permalink, $_POST)) {
                                $this->get_action('create_success', $permalink);
                                $previewLink = $_POST['pub'] ? sprintf(', ' . $this->translate('click') . ' <a href="%s" target="_blank" class="link-underline">' . $this->translate('here') . '</a> ' . $this->translate('to_preview') . '.', $this->url($permalink)) : '.';
                                $message = str_replace('{preview_link}', $previewLink, $this->translate('page_created_success'));
                                $this->alert($message, 'success');
                                $this->go($this->admin_url('?page=content'));
                            } else {
                                $this->get_action('create_error', $permalink, $_POST);
                                $this->alert($this->translate('page_not_created'), 'danger');
                                $this->go($this->admin_url('?page=create'));
                            }
                        }
                    }
                    break;

                case 'update':
                    $data = (empty($_POST) ? $this->data('pages')[$action] : $_POST);
                    $data['pub'] = ($data['pub'] === 'true' || $data['pub'] === true ? true : false);
                    $keys = array_keys($data);
                    $data = array_map(fn($value, $key) => in_array($key, ['title', 'descr', 'keywords', 'content', 'tpl', 'date']) ? htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false) : $value, $data, $keys);
                    $data = array_combine($keys, $data);

                    $layout['title'] = $this->translate('update_page');
                    ob_start();
                    include $this->root('app/admin/view/update.tpl');
                    $layout['content'] = ob_get_clean();

                    if (isset($_POST['update'])) {
                        $this->auth();
                        $this->get_action('on_update');

                        // Handle cover upload
                        if (!empty($_FILES['cover-upload']['name'])) {
                            $cover = $_FILES['cover-upload'];
                            $uploadDir = $this->root('media/');
                            $uploadFile = $uploadDir . basename($cover['name']);
                            if (move_uploaded_file($cover['tmp_name'], $uploadFile)) {
                                $_POST['cover'] = basename($cover['name']);
                            } else {
                                $this->alert($this->translate('upload_cover_failed'), 'danger');
                                $this->go($this->admin_url('?page=update&action=' . $action));
                                return;
                            }
                        } elseif (!empty($_POST['cover']) && in_array($_POST['cover'], $this->medias)) {
                            $_POST['cover'] = $_POST['cover']; // Preserve the cover if already in media and submitted
                        } else {
                            $_POST['cover'] = ''; // Do not set a default cover if none was uploaded or selected
                        }

                        $_POST['pub'] = filter_input(INPUT_POST, 'pub', FILTER_VALIDATE_BOOLEAN); // Ensure boolean value

                        // Capture boolean values for showing in menu and footer
                        $_POST['showInMenu'] = filter_input(INPUT_POST, 'showInMenu', FILTER_VALIDATE_BOOL) ?? false;
                        $_POST['showInFooter'] = filter_input(INPUT_POST, 'showInFooter', FILTER_VALIDATE_BOOL) ?? false;

                        $update = $this->esc_slug($_POST['permalink'], $action);
                        unset($_POST['permalink'], $_POST['token'], $_POST['update']);
                        $taken = $this->_l('slug_taken');
                        $taken[] = $this->admin_url();
                        if (($action !== $update) && $this->is_page($update) || in_array($update, $taken)) {
                            $suggestedPermalink = $this->slugify($update);
                            $message = str_replace('{permalink}', $suggestedPermalink, $this->translate('permalink_exists'));
                            $this->alert($message, 'danger');
                            $this->go($this->admin_url('?page=update&action=' . $action));
                        } else if (file_exists($this->root($update))) {
                            $suggestedPermalink = $this->slugify($update);
                            $message = str_replace('{permalink}', $suggestedPermalink, $this->translate('dir_file_exists'));
                            $this->alert($message, 'danger');
                            $this->go($this->admin_url('?page=update&action=' . $action));
                        } else {
                            if ($this->update_page($action, $update, $_POST)) {
                                $this->get_action('update_success', $action);
                                $message = $this->translate('update_success');
                                if ($_POST['pub']) {
                                    $previewLink = str_replace('{url}', $this->url($update), $this->translate('update_preview'));
                                    $message .= $previewLink;
                                }
                                $this->alert($message, 'success');
                                $this->go($this->admin_url('?page=content'));
                            } else {
                                $this->get_action('update_error', $action, $_POST);
                                $this->alert($this->translate('update_fail'), 'danger');
                                $this->go($this->admin_url('?page=update&action=' . $action));
                            }
                        }
                    }
                    break;

                case 'delete':
                    if (isset($_POST['delete'])) {
                        $this->auth();
                        $this->get_action('on_delete');
                        $pages = ($_POST['pages'] ?? array());

                        foreach ($pages as $page) {
                            // Extract slug, id, and title from the page array
                            $slug = $page['slug'] ?? '';
                            $id = $page['id'] ?? '';
                            $title = $page['title'] ?? '';

                            if ($this->delete_page($slug, $id)) {
                                $this->get_action('delete_success', $slug);
                                $message = str_replace('{title}', $title, $this->translate('delete_success'));
                                $this->alert($message, 'success');
                            } else {
                                $this->get_action('delete_error', $slug);
                                $message = str_replace('{title}', $title, $this->translate('delete_error'));
                                $this->alert($message, 'error');
                            }

                        }

                        $this->go($this->admin_url('?page=content'));
                    }
                    break;

                case 'media':
                    $layout['title'] = $this->translate('media_management');
                    ob_start();
                    include $this->root('app/admin/view/media.tpl');
                    $layout['content'] = ob_get_clean();

                    if (isset($_POST['upload'])) {
                        $this->auth();
                        $message = '';
                        if ($this->upload_media($message)) {
                            $this->alert($message, 'success');
                        } else {
                            $this->alert($message, 'error');
                        }
                        $this->go($this->admin_url('?page=media'));
                    }
                    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['file'])) {
                        $this->auth(post: false);
                        $file = $_GET['file'];
                        if ($this->delete_media($file)) {
                            $message = str_replace('{file}', $file, $this->translate('delete_media_success'));
                            $this->alert($message, 'success');
                        } else {
                            $message = str_replace('{file}', $file, $this->translate('delete_media_error'));
                            $this->alert($message, 'error');
                        }

                        $this->go($this->admin_url('?page=media'));
                    }
                    break;

                case 'plugins':
                    $layout['title'] = $this->translate('plugins');
                    $this->loadPluginDetails();
                    ob_start();
                    include $this->root('app/admin/view/plugins.tpl');
                    $layout['content'] = ob_get_clean();
                    if (isset($_GET['plugin'])) {
                        $this->auth(post: false);
                        $this->get_action('on_plugin');
                        $plugin = ($_GET['plugin'] ?? '');
                        if ($action === 'install') {
                            if ($this->install($plugin)) {
                                $message = str_replace('{plugin}', ucwords(str_replace('-', ' ', $plugin)), $this->translate('plugin_install_success'));
                                $this->alert($message, 'success');
                                $this->go($this->admin_url('?page=plugins'));
                            }
                            $message = str_replace('{plugin}', ucwords(str_replace('-', ' ', $plugin)), $this->translate('plugin_install_error'));
                            $this->alert($message, 'error');
                            $this->go($this->admin_url('?page=plugins'));
                        } else if ($action === 'uninstall') {
                            if ($this->uninstall($plugin)) {
                                $message = str_replace('{plugin}', ucwords(str_replace('-', ' ', $plugin)), $this->translate('plugin_uninstall_success'));
                                $this->alert($message, 'success');
                                $this->go($this->admin_url('?page=plugins'));
                            }
                            $message = str_replace('{plugin}', ucwords(str_replace('-', ' ', $plugin)), $this->translate('plugin_uninstall_error'));
                            $this->alert($message, 'error');
                            $this->go($this->admin_url('?page=plugins'));
                        }
                    }
                    break;

                case 'themes':
                    $layout['title'] = $this->translate('themes');
                    $this->loadThemeDetails(); // Load theme details on initialization.
                    // Render the themes page
                    ob_start();
                    include $this->root('app/admin/view/themes.tpl');
                    $layout['content'] = ob_get_clean();
                    // Handle theme activation
                    if (isset($_GET['theme'])) {
                        $this->auth(post: false);
                        $this->get_action('on_theme');
                        $theme = ($_GET['theme'] ?? '');
                        if ($action === 'activate') {
                            if (in_array($theme, $this->themes)) {
                                if ($this->set($theme, 'theme')) {
                                    $this->get_action('change_theme', $theme);
                                    $message = str_replace('{theme}', ucwords(str_replace('-', ' ', $theme)), $this->translate('theme_activate_success'));
                                    $this->alert($message, 'success');
                                    $this->go($this->admin_url('?page=themes'));
                                }
                            }
                            $this->alert($this->translate('theme_activate_error'), 'error');
                            $this->go($this->admin_url('?page=themes'));
                        }

                    }
                    break;

                case 'logout':
                    $this->auth(post: false);
                    unset($_SESSION['logged_in'], $_SESSION['root'], $_SESSION['token'], $_SESSION['alerts']);
                    $this->get_action('logout');
                    $this->go();
                    break;

                case 'settings':
                    $layout['title'] = $this->translate('settings');
                    ob_start();
                    include $this->root('app/admin/view/settings.tpl');
                    $layout['content'] = ob_get_clean();
                    if (isset($_POST['save'])) {
                        $this->auth();
                        $this->get_action('on_settings');
                        $_POST['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                        $_POST['url'] = rtrim(filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL), './?&#') . '/';
                        $_POST['maintenance'] = filter_input(INPUT_POST, 'maintenance', FILTER_VALIDATE_BOOL);
                        $data = $this->data();
                        unset($_POST['token'], $_POST['save']);

                        // Add social media fields to settings
                        $socialMediaFields = ['facebook', 'instagram', 'twitter', 'youtube', 'linkedin', 'pinterest'];
                        foreach ($socialMediaFields as $field) {
                            $_POST[$field] = filter_input(INPUT_POST, $field, FILTER_SANITIZE_URL);
                        }

                        $data['site'] = array_merge($data['site'], $_POST);
                        $keys = array_keys($data['site']);
                        $data['site'] = array_map(fn($value, $key) => in_array($key, ['lang', 'sitename', 'title', 'keywords', 'descr', 'admin_theme', 'footer', 'facebook', 'instagram', 'twitter', 'youtube', 'linkedin', 'pinterest']) ? htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false) : $value, $data['site'], $keys);
                        $data['site'] = array_combine($keys, $data['site']);
                        if ($this->save($data)) {
                            $this->database = $data;
                            $this->get_action('settings_success');
                            $this->alert($this->translate('settings_update_success'), 'success');
                            $this->go($this->admin_url('?page=settings'));
                        }
                        $this->get_action('settings_error', $data);
                        $this->alert($this->translate('settings_update_error'), 'error');
                        $this->go($this->admin_url('?page=settings'));
                    }
                    break;

                case 'user':
                    $layout['title'] = $this->translate('profile');
                    $avatarPath = $this->get('avatar');  // Assume fetching the avatar path from your database
                    $defaultAvatar = 'assets/img/no_avatar.png'; // Default avatar path
                    $avatarURL = (!empty($avatarPath) && file_exists($this->root($avatarPath))) ? $this->url($avatarPath) : $this->url($defaultAvatar);
                    ob_start();
                    include $this->root('app/admin/view/user.tpl');
                    $layout['content'] = ob_get_clean();

                    if (isset($_POST['save_profile'])) {
                        $this->auth();
                        $this->get_action('on_profile_save');

                        $real_name = $this->esc($_POST['real_name']);
                        $description = $this->esc($_POST['description']);
                        $avatar = $this->get('avatar');

                        $avatar = $this->get('avatar');

                        // Handle the avatar upload
                        if (!empty($_FILES['avatar']['name'])) {
                            $avatarFile = $_FILES['avatar'];
                            $uploadDir = $this->root('media/');
                            $uploadFile = $uploadDir . basename($avatarFile['name']);

                            // Check for valid image file and move the uploaded file
                            if (move_uploaded_file($avatarFile['tmp_name'], $uploadFile)) {
                                $avatar = 'media/' . basename($avatarFile['name']);
                                $this->set($avatar, 'avatar'); // Update the avatar in the database
                            } else {
                                $this->alert($this->translate('avatar_upload_failed'), 'error');
                                $this->go($this->admin_url('?page=user'));
                            }
                        }

                        $this->set($real_name, 'real_name');
                        $this->set($description, 'description');
                        $this->set($avatar, 'avatar');
                        $this->alert($this->translate('profile_update_success'), 'success');
                        $this->go($this->admin_url('?page=user'));
                    }

                    if (isset($_POST['password'])) {
                        $this->auth(); // Authenticates the current session and user.
                        $this->get_action('on_password'); // Custom action hook before processing the password change.

                        $old_pass = $_POST['old'] ?? '';
                        $new_pass = $_POST['new'] ?? '';
                        $confirm = $_POST['confirm'] ?? '';

                        // Check if the old password is correct
                        if (!password_verify($old_pass, $this->get('password'))) {
                            $this->get_action('password_error');
                            $this->alert($this->translate('incorrect_password'), 'danger');
                            $this->go($this->admin_url('?page=user'));
                        } else if (!hash_equals($new_pass, $confirm)) {
                            // Check if the new password and confirmation match
                            $this->alert($this->translate('passwords_do_not_match'), 'danger');
                            $this->go($this->admin_url('?page=user'));
                        } else if (strlen($new_pass) < 8) {
                            // Ensure the new password is at least 8 characters long
                            $this->alert($this->translate('password_too_short'), 'danger');
                            $this->go($this->admin_popup_menu('user'));
                        } else {
                            // Hash the new password
                            $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                            if ($this->set($hash, 'password')) {
                                // Update the password in the database
                                $this->get_action('password_success');
                                $this->alert($this->translate('password_update_success'), 'success');
                                // Optionally, refresh the session security token here if needed
                                $_SESSION['token'] = bin2hex(random_bytes(32));
                                // Regenerate the session ID to maintain the session but protect against fixation
                                session_regenerate_id(true);
                            } else {
                                $this->alert($this->translate('password_update_failed'), 'danger');
                            }
                            $this->go($this->admin_url('?page=user'));
                        }
                    }
                    break;

                case 'info':
                    $layout['title'] = $this->translate('info');
                    $info = $this->getSystemInfo(); // Get system info
                    ob_start();
                    include $this->root('app/admin/view/info.tpl');
                    $layout['content'] = ob_get_clean();
                    break;

                case 'upgrade':
                    $layout['title'] = $this->translate('Upgrade CMS');
                    ob_start();
                    include $this->root('app/admin/view/upgrade.tpl');
                    $layout['content'] = ob_get_clean();
                    break;


                default:
                    $page = 'dashboard';
                    $list = array_slice($this->data('pages'), -3, 3, true); // Get the last 3 pages
                    $layout['title'] = $this->translate('dashboard');
                    ob_start();
                    include $this->root('app/admin/view/dashboard.tpl');
                    $layout['content'] = ob_get_clean();
                    break;
            }
        }
        require_once $this->root('app/layout.php');
    }

    /**
     * Get the URL of the user's avatar.
     * Fetches the avatar path from the database and checks if the file exists.
     * Returns the URL to the avatar or a default avatar if not set or file does not exist.
     *
     * @return string The URL of the avatar image.
     */
    public function getAvatarUrl()
    {
        $avatarPath = $this->get('avatar');  // Fetch the avatar path from your database
        $defaultAvatar = 'assets/img/no_avatar.png';  // Path to your default avatar

        // Check if a custom avatar has been set; otherwise, use the default
        if (!empty($avatarPath) && file_exists($this->root($avatarPath))) {
            return $this->url($avatarPath);  // Return URL to the custom avatar
        } else {
            return $this->url($defaultAvatar);  // Return URL to the default avatar
        }
    }

    /**
     * Counts the number of pages.
     * @return int Number of pages.
     */
    public function countPages()
    {
        // Check if 'pages' is set and is an array
        if (isset($this->database['pages']) && is_array($this->database['pages'])) {
            $count = 0;
            foreach ($this->database['pages'] as $page) {
                if (isset($page['type']) && $page['type'] === 'page') {
                    $count++;
                }
            }
            return $count;
        }
        return 0;
    }

    /**
     * Counts the number of posts.
     * @return int Number of posts.
     */
    public function countPosts()
    {
        if (isset($this->database['pages']) && is_array($this->database['pages'])) {
            // Assuming posts are also stored under 'pages' with a type of 'post'
            $count = 0;
            foreach ($this->database['pages'] as $page) {
                if (isset($page['type']) && $page['type'] === 'post') {
                    $count++;
                }
            }
            return $count;
        }
        return 0;
    }


    public function get_paginated_posts(int $posts_per_page = 10): array
    {
        $pages = $this->data()['pages'];
        if (!is_array($pages)) {
            return [
                'posts' => [],
                'current_page' => 1,
                'total_pages' => 1
            ];
        }

        $posts = array_filter($pages, function ($page) {
            return $page['type'] === 'post' && $page['pub'];
        });
        usort($posts, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $total_posts = count($posts);
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $total_pages = ceil($total_posts / $posts_per_page);
        $offset = ($current_page - 1) * $posts_per_page;
        $paginated_posts = array_slice($posts, $offset, $posts_per_page);

        return [
            'posts' => $paginated_posts,
            'current_page' => $current_page,
            'total_pages' => $total_pages
        ];
    }


    private $footerPages = [];
    private $menuPages = [];

    /**
     * Prepare footer pages
     */
    public function prepare_footer_pages()
    {
        $this->footerPages = array_filter($this->data()['pages'], function ($page) {
            return isset($page['showInFooter']) && $page['showInFooter'];
        });
    }


    /**
     * Prepare menu pages
     */
    public function prepare_menu_pages()
    {
        $this->menuPages = array_filter($this->data()['pages'], function ($page) {
            return isset($page['showInMenu']) && $page['showInMenu'];
        });
    }


    public function countPublishedPosts()
    {
        $posts = array_filter($this->data('pages'), function ($page) {
            return $page['type'] === 'post' && $page['pub'];
        });
        $count = count($posts);

        // Calculate ordinal suffix
        $ordinalSuffix = ['st', 'nd', 'rd', 'th']; // Suffixes for ordinal numbers
        $position = $count; // Assuming you want to find the suffix for the count of published posts
        if (($position % 100 >= 11) && ($position % 100 <= 13)) {
            $suffix = 'th';
        } else {
            $suffix = $ordinalSuffix[($position % 10) - 1] ?? 'th';
        }

        return "{$count}{$suffix}" . ($count !== 1 ? 's' : '');
    }

    public function searchPagesByKeywords($keyword)
    {
        $pages = $this->data()['pages'];
        $matchingPages = [];
        foreach ($pages as $slug => $page) {
            $pageKeywords = explode(',', $page['keywords']);
            foreach ($pageKeywords as $pageKeyword) {
                if (trim(strtolower($pageKeyword)) == trim(strtolower($keyword))) {
                    $matchingPages[$slug] = $page;
                }
            }
        }
        return $matchingPages;
    }

    /**
     * Search pages by keywords, title, or content.
     *
     * @param string $keyword The keyword to search for.
     * @return array The array of pages that match the keyword, title, or content.
     */
    public function searchPagesByKeywords2($keyword)
    {
        $pages = $this->data()['pages'];
        $matchingPages = [];
        foreach ($pages as $slug => $page) {
            $searchContent = strtolower($page['title'] . ' ' . $page['content'] . ' ' . $page['keywords']);
            $keyword = strtolower(trim($keyword));
            if (strpos($searchContent, $keyword) !== false) {
                $matchingPages[$slug] = $page;
            }
        }
        return $matchingPages;
    }


    public function hasLiked($postId): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $likes = $this->data('likes') ?? [];
        return isset($likes[$postId]) && in_array($ip, $likes[$postId]);
    }

    public function likePost(string $postId, string $ip): bool
    {
        if (!isset($this->database['likes'][$postId])) {
            $this->database['likes'][$postId] = [];
        }

        if (!in_array($ip, $this->database['likes'][$postId])) {
            $this->database['likes'][$postId][] = $ip;
            return $this->save();
        }

        return false;
    }

    public function getPostLikes(string $postId): int
    {
        return isset($this->database['likes'][$postId]) ? count($this->database['likes'][$postId]) : 0;
    }

    public function incrementPostViews($postId)
    {
        $views = $this->data('views');
        if (!isset($views[$postId])) {
            $views[$postId] = [];
        }
        $userIP = $_SERVER['REMOTE_ADDR']; // Get the user's IP address
        if (!in_array($userIP, $views[$postId])) {
            $views[$postId][] = $userIP; // Add IP address to views array if not already present
            $this->database['views'] = $views; // Update the database
            $this->save(); // Save the changes to the database
        }
    }

    public function getPostViews($postId)
    {
        $views = $this->data('views')[$postId] ?? [];
        return count($views); // Return the count of unique views
    }


    /**
     * Set the current language and load corresponding translations.
     */
    public function setLanguage($lang)
    {
        $this->language = $lang;
        $this->loadTranslations();
    }

    /**
     * Load translations for the current language.
     */
    protected function loadTranslations()
    {
        $translationFile = $this->root . "/lang/{$this->language}.json";
        if (file_exists($translationFile)) {
            $json = file_get_contents($translationFile);
            $this->translations = json_decode($json, true);
        }
    }

    /**
     * Get a translated string by key.
     */
    public function translate(string $key): string
    {
        $currentLang = $this->get('lang'); // Get the current language set in settings
        $translationsPath = $this->root . '/lang/' . $currentLang . '.json';

        if (file_exists($translationsPath)) {
            $translations = json_decode(file_get_contents($translationsPath), true);
            return $translations[$key] ?? $key; // Return the translation or the key itself if not found
        }

        return $key; // Default to returning the key if the language file doesn't exist
    }


    /**
     * Get available languages by scanning the language directory.
     */
    public function getAvailableLanguages()
    {
        $langDir = $this->root . '/lang/';
        $languages = [];
        foreach (glob($langDir . '*.json') as $filename) {
            $langCode = basename($filename, '.json');
            $content = json_decode(file_get_contents($filename), true);
            $languages[$langCode] = $content['language'] ?? $langCode;  // Assuming each language file contains a 'language' key
        }
        return $languages;
    }

    /**
     * Render home, pages, and admin
     * @return void
     */
    public function render(): void
    {
        global $App;
        $App = $this; // Assign the current instance to the global variable
        $this->load_actions();
        header('X-Powered-By: PointCMS');
        $this->get_action('render');

        if ($this->get('maintenance') && strpos($this->page, 'admin') === false) {
            require_once $this->theme('maintenance.php');
            exit;
        }

        if (isset($_GET['action']) && $_GET['action'] === 'like' && isset($_GET['postId'])) {
            $postId = $_GET['postId'];
            $ip = $_SERVER['REMOTE_ADDR'];
            if ($this->likePost($postId, $ip)) {
                echo $this->getPostLikes($postId);
            } else {
                echo $this->getPostLikes($postId);
            }
            exit;
        }

        if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['postId'])) {
            $postId = $_GET['postId'];
            $this->incrementPostViews($postId);
            $currentViews = $this->getPostViews($postId);

            header('Content-Type: application/json');
            echo json_encode(['views' => $currentViews]);
            exit;
        }

        switch ($this->page) {
            case $this->admin_url():
                $this->admin();
                break;
            case $this->_('', 'index'):
                $this->get_action('home');
                require_once $this->theme('home.php');
                break;
            case 'posts':
                require_once $this->theme('posts.php');
                break;
            case 'keywords':
                $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
                $posts = $this->searchPagesByKeywords($keyword);
                require_once $this->theme('keywords.php');
                break;
            case 'search':
                $searchTerm = $_GET['search'] ?? '';
                $results = $searchTerm ? $this->searchPagesByKeywords2($searchTerm) : [];
                require_once $this->theme('search.php');
                break;
            case $this->page('pub'):
                $type = $this->page('type');
                $this->get_action($type . '_type');
                $tpl = $this->theme($this->page('tpl'));
                if (is_file($tpl)) {
                    require_once $tpl;
                    break;
                }
                require_once $this->theme('theme.php');
                break;
            default:
                http_response_code(404);
                $this->get_action('404');
                require_once $this->theme('404.php');
                break;
        }
        $this->get_action('rendered');
    }

}

?>
