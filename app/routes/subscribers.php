<?php

use System\config;
use System\database\query;
use System\input;
use System\route;
use System\uri;
use System\view;

Route::collection(['before' => 'auth,install_exists'], function () {

    /**
     * List Posts views statistics
     */
    Route::get([
        'admin/extend/subscribers',
        'admin/extend/subscribers/(:num)'
    ], function ($page = 1) {
        $vars['token'] = Csrf::token();

        $query = Query::table(Base::table(Subscriber::$table));
        $perpage = Config::get('admin.posts_per_page');
        $count = $query->count();
        $results = $query
            ->take($perpage)
            ->skip(($page - 1) * $perpage)
            ->sort('created_at', 'desc')
            ->get();

        $vars['subscribers'] = new Paginator($results, $count, $page, $perpage, Uri::to('admin/extend/subscribers'));

        if (Auth::admin() || Auth::demo()) {
            return View::create('extend/subscribers/subscribers', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        } else {
            return View::create('error/permission', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        }
    });


    /**
     * Subscribers export
     */
    Route::get('admin/extend/subscribers/export-csv', function () {
        if (Auth::demo()) {
            Notify::error(__('global.demonstration'));
        } else {
            // Create the SQL query to truncate the 'views' table
            $subscribers = Query::table(Base::table('subscribers'))
                ->get();

            // Prepare the CSV content
            $csvData = "Email\n"; // CSV header

            foreach ($subscribers as $subscriber) {
                $csvData .= '"' . $subscriber->email . "\"\n";
            }

            // Generate a unique CSV file name (e.g., based on timestamp)
            $filename = 'email_addresses_' . date('Y-m-d_H-i-s') . '.csv';

            // Set the HTTP response headers for CSV download
            $headers = array(
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            );

            // Return the CSV as a downloadable file
            return Response::create($csvData, 200, $headers);
        }
    });


    // TODO: Unused page parameter, what for?
    Route::get('admin/extend/subscribers/newsletter', function ($page = 1) {
        $vars['token'] = Csrf::token();

        if (Auth::admin() || Auth::demo()) {
            return View::create('extend/subscribers/newsletter', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        } else {
            return View::create('error/permission', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        }
    });

    Route::post('admin/extend/subscribers/newsletter/send', function ($page = 1) {
        $input = Input::get(array('subject', 'message'));

        // Validator check...
        $validator = new Validator($input);

        $validator->check('subject')
            ->is_max(1, "Subject is required!");

        $validator->check('message')
            ->is_max(5, "Message is empty or too short!");

        if ($errors = $validator->errors()) {
            Input::flash();
            return Response::json([
                'errors' => array_flatten($errors, [])
            ]);
        }

        $subscribers = Query::table(Subscriber::table())->get();
        $language = str_replace('_', '-', Config::app('language'));
        $subject = $input['subject'];
        $message = $input['message'];
        $site_name = Config::meta('site_name');
        $year = date("Y");
        $dont_like = __('global.dont_like');

        foreach ($subscribers as $subscriber) {
            $unsubscribe_id = $subscriber->id;
            $unsubscribe_link = Config::app('http_server') . 'unsubscribe/' . $unsubscribe_id;

            $html = ' <!doctype html>
                        <html dir="ltr" lang="' . $language . '">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>' . $subject . '</title>
                            <style media="all" type="text/css">
                                /* -------------------------------------
                                GLOBAL RESETS
                            ------------------------------------- */
                        
                                body {
                                    font-family: Helvetica, sans-serif;
                                    -webkit-font-smoothing: antialiased;
                                    font-size: 16px;
                                    line-height: 1.3;
                                    -ms-text-size-adjust: 100%;
                                    -webkit-text-size-adjust: 100%;
                                }
                        
                                table {
                                    border-collapse: separate;
                                    mso-table-lspace: 0pt;
                                    mso-table-rspace: 0pt;
                                    width: 100%;
                                }
                        
                                table td {
                                    font-family: Helvetica, sans-serif;
                                    font-size: 16px;
                                    vertical-align: top;
                                }
                        
                                /* -------------------------------------
                                BODY & CONTAINER
                            ------------------------------------- */
                        
                                body {
                                    background-color: #f4f5f6;
                                    margin: 0;
                                    padding: 0;
                                }
                        
                                .body {
                                    background-color: #f4f5f6;
                                    width: 100%;
                                }
                        
                                .container {
                                    margin: 0 auto !important;
                                    max-width: 600px;
                                    padding: 0;
                                    padding-top: 24px;
                                    width: 600px;
                                }
                        
                                .content {
                                    box-sizing: border-box;
                                    display: block;
                                    margin: 0 auto;
                                    max-width: 600px;
                                    padding: 0;
                                }
                        
                                /* -------------------------------------
                                HEADER, FOOTER, MAIN
                            ------------------------------------- */
                        
                                .main {
                                    background: #ffffff;
                                    border: 1px solid #eaebed;
                                    border-radius: 16px;
                                    width: 100%;
                                }
                        
                                .wrapper {
                                    box-sizing: border-box;
                                    padding: 24px;
                                }
                        
                                .footer {
                                    clear: both;
                                    padding-top: 24px;
                                    text-align: center;
                                    width: 100%;
                                }
                        
                                .footer td,
                                .footer p,
                                .footer span,
                                .footer a {
                                    color: #9a9ea6;
                                    font-size: 16px;
                                    text-align: center;
                                }
                        
                                /* -------------------------------------
                                TYPOGRAPHY
                            ------------------------------------- */
                        
                                p {
                                    font-family: Helvetica, sans-serif;
                                    font-size: 16px;
                                    font-weight: normal;
                                    margin: 0;
                                    margin-bottom: 16px;
                                }
                        
                                a {
                                    color: #0867ec;
                                    text-decoration: underline;
                                }
                        
                                /* -------------------------------------
                                OTHER STYLES THAT MIGHT BE USEFUL
                            ------------------------------------- */
                        
                                .last {
                                    margin-bottom: 0;
                                }
                        
                                .first {
                                    margin-top: 0;
                                }
                        
                                .align-center {
                                    text-align: center;
                                }
                        
                                .align-right {
                                    text-align: right;
                                }
                        
                                .align-left {
                                    text-align: left;
                                }
                        
                                .text-link {
                                    color: #0867ec !important;
                                    text-decoration: underline !important;
                                }
                        
                                .clear {
                                    clear: both;
                                }
                        
                                .mt0 {
                                    margin-top: 0;
                                }
                        
                                .mb0 {
                                    margin-bottom: 0;
                                }
                        
                                .preheader {
                                    color: transparent;
                                    display: none;
                                    height: 0;
                                    max-height: 0;
                                    max-width: 0;
                                    opacity: 0;
                                    overflow: hidden;
                                    mso-hide: all;
                                    visibility: hidden;
                                    width: 0;
                                }
                        
                        
                                /* -------------------------------------
                                RESPONSIVE AND MOBILE FRIENDLY STYLES
                            ------------------------------------- */
                        
                                @media only screen and (max-width: 640px) {
                                    .main p,
                                    .main td,
                                    .main span {
                                        font-size: 16px !important;
                                    }
                        
                                    .wrapper {
                                        padding: 8px !important;
                                    }
                        
                                    .content {
                                        padding: 0 !important;
                                    }
                        
                                    .container {
                                        padding: 0 !important;
                                        padding-top: 8px !important;
                                        width: 100% !important;
                                    }
                        
                                    .main {
                                        border-left-width: 0 !important;
                                        border-radius: 0 !important;
                                        border-right-width: 0 !important;
                                    }
                                }
                        
                                /* -------------------------------------
                                PRESERVE THESE STYLES IN THE HEAD
                            ------------------------------------- */
                        
                                @media all {
                                    .ExternalClass {
                                        width: 100%;
                                    }
                        
                                    .ExternalClass,
                                    .ExternalClass p,
                                    .ExternalClass span,
                                    .ExternalClass font,
                                    .ExternalClass td,
                                    .ExternalClass div {
                                        line-height: 100%;
                                    }
                        
                                    .apple-link a {
                                        color: inherit !important;
                                        font-family: inherit !important;
                                        font-size: inherit !important;
                                        font-weight: inherit !important;
                                        line-height: inherit !important;
                                        text-decoration: none !important;
                                    }
                        
                                    #MessageViewBody a {
                                        color: inherit;
                                        text-decoration: none;
                                        font-size: inherit;
                                        font-family: inherit;
                                        font-weight: inherit;
                                        line-height: inherit;
                                    }
                                }
                            </style>
                        </head>
                        <body>
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
                            <tr>
                                <td>&nbsp;</td>
                                <td class="container">
                                    <div class="content">
                                        <!-- START CENTERED WHITE CONTAINER -->
                                        <span class="preheader">' . $subject . '</span>
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="main">
                                            <!-- START MAIN CONTENT AREA -->
                                            <tr>
                                                <td class="wrapper">
                                                    <p>' . $subject . '</p>
                                                    <p>' . html_entity_decode($message, ENT_QUOTES, 'UTF-8') . '</p>
                                                </td>
                                            </tr>
                        
                                            <!-- END MAIN CONTENT AREA -->
                                        </table>
                                        <!-- START FOOTER -->
                                        <div class="footer">
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td class="content-block">
                                                        <span class="apple-link"> &copy; ' . $year . '  ' . $site_name . ' </span>
                                                        <br> ' . $dont_like . ' <a href="' . $unsubscribe_link . '"
                                                                                   style="color: #000; text-decoration: none;">Unsubscribe</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <!-- END FOOTER -->
                                        <!-- END CENTERED WHITE CONTAINER -->
                                    </div>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                        </body>
                        </html>
                ';

            if (Auth::demo()) {
                return Response::json([
                    'notification' => __('global.demonstration')
                ]);
            } else {
                $host = parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST) ?: 'localhost';
                $from = 'notifications@' . $host;

                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                $headers .= 'From: ' . $from . "\r\n";

                $successCount = 0; // Count successful emails
                $failureCount = 0; // Count failed emails

                $to = $subscriber->email;
                if (mail($to, $subject, $html, $headers)) {
                    $successCount++;
                } else {
                    $failureCount++;
                }
            }
        }

        if ($successCount > 0) {
            return Response::json([
                'notification' => 'Email sent to ' . $successCount . ' subscribers.'
            ]);
        } else {
            return Response::json([
                'notification' => 'Failed to send emails to all subscribers. ' . $failureCount . ' emails failed.'
            ]);
        }
    });

    /**
     * Delete Subscribers
     */
    Route::post('admin/extend/subscribers/reset', function () {
        if (Auth::demo()) {
            return Response::json([
                'notification' => __('global.demonstration')
            ]);
        } else {
            // Create the SQL query to truncate the 'subscribers' table
            $result = "TRUNCATE TABLE " . Base::table('subscribers');
            // Execute the query using DB::ask
            DB::ask($result);

            if ($result) {
                return Response::json([
                    'notification' => 'Subscribers reset successfully.'
                ]);
            } else {
                return Response::json([
                    'notification' => 'An error occurred while resetting subscribers.'
                ]);
            }
        }
    });

});