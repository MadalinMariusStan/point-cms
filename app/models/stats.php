<?php

use System\config;
use System\database\query;
use System\uri;

/**
 * Posts statistics class for tracking and paginating posts based on views.
 */
class Stats extends Base {

    public static $table = 'posts';

    public static function paginate($page = 1, $perpage = 10)
    {
// Initialize the base query for fetching posts and their view counts
        $query = Query::table(static::$table)
            ->select(static::$table . '.*', Query::raw('COUNT(views.id) AS views'))
            ->leftJoin('views', 'views.post', '=', static::$table . '.id')
            ->groupBy(static::$table . '.id');

        // Clone the base query for counting total available posts (ignoring pagination)
        $countQuery = clone $query;
        $totalItems = $countQuery->count('distinct ' . static::$table . '.id');

        // Apply pagination and sorting
        $results = $query
            ->take($perpage)
            ->skip(($page - 1) * $perpage)
            ->sort('created_at', 'desc') // Assuming 'created_at' is the column to sort by
            ->get();

        // Return a Paginator instance with results and pagination info
        return new Paginator($results, $totalItems, $page, $perpage, Uri::to('admin/extend/stats'));
    }

}

