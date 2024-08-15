<?php

use System\config;
use System\input;
use System\route;
use System\view;

Route::collection(['before' => 'auth,csrf,install_exists'], function () {

    /**
     * List Categories
     */
    Route::get(['admin/categories', 'admin/categories/(:num)'], function ($page = 1) {
        $vars['categories'] = Category::paginate($page, Config::get('admin.posts_per_page'));

        if (Auth::admin() || Auth::demo()) {
        return View::create('categories/index', $vars)
                   ->partial('header', 'partials/header')
                   ->partial('footer', 'partials/footer');
    } else {
        return View::create('error/permission', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    }
    });

    /**
     * Edit Category
     */
    Route::get('admin/categories/edit/(:num)', function ($id) {
        $vars['token']    = Csrf::token();
        $vars['category'] = Category::find($id);

        if (Auth::admin() || Auth::demo()) {
            return View::create('categories/edit', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        } else {
            return View::create('error/permission', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        }
    });

    Route::post('admin/categories/edit/(:num)', function ($id) {
        $input = Input::get(['title', 'slug', 'description']);

        foreach ($input as $key => &$value) {
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('title')
                  ->is_max(3, __('categories.title_missing'));

        if ($errors = $validator->errors()) {
            Input::flash();

            // Notify::error($errors);

            return Response::json([
                'id'     => $id,
                'errors' => array_flatten($errors, [])
            ]);
        }

        if (empty($input['slug'])) {
            $input['slug'] = $input['title'];
        }

        $input['slug'] = slug($input['slug']);

        if (Auth::demo()) {
            return Response::json([
                'notification' => __('global.demonstration')
            ]);
        } else {
            Category::update($id, $input);

            return Response::json([
                'id' => $id,
                'notification' => __('categories.updated')
            ]);
        }
    });

    /**
     * Add Category
     */
    Route::get('admin/categories/add', function () {
        $vars['token'] = Csrf::token();

        if (Auth::admin() || Auth::demo()) {
            return View::create('categories/add', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        } else {
            return View::create('error/permission', $vars)
                ->partial('header', 'partials/header')
                ->partial('footer', 'partials/footer');
        }
    });

    Route::post('admin/categories/add', function () {
        $input = Input::get(['title', 'slug', 'description']);

        foreach ($input as $key => &$value) {
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('title')
                  ->is_max(3, __('categories.title_missing'));

        if ($errors = $validator->errors()) {
            Input::flash();

            // Notify::error($errors);

            // TODO: $id is undefined and will throw
            return Response::json([
                'id'     => -1,
                'errors' => array_flatten($errors, [])
            ]);
        }

        if (empty($input['slug'])) {
            $input['slug'] = $input['title'];
        }

        $input['slug'] = slug($input['slug']);
        if (Auth::demo()) {
            return Response::json([
                'notification' => __('global.demonstration')
            ]);
        } else {
            $category = Category::create($input);
            $id = $category->id;

            return Response::json([
                'id' => $id,
                'notification' => __('categories.created'),
                'redirect' => Uri::to('admin/categories/edit/' . $id)
            ]);
        }
    });

    /**
     * Delete Category
     */
    Route::get('admin/categories/delete/(:num)', function ($id) {
        $total = Category::count();

        if ($total == 1) {
            Notify::error(__('categories.delete_error'));

            return Response::redirect('admin/categories/edit/' . $id);
        }

        // move posts
        $category = Category::where('id', '<>', $id)->fetch();

        if (Auth::demo()) {
            Notify::error(__('global.demonstration'));
        } else {
            // delete selected
            Category::find($id)->delete();
            // update posts
            Post::where('category', '=', $id)->update([
                'category' => $category->id
            ]);

            Notify::success(__('categories.deleted'));
        }


        return Response::redirect('admin/categories');
    });
});
