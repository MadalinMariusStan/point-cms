<?php

use System\config;
use System\route;
use System\view;
use System\session;
use System\uri;


Route::collection(['before' => 'auth,csrf,install_exists'], function () {

    Route::get('admin/extend/tools/log', function () {
        $vars['token'] = Csrf::token();

        // Path to the error log file
        $errorLogFilePath = APP . 'errors.log';

        // Check if the error log file exists
        if (file_exists($errorLogFilePath)) {
            // Read the contents of the error log file
            $errorLogContent = file_get_contents($errorLogFilePath);

            // Pass the error log content to the view
            $vars['errorLogContent'] = nl2br(htmlspecialchars($errorLogContent));
        } else {
            // If the error log file doesn't exist, set an appropriate message
            $vars['errorLogContent'] = "Error log file not found.";
        }

        // Check the size of the error log file
        $size = filesize($errorLogFilePath);
        $maxSize = 3145728; // 3 MB in bytes

        if ($size >= $maxSize) {
            // Generate a notification message if the file size exceeds the threshold
            $suffix = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            $i = 0;
            while (($size / 1024) > 1) {
                $size /= 1024;
                $i++;
            }
            $notification = sprintf("Warning: Error log file is %.2f %s", $size, $suffix[$i]);
            $vars['notification'] = $notification;
        } else {
            // Generate size information if the file size is smaller than the threshold
            $suffix = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            $i = 0;
            while (($size / 1024) > 1) {
                $size /= 1024;
                $i++;
            }
            $sizeInfo = sprintf("Info: Error log file size is %.2f %s", $size, $suffix[$i]);
            $vars['sizeInfo'] = $sizeInfo;
        }

        if (Auth::admin() || Auth::demo()) {
            return View::create('extend/tools/log', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        } else {
            return View::create('error/permission', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        }
    });

    Route::get('admin/extend/tools/log/clear-errors', function () {
        if (Auth::demo()) {
            return Response::json([
                'notification' => __('global.demonstration')
                ]);
        } else {
            // Path to the error log file
            $errorLogFilePath = APP . 'errors.log';

            // Clear the contents of the error log file
            if (file_exists($errorLogFilePath)) {
                // Open the file in write mode, truncating it to zero length
                $fileHandle = fopen($errorLogFilePath, 'w+');
                if ($fileHandle !== false) {
                    // Close the file handle
                    fclose($fileHandle);
                    // Send a response indicating success
                    return Response::json([
                        'notification' => __('errors.success') // Assuming 'error.success' is the key for the success message in your translation file
                    ]);

                } else {
                    // Send an error response if unable to open the file
                    http_response_code(500);
                    return Response::json([
                        'notification' => __('errors.failed_to_open')
                    ], 500);

                }
            } else {
                // Send a response if the log file doesn't exist
                http_response_code(404);
                return Response::json([
                    'notification' => __('errors.log_file_not_found')
                ], 404);
            }
        }
    });

});
