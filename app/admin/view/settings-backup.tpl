<?php defined('App') or die('PointCMS'); ?>
<form action="<?php echo $this->admin_url('?page=settings', true); ?>" method="post" class="py-4 needs-validation" novalidate>
    <div class="input-group mb-3">
        <label class="input-group-text" for="sitename"><?php echo $this->translate('site_name'); ?></label>
        <input type="text" id="sitename" name="sitename" placeholder="<?php echo $this->translate('site_name'); ?>" value="<?php echo $this->get('sitename'); ?>" class="form-control rounded-end" required>
        <div class="invalid-feedback"><?php echo $this->translate('error_sitename'); ?></div>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="title"><?php echo $this->translate('subtitle'); ?></label>
        <input type="text" id="title" name="title" placeholder="<?php echo $this->translate('site_subtitle'); ?>" value="<?php echo $this->get('title'); ?>" class="form-control">
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="keywords"><?php echo $this->translate('keywords'); ?></label>
        <input type="text" id="keywords" name="keywords" placeholder="<?php echo $this->translate('keywords'); ?>" value="<?php echo $this->get('keywords'); ?>" class="form-control">
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="descr"><?php echo $this->translate('description'); ?></label>
        <textarea rows="10" id="descr" name="descr" placeholder="<?php echo $this->translate('site_description'); ?>" class="form-control"><?php echo $this->esc($this->get('descr')); ?></textarea>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="email"><?php echo $this->translate('email'); ?></label>
        <input type="email" id="email" name="email" placeholder="<?php echo $this->translate('email_placeholder'); ?>" value="<?php echo $this->esc($this->get('email')); ?>" class="form-control">
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="url"><?php echo $this->translate('site_url'); ?></label>
        <input type="url" id="url" name="url" placeholder="<?php echo $this->translate('site_url_placeholder'); ?>" value="<?php echo $this->esc($this->get('url')); ?>" class="form-control rounded-end" required>
        <div class="invalid-feedback"><?php echo $this->translate('error_site_url'); ?></div>

    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="admin"><?php echo $this->translate('admin_url'); ?></label>
        <input type="text" id="admin" name="admin" placeholder="example/<?php echo bin2hex(random_bytes(3)); ?>/admin" value="<?php echo $this->admin_url(); ?>" class="form-control rounded-end" required>
        <div class="invalid-feedback"><?php echo $this->translate('error_admin_url'); ?></div>
    </div>
    <!-- Theme Selection -->
    <div class="input-group mb-3">
        <label class="input-group-text" for="admin_theme"><?php echo $this->translate('admin_theme'); ?></label>
        <select id="admin_theme" name="admin_theme" class="form-select">
            <option value="light"<?php echo ($this->get('admin_theme') === 'light' ? ' selected' : ''); ?>><?php echo $this->translate('light'); ?></option>
            <option value="dark"<?php echo ($this->get('admin_theme') === 'dark' ? ' selected' : ''); ?>><?php echo $this->translate('dark'); ?></option>
            <option value="auto"<?php echo ($this->get('admin_theme') === 'auto' ? ' selected' : ''); ?>><?php echo $this->translate('auto'); ?></option>
        </select>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="maintenance"><?php echo $this->translate('enable_maintenance'); ?></label>
        <select id="maintenance" name="maintenance" class="form-select">
            <option value="true"<?php echo $this->get('maintenance') ? ' selected' : ''; ?>><?php echo $this->translate('yes'); ?></option>
            <option value="false"<?php echo $this->get('maintenance') ? '' : ' selected'; ?>><?php echo $this->translate('no'); ?></option>
        </select>
    </div>
    <!-- Begin language selection dropdown -->
    <div class="input-group mb-3">
        <label class="input-group-text" for="lang"><?php echo $this->translate('language_selection'); ?></label>
        <select id="lang" name="lang" class="form-select">
            <?php
            $availableLanguages = $this->getAvailableLanguages();
            $currentLanguage = $this->get('lang');
            foreach ($availableLanguages as $code => $name): ?>
            <option value="<?php echo htmlspecialchars($code); ?>"
            <?php if ($currentLanguage === $code) echo 'selected'; ?>>
            <?php echo htmlspecialchars($name); ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <!-- End language selection dropdown -->
    <input type="hidden" name="token" value="<?php echo $this->token(); ?>">
    <button type="submit" name="save" class="btn btn-primary"><?php echo $this->translate('save_changes'); ?></button>
</form>




