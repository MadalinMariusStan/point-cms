<?php

use System\config;
use System\database\query;
use System\input;
use System\route;
use System\view;

Route::collection(['before' => 'auth,csrf,install_exists'], function () {

    /**
     * List Metadata
     */
    Route::get('admin/extend/metadata', function () {
        $vars['token'] = Csrf::token();
        $vars['dashboard_page_options'] = [
            'panel' => 'Welcome',
            'posts' => 'Posts',
            'pages' => 'Pages',
        ];

        $vars['meta'] = Config::get('meta');
        $vars['pages'] = Page::dropdown();
        $vars['themes'] = Themes::all();

        if (Auth::admin()|| Auth::demo()) {
            return View::create('extend/metadata/edit', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
        } else {
            return View::create('error/permission', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        }
    });

    /**
     * Update Metadata
     */
    Route::post('admin/extend/metadata', function () {
        $input = Input::get([
            'site_name',
            'description',
            'site_email',
            'cookie',
            'cookie_policy_page',
            'maintenance',
            'maintenance',
            'posts_page',
            'posts_per_page',
            'auto_published_comments',
            'theme',
            'admin_theme',
            'comment_notifications',
            'comment_moderation_keys',
            'show_all_posts',
            'dashboard_page'
        ]);

        foreach ($input as $key => $value) {
            $input[$key] = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('site_name')
            ->is_max(3, __('metadata.site_name_missing'));

        $validator->check('description')
            ->is_max(3, __('metadata.site_description_missing'));

        $validator->check('posts_per_page')
            ->is_regex('#^[0-9]+$#',
                __('metadata.missing_posts_per_page', 'Please enter a number for posts per page'));

        if ($errors = $validator->errors()) {
            Input::flash();

            return Response::json([
                'errors' => array_flatten($errors, [])
            ]);
        }


        // convert double quotes so we dont break html
        $input['site_name'] = e($input['site_name'], ENT_COMPAT);
        $input['description'] = e($input['description'], ENT_COMPAT);

        if (Auth::demo()) {
            return Response::json([
                'notification' => __('global.demonstration')
            ]);
        } else {
            foreach ($input as $key => $v) {
                $v = is_null($v) ? 0 : $v;

                Query::table(Base::table('meta'))
                    ->where('key', '=', $key)
                    ->update(['value' => $v]);
            }

            return Response::json([
                'notification' => __('metadata.updated')
            ]);
        }
    });

});
