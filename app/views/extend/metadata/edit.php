<?php echo $header; ?>
    <form id="form-edit" method="post" action="<?php echo Uri::to('admin/extend/metadata'); ?>" novalidate>
        <input name="token" type="hidden" value="<?php echo $token; ?>">
        <legend><?php echo __('metadata.admin'); ?></legend>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-dashboard_page"><?php echo __('metadata.dashboard_page'); ?></label>
            <?php echo Form::select('dashboard_page', $dashboard_page_options,
                Input::previous('dashboard_page', $meta['dashboard_page']), [
                    'class' => 'form-select',
                    'id' => 'label-dashboard_page',
                    'placeholder' => __('metadata.dashboard_page_explain')
                ]); ?>
        </div>
        <!-- New Admin Theme Selection -->
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-admin_theme"><?php echo __('metadata.admin_theme'); ?></label>
            <?php echo Form::select('admin_theme', [
                'light' => 'Light',
                'dark' => 'Dark',
                'auto' => 'Auto'
            ], Input::previous('admin_theme', $meta['admin_theme']), [
                'class' => 'form-select',
                'id' => 'label-admin_theme'
            ]); ?>
        </div>
        <small class="form-text"><?php echo __('metadata.admin_theme_explain'); ?></small>

        <legend><?php echo __('metadata.site'); ?></legend>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-site_name"><?php echo __('metadata.site_name'); ?></label>
            <?php echo Form::text('site_name', Input::previous('site_name', $meta['site_name']),
                [
                    'class' => 'form-control',
                    'id' => 'label-site_name'
                ]); ?>
        </div>
        <small class="form-text"><?php echo __('metadata.site_name_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-site_description"><?php echo __('metadata.site_description'); ?></label>
            <?php echo Form::textarea('description', Input::previous('description', $meta['description']), [
                'class' => 'form-control',
                'id' => 'label-site_description',
                'placeholder' => __('metadata.site_description_explain')
            ]); ?>
        </div>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-tumblr"><?php echo __('metadata.site_email'); ?></label>
            <?php echo Form::text('site_email', Input::previous('site_email', $meta['site_email']),
                [
                    'class' => 'form-control',
                    'id' => 'label-site_email'
                ]); ?>
        </div>
        <small class="form-text"><?php echo __('metadata.site_email_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-all_posts"><?php echo __('metadata.cookie'); ?></label>
            <?php $checked = Input::previous('cookie', $meta['cookie']) ? ' checked' : ''; ?>
            <?php echo Form::checkbox('cookie', 1, $checked, [
                'class' => 'form-check-input',
                'id' => 'label-cookie',
                'placeholder' => __('metadata.cookie')
            ]); ?>
        </div>
        <small class="form-text"><?php echo __('metadata.cookie_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-cookie_policy_page"><?php echo __('metadata.cookie_policy_page'); ?></label>
            <?php echo Form::select('cookie_policy_page', $pages, Input::previous('posts_page', $meta['cookie_policy_page']), [
                'class' => 'form-select',
                'id' => 'label-cookie_policy_page',
                'placeholder' => __('metadata.cookie_policy_page')
            ]); ?>
        </div>
        <small class="form-text"><?php echo __('metadata.cookie_policy_page_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-all_posts"><?php echo __('metadata.maintenance'); ?></label>
            <?php $checked = Input::previous('maintenance', $meta['maintenance']) ? ' checked' : ''; ?>
            <?php echo Form::checkbox('maintenance', 1, $checked, [
                'class' => 'form-check-input',
                'id' => 'label-maintenance',
                'placeholder' => __('metadata.maintenance')
            ]); ?>
        </div>
        <small class="form-text"><?php echo __('metadata.maintenance_explain'); ?></small>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-postspage"><?php echo __('metadata.postspage'); ?></label>
            <?php echo Form::select('posts_page', $pages, Input::previous('posts_page', $meta['posts_page']), [
                'class' => 'form-select',
                'id' => 'label-postspage',
                'placeholder' => __('metadata.postspage_explain')
            ]); ?>
        </div>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-posts_per_page"><?php echo __('metadata.posts_per_page'); ?></label>
            <?php echo Form::input('range', 'posts_per_page', Input::previous('posts_per_page', $meta['posts_per_page']),
                ['min' => 1,
                    'max' => 25,
                    'class' => 'form-control',
                    'id' => 'label-posts_per_page',
                    'placeholder' => __('metadata.posts_per_page_explain')
                ]); ?>
            <span class="input-group-text visible"
                  id="posts_per_page_number"><?php echo $meta['posts_per_page']; ?></span>
        </div>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-all_posts"><?php echo __('metadata.show_all_posts'); ?></label>
            <?php $checked = Input::previous('show_all_posts', $meta['show_all_posts']) ? ' checked' : ''; ?>
            <?php echo Form::checkbox('show_all_posts', 1, $checked, [
                'class' => 'form-check-input',
                'id' => 'label-show_all_posts',
                'placeholder' => __('metadata.show_all_posts_explain')
            ]); ?>
        </div>
        <legend><?php echo __('metadata.comment_settings'); ?></legend>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-auto_published_comments"><?php echo __('metadata.auto_publish_comments'); ?></label>
            <?php $checked = Input::previous('auto_published_comments', $meta['auto_published_comments']) ? ' checked'  : ''; ?>
            <?php echo Form::checkbox('auto_published_comments', 1, $checked, [
                'class' => 'form-check-input',
                'id' => 'label-auto_published_comments',
                'placeholder' => __('metadata.auto_publish_comments_explain')
            ]); ?>
        </div>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-comment_notifications"><?php echo __('metadata.comment_notifications'); ?></label>
            <?php $checked = Input::previous('comment_notifications', $meta['comment_notifications']) ? ' checked' : ''; ?>
            <?php echo Form::checkbox('comment_notifications', 1, $checked, [
                'class' => 'form-check-input',
                'id' => 'label-comment_notifications',
                'placeholder' => __('metadata.comment_notifications_explain')
            ]); ?>
        </div>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text"
                   for="label-comment_moderation_keys"><?php echo __('metadata.comment_moderation_keys'); ?></label>
            <?php echo Form::textarea('comment_moderation_keys',
                Input::previous('comment_moderation_keys', $meta['comment_moderation_keys']), [
                    'class' => 'form-control',
                    'id' => 'label-comment_moderation_keys',
                    'placeholder' => __('metadata.comment_moderation_keys_explain')
                ]); ?>
        </div>
        <legend><?php echo __('metadata.theme_settings'); ?></legend>
        <div class="input-group mt-3 mb-3">
            <label class="input-group-text" for="label-theme"><?php echo __('metadata.current_theme'); ?></label>
            <select class="form-select" id="label-theme" name="theme">
                <?php foreach ($themes as $theme => $about): ?>
                    <?php $selected = (Input::previous('theme', $meta['theme']) == $theme) ? ' selected' : ''; ?>
                    <option value="<?php echo $theme; ?>" <?php echo $selected; ?>>
                        <?php echo $about['name']; ?> by <?php echo $about['author']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <small class="form-text"><?php echo __('metadata.current_theme_explain', 'Your current theme.'); ?></small>
        <div class="sticky-sm-bottom bg-body row">
            <div class="col px-0 d-grid gap-2">
                <?php echo Form::button(__('global.save'), [
                    'form' => 'form-edit',
                    'type' => 'submit',
                    'class' => 'btn btn-success m-2'
                ]); ?>
            </div>
            <div class="col px-0 d-grid gap-2">
                <?php echo Html::link('admin/extend', __('global.cancel'), [
                    'class' => 'btn btn-link btn-block fw-bold text-muted text-decoration-none m-2'
                ]); ?>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        // Show posts per page count
        $(document).ready(function () {
            $('input[name="posts_per_page"]').change(function () {
                $('#posts_per_page_number').text($(this).val());
            });
        });
    </script>
<?php echo $footer; ?>


