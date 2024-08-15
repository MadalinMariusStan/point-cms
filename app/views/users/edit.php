<?php echo $header; ?>
<?php if (Auth::admin() || Auth::me($user->id)) : ?>
    <div class="row">
        <div class="col">
            <h3 class="float-start mt-3 mb-3"><?php echo __('users.editing_user'); ?></h3>
        </div>
    </div>
    <form id="form-edit" method="post" action="<?php echo Uri::to('admin/users/edit/' . $user->id); ?>" novalidate autocomplete="off" enctype="multipart/form-data">
        <input name="token" type="hidden" value="<?php echo $token; ?>">
        <div class="card">
           <div class="card-body">
            <div class="d-flex m-2">
                <div class="flex-shrink-0">
                    <?php if ($user->image): ?>
                        <img id="show" src="<?php echo $user->image; ?>" alt="Avatar"
                             class="img-fluid avatar-md rounded-circle img-thumbnail" width="120" height="120">
                    <?php else: ?>
                        <img id="show" src="<?php echo asset('app/views/assets/img/no_avatar.png'); ?>" alt="Avatar"
                             class="img-fluid avatar-md rounded-circle img-thumbnail" width="120" height="120">
                    <?php endif; ?>
                    <br>
                    <input type="hidden" name="image" class="image" value="<?php echo $user->image; ?>">
                    <button type="button" data-image-toggle='image' class="btn btn-secondary mt-2 mb-3"><?php echo __('users.upload_image'); ?></button>
                </div>
                <div class="flex-grow-1 mt-5 ms-3">
                    <h5><?php echo $user->real_name; ?> - <small><?php echo __('users.' . $user->role); ?></small></h5>
                    <a href="mailto:<?php echo $user->email; ?>" class="mb-1 text-primary text-decoration-none">
                        <?php echo $user->email; ?></a>
                    <p class="text-secondary mb-0">
                        <?php echo $posts_count; ?> Posts - Created <?php echo relative_time_admin($user->created_at); ?>.
                    </p>
                </div>
            </div>
           </div>
        </div>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-real_name"><?php echo __('users.real_name'); ?></label>
            <?php echo Form::text('real_name', Input::previous('real_name', $user->real_name),
                [
                    'class' => 'form-control',
                    'id' => 'label-real_name'
                ]); ?>
        </div>
        <small class="form-text"><?php echo __('users.real_name_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-bio"><?php echo __('users.bio'); ?></label>
            <?php echo Form::textarea('bio', Input::previous('bio', $user->bio),
                [
                    'cols' => 20,
                    'class' => 'form-control',
                    'id' => 'label-bio'
                ]); ?>
        </div>
        <small class="form-text"><?php echo __('users.bio_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-status"><?php echo __('users.status'); ?></label>
            <?php echo Form::select('status', $statuses, Input::previous('status', $user->status),
                [
                    'class' => 'form-control',
                    'id' => 'label-status'
                ]); ?>
        </div>
        <small class="form-text"><?php echo __('users.status_explain'); ?></small>
        <?php if (Auth::admin()) : ?>
            <div class="input-group mt-3 mb-3">
                <label class="input-group-text" for="label-role"><?php echo __('users.role'); ?></label>
                <?php echo Form::select('role', $roles, Input::previous('role', $user->role),
                    [
                        'class' => 'form-select',
                        'id' => 'label-role'
                    ]); ?>
            </div>
            <small class="form-text"><?php echo __('users.role_explain'); ?></small>
        <?php endif; ?>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-username"><?php echo __('users.username'); ?></label>
            <?php echo Form::text('username', Input::previous('username', $user->username),
                [
                    'class' => 'form-control',
                    'id' => 'label-username'
                ]); ?>
        </div>
        <small class="form-text"><?php echo __('users.role_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-password"><?php echo __('users.password'); ?></label>
            <?php echo Form::password('password', [
                'class' => 'form-control',
                'id' => 'label-password'
            ]); ?>
        </div>
        <small class="form-text"><?php echo __('users.password_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-email"><?php echo __('users.email'); ?></label>
            <?php echo Form::text('email', Input::previous('email', $user->email), [
                'class' => 'form-control',
                'id' => 'label-email'
            ]); ?>
        </div>
        <small class="form-text"><?php echo __('users.email_explain'); ?></small>
        <div class="sticky-sm-bottom bg-body row">
            <div class="col px-0 d-grid gap-2">
                <?php echo Form::button(__('global.update'), [
                    'type' => 'submit',
                    'form' => 'form-edit',
                    'class' => 'btn btn-success m-2'
                ]); ?>
            </div>
            <div class="col px-0 d-grid gap-2">
            <?php echo Html::link('admin/users/delete/' . $user->id, __('global.delete'), [
                'form' => 'form-edit',
                'class' => 'btn btn-danger delete m-2'
            ]); ?>
            </div>
            <div class="col px-0 d-grid gap-2">
                <?php echo Html::link('admin/users/', __('global.cancel'), [
                    'data-bs-toggle' => 'tooltip',
                    'class' => 'btn btn-link btn-block fw-bold text-muted text-decoration-none m-2'
                ]); ?>
            </div>
        </div>
    </form>
<?php else : ?>
    <p class="mt-5 ms-5 me-5 lead">
        You do not have the required privileges to modify this users information, you must be
        an Administrator. Please contact the Administrator of the site if you are supposed to have
        these privileges.
    </p>
    <br>
    <div class="d-grid gap-2 col-6 mx-auto">
        <a class="btn btn-primary" href="<?php echo Uri::to('admin/users'); ?>">Go back</a>
    </div>
<?php endif; ?>
<?php echo $footer; ?>