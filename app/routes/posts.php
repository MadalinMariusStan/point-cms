<?php

use System\config;
use System\input;
use System\route;
use System\uri;
use System\view;

Route::collection(['before' => 'auth,csrf,install_exists'], function () {

    /**
     * List all posts and paginate through them
     */
    Route::get([
        'admin/posts',
        'admin/posts/(:num)'
    ], function ($page = 1) {
        $perpage = Config::get('admin.posts_per_page');
        $url = Uri::to('admin/posts');
        if (Auth::contributor()) {
            $user_id = Auth::user()->id;
            $total = Post::where('author', '=', $user_id)->count();
            $posts = Post::where('author', '=', $user_id)
                ->sort('created', 'desc')
                ->take($perpage)
                ->skip(($page - 1) * $perpage)
                ->get();
        } else {
            $total = Post::count();
            $posts = Post::sort('created', 'desc')
                ->take($perpage)
                ->skip(($page - 1) * $perpage)
                ->get();
        }

        $pagination = new Paginator($posts, $total, $page, $perpage, $url);

        $vars['posts'] = $pagination;
        $vars['status'] = 'all';

        return View::create('posts/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    /**
     * List posts by status and paginate through them
     */
    Route::get([
        'admin/posts/status/(:any)',
        'admin/posts/status/(:any)/(:num)'
    ], function ($status, $post = 1) {
        $perpage = Config::get('admin.posts_per_page');
        if (Auth::contributor()) {
            $user_id = Auth::user()->id;
            $query = Post::where('author', '=', $user_id)
                ->where('status', '=', $status);
        } else {
            $query = Post::where('status', '=', $status);
        }
        $total = $query->count();
        $url = Uri::to('admin/posts/status/' . $status);
        $posts = $query->sort('title')
            ->take($perpage)
            ->skip(($post - 1) * $perpage)
            ->get();

        $pagination = new Paginator($posts, $total, $post, $perpage, $url);

        $vars['posts'] = $pagination;
        $vars['status'] = $status;
        $vars['categories'] = Category::sort('title')->get();

        return View::create('posts/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::get([
        'admin/posts/my-posts',
        'admin/posts/my-posts/(:num)'
    ], function ($page = 1) {
        $perpage = Config::get('admin.posts_per_page');
        $url = Uri::to('admin/posts/my-posts');
        $user_id = Auth::user()->id;
        $total = Post::where('author', '=', $user_id)->count();
        $posts = Post::where('author', '=', $user_id)
            ->sort('created', 'desc')
            ->take($perpage)
            ->skip(($page - 1) * $perpage)
            ->get();

        $pagination = new Paginator($posts, $total, $page, $perpage, $url);

        $vars['posts'] = $pagination;
        $vars['status'] = 'my-posts';

        return View::create('posts/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });


    /**
     * Edit post
     */
    Route::get('admin/posts/edit/(:num)', function ($id) {
        $vars['token'] = Csrf::token();
        $vars['article'] = Post::find($id);
        $vars['page'] = Registry::get('posts_page');

        $vars['categories'] = Category::dropdown();

        $post_page = Registry::get('posts_page');

        $vars['page_slug'] = $post_page->slug . '/';

        $vars['statuses'] = [
            'published' => __('global.published'),
            'draft' => __('global.draft'),
            'archived' => __('global.archived')
        ];

        return View::create('posts/edit', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer')
            ->partial('editor', 'partials/editor');
    });

    Route::post('admin/posts/edit/(:num)', function ($id) {
        $input = Input::get([
            'title',
            'slug',
            'description',
            'meta_description',
            'image',
            'videolink',
            'created',
            'category',
            'status',
            'comments'
        ]);

        // if there is no slug try and create one from the title
        if (empty($input['slug'])) {
            $input['slug'] = $input['title'];
        }

        // convert to ascii
        $input['slug'] = slug($input['slug']);

        // an array of items that we shouldn't encode - they're no XSS threat
        $dont_encode = ['description', 'meta_description'];

        foreach ($input as $key => &$value) {
            if (in_array($key, $dont_encode)) {
                continue;
            }

            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->add('duplicate', function ($str) use ($id) {
            return Post::where('slug', '=', $str)
                    ->where('id', '<>', $id)
                    ->count() == 0;
        });

        $validator->check('title')
            ->is_max(3, __('posts.title_missing'));

        $validator->check('slug')
            ->is_max(3, __('posts.slug_missing'))
            ->is_duplicate(__('posts.slug_duplicate'))
            ->not_regex('#^[0-9_-]+$#', __('posts.slug_invalid'));

        $validator->check('created')
            ->is_regex(
                '#^[0-9]{4}\-[0-9]{2}\-[0-9]{2} [0-9]{2}\:[0-9]{2}\:[0-9]{2}$#',
                __('posts.time_invalid')
            );

        if ($errors = $validator->errors()) {
            Input::flash();

            // Notify::error($errors);

            return Response::json([
                'id' => $id,
                'errors' => array_flatten($errors, [])
            ]);
        }

        if (empty($input['comments'])) {
            $input['comments'] = 0;
        }

        // If the user is a contributor or the description is empty, set the post status to draft
        if (Auth::contributor() || empty($input['description'])) {
            $input['status'] = 'draft';
        }

        if (Auth::demo()) {
            return Response::json([
                'notification' => __('global.demonstration')
            ]);
        } else {
            Post::update($id, $input);

            return Response::json([
                'id' => $id,
                'notification' => __('posts.updated')
            ]);
        }
    });

    /**
     * Add new post
     */
    Route::get('admin/posts/add', function () {
        $vars['token'] = Csrf::token();
        $vars['page'] = Registry::get('posts_page');

        $vars['categories'] = Category::dropdown();

        $vars['statuses'] = [
            'published' => __('global.published'),
            'draft' => __('global.draft'),
            'archived' => __('global.archived')
        ];

        return View::create('posts/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer')
            ->partial('editor', 'partials/editor');
    });

    Route::post('admin/posts/add', function () {
        $input = Input::get([
            'title',
            'slug',
            'description',
            'meta_description',
            'image',
            'videolink',
            'created',
            'category',
            'status',
            'comments'
        ]);

        // if there is no slug try and create one from the title
        if (empty($input['slug'])) {
            $input['slug'] = $input['title'];
        }

        // convert to ascii
        $input['slug'] = slug($input['slug']);

        // an array of items that we shouldn't encode - they're no XSS threat
        $dont_encode = ['description', 'meta_description'];

        foreach ($input as $key => &$value) {
            if (in_array($key, $dont_encode)) {
                continue;
            }

            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->add('duplicate', function ($str) {
            return Post::where('slug', '=', $str)->count() == 0;
        });

        $validator->check('title')
            ->is_max(3, __('posts.title_missing'));

        $validator->check('slug')
            ->is_max(3, __('posts.slug_missing'))
            ->is_duplicate(__('posts.slug_duplicate'))
            ->not_regex('#^[0-9_-]+$#', __('posts.slug_invalid'));

        if ($errors = $validator->errors()) {
            Input::flash();

            // Notify::error($errors);

            return Response::json([
                'id' => -1,
                'errors' => array_flatten($errors, [])
            ]);
        }

        if (empty($input['created'])) {
            $input['created'] = Date::mysql('now');
        }

        $user = Auth::user();

        $input['author'] = $user->id;

        if (empty($input['comments'])) {
            $input['comments'] = 0;
        }

        // If the user is a contributor or the description is empty, set the post status to draft
        if (Auth::contributor() || empty($input['description'])) {
            $input['status'] = 'draft';
        }




        if (Auth::demo()) {
            return Response::json([
                'notification' => __('global.demonstration')
            ]);
        } else {


            $post = Post::create($input);
            $id = $post->id;

            if (Auth::contributor()) {
                    Query::table(Base::table('notifications'))->insert([
                        'user_id' => $user->id,
                        'type' => 'post',
                        'related_id' => $id,  // The post ID
                        'created_at' =>  Date::mysql('now')  // Use date function to get current time
                    ]);
            }


            return Response::json([
                'id' => $id,
                'notification' => __('posts.created'),
                'redirect' => Uri::to('admin/posts/edit/' . $id)
            ]);
        }


    });

    /**
     * Delete post
     */
    Route::get('admin/posts/delete/(:num)', function ($id) {
        if (Auth::demo()) {
            Notify::error(__('global.demonstration'));
        } else {
            Post::find($id)->delete();
            Comment::where('post', '=', $id)->delete();

            Notify::success(__('posts.deleted'));
        }
            return Response::redirect('admin/posts');
        });
});
