<?php

use System\config;
use System\database\query;
use System\input;
use System\route;
use System\uri;
use System\view;
use System\session;


Route::collection(['before' => 'auth,csrf,install_exists'], function () {

    // TODO: Unused page parameter, what for?
    Route::get('admin/panel', function ($page = 1) {
        $vars['token'] = Csrf::token();

        /**
         * Counts the number of total  comments / posts / pages
         *
         * @return string
         * @throws \ErrorException
         * @throws \Exception
         */

        $user = Auth::user();
        if (!empty($user)) {
            $vars['user'] = $user->real_name;
        }

        $user_id = $user->id;

        $comments_count_total = Query::table(Base::table('comments'))
            ->count();

        if ($comments_count_total > 1000000000000) {
            $vars['total_comments'] = round($comments_count_total / 1000000000000, 1) . 'T';
        } elseif ($comments_count_total > 1000000000) {
            $vars['total_comments'] = round($comments_count_total / 1000000000, 1) . 'B';
        } elseif ($comments_count_total > 1000000) {
            $vars['total_comments'] = round($comments_count_total / 1000000, 1) . 'M';
        } elseif ($comments_count_total > 1000) {
            $vars['total_comments'] = round($comments_count_total / 1000, 1) . 'K';
        } else {
            $vars['total_comments'] = $comments_count_total;
        }

        $posts_count_total = Query::table(Base::table('posts'))
            ->where('author', '=', $user_id)
            ->count();

        if ($posts_count_total > 1000000000000) {
            $vars['total_posts'] = round($posts_count_total / 1000000000000, 1) . 'T';
        } elseif ($posts_count_total > 1000000000) {
            $vars['total_posts'] = round($posts_count_total / 1000000000, 1) . 'B';
        } elseif ($posts_count_total > 1000000) {
            $vars['total_posts'] = round($posts_count_total / 1000000, 1) . 'M';
        } elseif ($posts_count_total > 1000) {
            $vars['total_posts'] = round($posts_count_total / 1000, 1) . 'K';
        } else {
            $vars['total_posts'] = $posts_count_total;
        }

        $pages_count_total = Query::table(Base::table('pages'))
            ->count();

        if ($pages_count_total > 1000000000000) {
            $vars['total_pages'] = round($pages_count_total / 1000000000000, 1) . 'T';
        } elseif ($pages_count_total > 1000000000) {
            $vars['total_pages'] = round($pages_count_total / 1000000000, 1) . 'B';
        } elseif ($pages_count_total > 1000000) {
            $vars['total_pages'] = round($pages_count_total / 1000000, 1) . 'M';
        } elseif ($pages_count_total > 1000) {
            $vars['total_pages'] = round($pages_count_total / 1000, 1) . 'K';
        } else {
            $vars['total_pages'] = $pages_count_total;
        }

        $users_count_total = Query::table(Base::table('users'))
            ->count();

        if ($users_count_total > 1000000000000) {
            $vars['total_users'] = round($users_count_total / 1000000000000, 1) . 'T';
        } elseif ($users_count_total > 1000000000) {
            $vars['total_users'] = round($users_count_total / 1000000000, 1) . 'B';
        } elseif ($users_count_total > 1000000) {
            $vars['total_users'] = round($users_count_total / 1000000, 1) . 'M';
        } elseif ($users_count_total > 1000) {
            $vars['total_users'] = round($users_count_total / 1000, 1) . 'K';
        } else {
            $vars['total_users'] = $users_count_total;
        }

        $visitors_count_total = Query::table(Base::table('visitors'))
            ->count();

        if ($visitors_count_total > 1000000000000) {
            $vars['total_visitors'] = round($visitors_count_total / 1000000000000, 1) . 'T';
        } elseif ($visitors_count_total > 1000000000) {
            $vars['total_visitors'] = round($visitors_count_total / 1000000000, 1) . 'B';
        } elseif ($visitors_count_total > 1000000) {
            $vars['total_visitors'] = round($visitors_count_total / 1000000, 1) . 'M';
        } elseif ($visitors_count_total > 1000) {
            $vars['total_visitors'] = round($visitors_count_total / 1000, 1) . 'K';
        } else {
            $vars['total_visitors'] = $visitors_count_total;
        }

        $subscribers_count_total = Query::table(Base::table('subscribers'))
            ->count();

        if ($subscribers_count_total > 1000000000000) {
            $vars['total_subscribers'] = round($subscribers_count_total / 1000000000000, 1) . 'T';
        } elseif ($subscribers_count_total > 1000000000) {
            $vars['total_subscribers'] = round($subscribers_count_total / 1000000000, 1) . 'B';
        } elseif ($subscribers_count_total > 1000000) {
            $vars['total_subscribers'] = round($subscribers_count_total / 1000000, 1) . 'M';
        } elseif ($subscribers_count_total > 1000) {
            $vars['total_subscribers'] = round($subscribers_count_total / 1000, 1) . 'K';
        } else {
            $vars['total_subscribers'] = $subscribers_count_total;
        }

        return View::create('panel', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get('admin/panel/visitors_chart', function () {
        $table = Base::table('visitors');

        // Calculate the start of the current week
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));

        // Fetch counts based on created_at
        $sqlCreated = "SELECT DAYNAME(created_at) AS dayOfWeek, COUNT(*) AS count 
               FROM {$table}
               WHERE created_at >= '{$startOfWeek}'
               GROUP BY DAYOFWEEK(created_at)
               ORDER BY DAYOFWEEK(created_at);";

        // Fetch counts based on last_activity
        $sqlLastActivity = "SELECT DAYNAME(updated_at) AS dayOfWeek, COUNT(*) AS count 
                    FROM {$table}
                    WHERE updated_at >= '{$startOfWeek}'
                    GROUP BY DAYOFWEEK(updated_at)
                    ORDER BY DAYOFWEEK(updated_at);";

        // Execute both queries
        list($resultCreated, $statementCreated) = DB::ask($sqlCreated);
        list($resultLastActivity, $statementLastActivity) = DB::ask($sqlLastActivity);

        $createdData = $statementCreated->fetchAll(PDO::FETCH_OBJ);
        $lastActivityData = $statementLastActivity->fetchAll(PDO::FETCH_OBJ);

        // Prepare series data
        $dayMap = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $seriesCreated = array_fill(0, 7, 0);
        $seriesLastActivity = array_fill(0, 7, 0);

        foreach ($createdData as $data) {
            $index = array_search($data->dayOfWeek, $dayMap);
            if ($index !== false) {
                $seriesCreated[$index] = (int)$data->count;
            }
        }

        foreach ($lastActivityData as $data) {
            $index = array_search($data->dayOfWeek, $dayMap);
            if ($index !== false) {
                $seriesLastActivity[$index] = (int)$data->count;
            }
        }

        return Response::json([
            'labels' => $dayMap,
            'series' => [$seriesCreated, $seriesLastActivity]
        ]);
    });

    Route::get('admin/panel/get_visits_today', function () {

        $visitsToday = Query::table(Base::table('visitors'))
            ->where('updated_at', '>=', date('Y-m-d') . ' 00:00:00')
            ->where('updated_at', '<=', date('Y-m-d') . ' 23:59:59')
            ->count();

        return Response::json([
            'visitsToday' => $visitsToday
        ]);
    });

});
