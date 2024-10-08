<?php

use System\input;
use System\route;
use System\view;

Route::collection(['before' => 'auth,install_exists'], function () {

    /**
     * List Menu Items
     */
    Route::get('admin/menu', function () {
        $vars['pages'] = Page::where('show_in_menu', '=', 1)
            ->sort('menu_order')
            ->get();

        if (Auth::admin()|| Auth::demo()) {
            return View::create('menu/index', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        } else {
            return View::create('error/permission', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        }
    });

    /**
     * Update order
     */
    Route::post('admin/menu/update', function () {
        $sort = Input::get('sort');
        if (Auth::demo()) {
            return Response::json([
                'notification' => __('global.demonstration')
            ]);
        } else {
            foreach ($sort as $index => $id) {
                Page::where('id', '=', $id)
                    ->update(['menu_order' => $index]);

                return Response::json(['result' => true]);
            }
        }
    });
});
