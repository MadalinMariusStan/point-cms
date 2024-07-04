<?php defined('App') or die('BoidCMS');
return array(
    'site' => array(
        'lang' => 'en',
        'sitename' => 'My Blog',
        'keywords' => 'blog, keywords, for, seo',
        'title' => 'My First Blog.',
        'descr' => 'Your Blog Description',
        'url' => 'http' . (filter_var($_SERVER['HTTPS'] ?? 0, FILTER_VALIDATE_BOOL) || ($_SERVER['SERVER_PORT'] == 443) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . ((($_SERVER['SERVER_PORT'] == 80) || ($_SERVER['SERVER_PORT'] == 443)) ? '' : ':' . $_SERVER['SERVER_PORT']) . ((dirname($_SERVER['SCRIPT_NAME']) === '/') ? '' : dirname($_SERVER['SCRIPT_NAME'])) . '/',
        'email' => 'your_mail@example.com',
        'real_name' => 'John Doe',
        'username' => 'admin',
        'password' => password_hash('password', PASSWORD_DEFAULT),
        'theme' => 'default',
        'admin' => 'admin'
        
    ),
    'installed' => array()
);
?>
