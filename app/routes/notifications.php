<?php

use System\config;
use System\database\query;
use System\input;
use System\route;
use System\uri;

Route::collection(['before' => 'auth,install_exists'], function () {

    Route::get('admin/notifications/notifications', function () {
        $lastCheckTimestamp = strtotime('-1 day'); // Example: One day ago

        // Count new notifications within the last day that are unread
        $newNotificationsCount = Query::table(Base::table('notifications'))
            ->where('created_at', '>', date('Y-m-d H:i:s', $lastCheckTimestamp))
            ->where('is_read', '=', 0) // Optionally filter unread notifications
            ->count();

        // Return the count of new notifications as a JSON response
        return Response::json([
            'newNotificationsCount' => $newNotificationsCount
        ]);
    });

    Route::get([
        'admin/notifications',
        'admin/notifications/(:num)'
    ], function ($page = 1) {
        $perpage = Config::get('admin.posts_per_page'); // Define how many notifications per page

        // Retrieve all notifications for comments, posts, and likes, without time filtering
        $notifications = Query::table(Base::table('notifications'))
            ->where('is_read', '=', 0) // Optionally filter unread notifications
            ->sort('created_at', 'desc')
            ->get();

        $totalNotifications = count($notifications);

        // Implement pagination manually
        $offset = ($page - 1) * $perpage;
        $paginatedNotifications = array_slice($notifications, $offset, $perpage);

        // Process the notifications
        foreach ($paginatedNotifications as &$notification) {
            if ($notification->type == 'comment') {
                $comment = Query::table(Base::table('comments'))->where('id', '=', $notification->related_id)->fetch();
                if ($comment) {
                    $notification->details = [
                        'name' => $comment->name,
                        'text' => $comment->text,
                        'status' => $comment->status,
                        'date' => $comment->date,
                        'id' => $comment->id,
                    ];
                }
            } elseif ($notification->type == 'post') {
                $post = Query::table(Base::table('posts'))->where('id', '=', $notification->related_id)->fetch();
                if ($post) {
                    $notification->details = [
                        'title' => $post->title,
                        'author' => $post->author,
                        'created' => $post->created,
                        'post_id' => $post->id,
                    ];
                }
            } elseif ($notification->type == 'like') {
                $like = Query::table(Base::table('likes'))
                    ->join(Base::table('posts'), Base::table('likes.post'), '=', Base::table('posts.id'))
                    ->select(Base::table('likes.id'), Base::table('likes.post'), Base::table('likes.created_at'), Base::table('posts.title'))
                    ->where(Base::table('likes.id'), '=', $notification->related_id)
                    ->fetch();
                if ($like) {
                    $notification->details = [
                        'post_title' => $like->title,
                        'post_id' => $like->post,
                        'liked_at' => $like->created_at,
                    ];
                }
            } elseif ($notification->type == 'subscriber') {
                $subscriber = Query::table(Base::table('subscribers'))->where('id', '=', $notification->related_id)->fetch();
                if ($subscriber) {
                    $notification->details = [
                        'email' => $subscriber->email,
                        'subscribed_at' => $subscriber->created_at,
                    ];
                }
            }
        }

        // Pass the paginated notifications and pagination info to the view
        $vars['notifications'] = $paginatedNotifications;
        $vars['pagination'] = new Paginator($notifications, $totalNotifications, $page, $perpage, Uri::to('admin/notifications'));

        // Return the view with the data
        return View::create('notifications/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/notifications/mark-read', function () {
        $notificationId = Input::get('id');

        // Update the notification as read in the database
        Query::table(Base::table('notifications'))
            ->where('id', '=', $notificationId)
            ->update(['is_read' => 1]);

        return Response::json(['success' => true]);
    });

    Route::post('admin/notifications/mark-all-read', function () {
        // Update all unread notifications to be marked as read
        Query::table(Base::table('notifications'))
            ->where('is_read', '=', 0)
            ->update(['is_read' => 1]);

        return Response::json(['success' => true]);
    });


});
