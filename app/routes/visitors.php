<?php

use System\config;
use System\database\query;
use System\input;
use System\route;
use System\uri;
use System\view;

Route::collection(['before' => 'auth,install_exists'], function () {

    /**
     * List Visitors statistics
     */
    Route::get([
        'admin/extend/reports/visitors',
        'admin/extend/reports/visitors/(:num)'
    ], function ($page = 1) {
        $vars['token'] = Csrf::token();

        $vars['visitors_online'] = Visitors::paginate($page, Config::get('admin.posts_per_page'));

        if (Auth::admin() || Auth::demo()) {
            return View::create('extend/reports/visitors', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        } else {
            return View::create('error/permission', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        }
    });

    /**
     * Delete Visitors
     */
    Route::post('admin/extend/reports/visitors/reset', function () {
        if (Auth::demo()) {
            return Response::json([
                'notification' => __('global.demonstration')
            ]);
        } else {
            // Create the SQL query to truncate the 'views' table
            $result = "TRUNCATE TABLE " . Base::table('visitors');
            // Execute the query using DB::ask
            DB::ask($result);

            if ($result) {
                return Response::json([
                    'notification' => 'Visitors reset successfully.'
                ]);
            } else {
                return Response::json([
                    'notification' => 'Failed to reset visitors.'
                ]);
            }
        }
    });

    Route::get('admin/extend/reports/visitors/chart', function () {
        $table = Base::table('visitors');

        // Calculate the start of the current month
        $startOfMonth = date('Y-m-01');

        // Fetch counts based on created_at
        $sqlCreated = "SELECT DAY(created_at) AS dayOfMonth, COUNT(*) AS count 
               FROM {$table}
               WHERE created_at >= '{$startOfMonth}'
               GROUP BY DAY(created_at)
               ORDER BY DAY(created_at);";

        // Fetch counts based on last_activity
        $sqlLastActivity = "SELECT DAY(updated_at) AS dayOfMonth, COUNT(*) AS count 
                    FROM {$table}
                    WHERE updated_at >= '{$startOfMonth}'
                    GROUP BY DAY(updated_at)
                    ORDER BY DAY(updated_at);";

        // Execute both queries
        list($resultCreated, $statementCreated) = DB::ask($sqlCreated);
        list($resultLastActivity, $statementLastActivity) = DB::ask($sqlLastActivity);

        $createdData = $statementCreated->fetchAll(PDO::FETCH_OBJ);
        $lastActivityData = $statementLastActivity->fetchAll(PDO::FETCH_OBJ);

        // Prepare series data
        $daysInMonth = date('t'); // Total number of days in the current month
        $seriesCreated = array_fill(0, $daysInMonth, 0);
        $seriesLastActivity = array_fill(0, $daysInMonth, 0);

        foreach ($createdData as $data) {
            $seriesCreated[$data->dayOfMonth - 1] = (int)$data->count;
        }

        foreach ($lastActivityData as $data) {
            $seriesLastActivity[$data->dayOfMonth - 1] = (int)$data->count;
        }

        return Response::json([
            'labels' => range(1, $daysInMonth),
            'series' => [$seriesCreated, $seriesLastActivity]
        ]);
    });

    Route::get('admin/extend/reports/visitors/get_visits', function () {
        $table = Base::table('visitors');
        $today = date('Y-m-d');
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $startOfMonth = date('Y-m-01');
        $startOfYear = date('Y-01-01');

        // Visits today
        $visitsToday = Query::table($table)
            ->where('updated_at', '>=', $today . ' 00:00:00')
            ->where('updated_at', '<=', $today . ' 23:59:59')
            ->count();

        // Visits this week
        $visitsWeek = Query::table($table)
            ->where('updated_at', '>=', $startOfWeek . ' 00:00:00')
            ->where('updated_at', '<=', $today . ' 23:59:59')
            ->count();

        // Visits this month
        $visitsMonth = Query::table($table)
            ->where('updated_at', '>=', $startOfMonth . ' 00:00:00')
            ->where('updated_at', '<=', $today . ' 23:59:59')
            ->count();

        // Visits this year
        $visitsYear = Query::table($table)
            ->where('updated_at', '>=', $startOfYear . ' 00:00:00')
            ->where('updated_at', '<=', $today . ' 23:59:59')
            ->count();

        return Response::json([
            'visitsToday' => $visitsToday,
            'visitsWeek' => $visitsWeek,
            'visitsMonth' => $visitsMonth,
            'visitsYear' => $visitsYear
        ]);
    });

});