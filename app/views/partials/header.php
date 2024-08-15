<!doctype html>
<html id="admin" lang="<?php echo str_replace('_', '-', Config::app('language')); ?>" data-bs-theme="<?php echo Config::meta('admin_theme'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="light dark" />
    <!-- Title -->
    <title><?php echo __('global.manage'); ?> - <?php echo Config::meta('site_name'); ?></title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo asset('assets/favicon/apple-touch-icon.png'); ?>"/>
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo asset('assets/favicon/favicon-32x32.png'); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo asset('assets/favicon/favicon-16x16.png'); ?>">
    <!-- Boostrap Css -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/bootstrap.min.css'); ?>">
    <!-- Admin Extra Css -->
    <link rel="stylesheet" href="<?php echo asset('app/views/assets/css/admin.css'); ?>">
    <!-- Boostrap Js -->
    <script src="<?php echo asset('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    <!-- Jquery Js -->
    <script src="<?php echo asset('assets/js/jquery.min.js'); ?>"></script>
</head>
<body class="min-vh-100">
<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
    <symbol id="bi-person-circle" viewBox="0 0 16 16">
        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
        <path fill-rule="evenodd"
              d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
    </symbol>
    <symbol id="bi-eye" viewBox="0 0 16 16">
        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
    </symbol>
    <symbol id="bi-power" viewBox="0 0 16 16">
        <path d="M7.5 1v7h1V1z"/>
        <path d="M3 8.812a5 5 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812"/>
    </symbol>
    <symbol id="bi-chat-left-text" viewBox="0 0 16 16">
        <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
        <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6m0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/>
    </symbol>
    <symbol id="bi-pencil-square" viewBox="0 0 16 16">
        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
    </symbol>
    <symbol id="bi-file-earmark" viewBox="0 0 16 16">
        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
    </symbol>
    <symbol id="bi-people" viewBox="0 0 16 16">
        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
    </symbol>
    <symbol id="bi-people-fill" viewBox="0 0 16 16">
        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
    </symbol>
    <symbol id="bi-envelope-open" viewBox="0 0 16 16">
        <path d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.817l5.75 3.45L8 8.917l1.25.75L15 6.217V5.4a1 1 0 0 0-.53-.882zM15 7.383l-4.778 2.867L15 13.117zm-.035 6.88L8 10.082l-6.965 4.18A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738ZM1 13.116l4.778-2.867L1 7.383v5.734ZM7.059.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765z"/>
    </symbol>
    <symbol id="bi-chevron-up" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708z"/>
    </symbol>
    <symbol id="bi-funnel" viewBox="0 0 16 16">
        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2z"/>
    </symbol>
    <symbol id="bi-trash" viewBox="0 0 16 16">
        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
    </symbol>
    <symbol id="bi-envelope-arrow-down" viewBox="0 0 16 16">
        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4.5a.5.5 0 0 1-1 0V5.383l-7 4.2-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h5.5a.5.5 0 0 1 0 1H2a2 2 0 0 1-2-1.99zm1 7.105 4.708-2.897L1 5.383zM1 4v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1"/>
        <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.354-1.646a.5.5 0 0 1-.722-.016l-1.149-1.25a.5.5 0 1 1 .737-.676l.28.305V11a.5.5 0 0 1 1 0v1.793l.396-.397a.5.5 0 0 1 .708.708z"/>
    </symbol>
    <symbol id="bi-send" viewBox="0 0 16 16">
        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
    </symbol>
    <symbol id="bi-notification" viewBox="0 0 24 24">
        <path d="M20 17h2v2H2v-2h2v-7a8 8 0 1116 0v7zm-2 0v-7a6 6 0 10-12 0v7h12zm-9 4h6v2H9v-2z"></path>
    </symbol>
    <symbol id="bi-clock" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
    </symbol>
    <symbol id="bi-app" viewBox="0 0 24 24" xml:space="preserve">
    <path d="M9.453,14.183H1.192C0.535,14.183,0,13.648,0,12.991V1.192C0,0.535,0.535,0,1.192,0h8.261c0.657,0,1.192,0.535,1.192,1.192v11.799C10.645,13.648,10.111,14.183,9.453,14.183z" fill="#54c0eb"/>
        <path d="M9.453,24H1.192C0.535,24,0,23.465,0,22.808V18.516c0-0.657,0.535-1.192,1.192-1.192h8.261c0.657,0,1.192,0.535,1.192,1.192v4.292C10.645,23.465,10.111,24,9.453,24z" fill="#f8b64c"/>
        <path d="M22.808,24H14.547c-0.657,0-1.192-0.535-1.192-1.192V10.05c0-0.657,0.535-1.192,1.192-1.192h8.261c0.657,0,1.192,0.535,1.192,1.192v12.758C24,23.465,23.465,24,22.808,24z" fill="#f1543f"/>
        <path d="M22.808,6.193H14.547c-0.657,0-1.192-0.535-1.192-1.192V1.192C13.355,0.535,13.89,0,14.547,0h8.261C23.465,0,24,0.535,24,1.192v3.808C24,5.658,23.465,6.193,22.808,6.193z" fill="#5ecb58"/>
</symbol>
    <symbol id="bi-heart-fill" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314"/>
    </symbol>
    <symbol id="bi-exclamation-circle" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
    </symbol>
    <svg id="bi-three-dots-vertical" viewBox="0 0 16 16">
        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
    </svg>
</svg>

<?php if (Auth::user()): ?>
    <header class="p-3 border-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
                    <nav class="navbar d-flex px-0 py-1">
                        <?php $page = in_array(Config::meta('dashboard_page'), ['panel', 'pages', 'posts']) ? Config::meta('dashboard_page') : 'panel'; ?>
                        <a href="<?php echo Uri::to('admin/' . $page); ?>"
                           class="text-body-emphasis text-decoration-none mr-3">
                            <svg width="24" height="24" role="img"><use href="#bi-app"></use></svg>
                        </a>
                        <ul class="navbar-nav flex-row">
                            <a href="<?php echo Uri::to('admin/posts/add'); ?>" type="button"
                               class="btn btn-outline-primary ms-3 me-1 pt-2 <?php if (Uri::current() === "admin/posts/add" || Uri::current() === "admin/posts/edit/" . substr(Uri::current(), 17)) {
                                   echo 'd-none';
                               } ?>">
                                Create Post
                            </a>
                            <li class="ms-3 me-4 pt-2">
                                <a class="text-body-emphasis" href="<?php echo Uri::to('admin/notifications'); ?>">
                                    <span class="position-relative">
                                        <span id="newNotificationsBadge"
                                              class="position-absolute top-0 start-90 translate-middle badge rounded-pill bg-danger"
                                              style="display: none;">0</span>
                                        <svg width="24" height="24"role="img" aria-labelledby="title"><title id="title">Notifications</title><use href="#bi-notification"></use></svg>
                                    </span>
                                </a>
                            </li>
                            <div id="dropdown-menu" class="dropdown keep-open">
                                <img src="<?php echo avatar(); ?>" alt="<?php echo user_name(); ?>" id="dropdown" class="dropdown-toggle border avatar-sm me-2" data-bs-toggle="dropdown">
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item"
                                           href="<?php echo Uri::to('admin/users/edit/' . user_id()); ?>">
                                            <svg class="bi"><use href="#bi-person-circle"></use></svg> <?php echo user_name(); ?>
                                        </a>
                                    </li>
                                    <hr class="dropdown-divider">
                                    <li>
                                        <?php echo Html::link('/', '<svg class="bi"><use href="#bi-eye"></use></svg> ' . __('global.visit_your_blog'), ['class' => 'dropdown-item', 'target' => '_blank']); ?>
                                    </li>
                                    <hr class="dropdown-divider">
                                    <?php if (Auth::admin() || Auth::demo()): ?>
                                        <?php $menu = ['categories', 'comments', 'menu', 'pages', 'posts', 'users', 'extend']; ?>
                                    <?php else: ?>
                                        <?php $menu = ['posts']; ?>
                                    <?php endif; ?>
                                    <?php foreach ($menu as $url): ?>
                                        <li>
                                            <a class="dropdown-item <?php if (strpos(Uri::current(), $url) !== false) {
                                                echo 'active';
                                            } ?>" href="<?php echo Uri::to('admin/' . $url); ?>">
                                                <?php echo ucfirst(__($url . '.' . $url)); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li class="logout">
                                        <?php echo Html::link('admin/logout', '<svg class="bi"><use href="#bi-power"></use></svg> ' . __('global.logout'), ['class' => 'dropdown-item text-danger']); ?>
                                    </li>
                                </ul>

                            </div>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
<?php endif; ?>
<div class="container p-3 m-auto flex-column min-vh-100">
    <div class="row">
        <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12 main">
            <?php echo Notify::read(); ?>
            <div class="notifications"></div>

