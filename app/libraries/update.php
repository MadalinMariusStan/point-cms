<?php

use System\config;
use System\database\query;
use System\error;

/**
 * update class
 * Handles blog script software updates
 */
class update
{
    protected static $githubReleasesUrl = 'https://api.github.com/repos/MadalinMariusStan/point-cms/releases';
    protected static $githubRepo = 'MadalinMariusStan/point-cms';

    /**
     * Checks the blog script version
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function version()
    {
        // first time
        if (! $last = Config::meta('last_update_check')) {
            static::setup();
        }

        static::renew();
    }

    /**
     * Sets up the update check database entries
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function setup()
    {
        $version = static::touch();
        $today   = date('Y-m-d H:i:s');
        $table   = Base::table('meta');
        $meta    = [];

        Query::table($table)->insert(['key' => 'last_update_check', 'value' => $today]);
        Query::table($table)->insert(['key' => 'update_version', 'value' => $version]);

        // reload database metadata
        foreach (Query::table($table)->get() as $item) {
            $meta[$item->key] = $item->value;
        }

        Config::set('meta', $meta);
    }

    /**
     * Queries the GitHub API for the latest release version
     *
     * @return bool|string
     */
    public static function touch()
    {
        $result = false;

        if (function_exists('curl_init')) {
            try {
                $session = curl_init();
                curl_setopt_array($session, [
                    CURLOPT_URL            => self::$githubReleasesUrl,
                    CURLOPT_HEADER         => false,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_USERAGENT      => 'Mozilla/5.0', // Required by GitHub API
                ]);

                $response = curl_exec($session);
                curl_close($session);

                $releases = json_decode($response, true);

                if (is_array($releases) && isset($releases[0]['tag_name'])) {
                    $result = $releases[0]['tag_name'];
                } else {
                    error::log("Unable to check for update... Invalid response from GitHub API.");
                }
            } catch (Exception $e) {
                error::log("Unable to check for update... Exception:\n$e");
            }
        }

        return $result;
    }

    /**
     * Renews the blog script update check
     *
     * @return void
     * @throws \ErrorException
     * @throws \Exception
     */
    public static function renew()
    {
        $version = static::touch();
        $today   = date('Y-m-d H:i:s');
        $table   = Base::table('meta');
        $meta    = [];

        Query::table($table)
            ->where('key', '=', 'last_update_check')
            ->update(['value' => $today]);

        Query::table($table)
            ->where('key', '=', 'update_version')
            ->update(['value' => $version]);

        // reload database metadata
        foreach (Query::table($table)->get() as $item) {
            $meta[$item->key] = $item->value;
        }

        Config::set('meta', $meta);
    }

    // Upgrade method remains the same...
}
