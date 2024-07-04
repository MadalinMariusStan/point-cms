<?php defined('App') or die('PointCMS'); ?>
<!-- user.tpl.php -->
<form action="<?php echo $this->admin_url('?page=user', true); ?>" method="post" enctype="multipart/form-data"
      class="py-4 needs-validation" novalidate>
    <div class="input-group mb-3">
        <label class="input-group-text" for="real_name"><?php echo $this->translate('real_name'); ?></label>
        <input type="text" id="real_name" name="real_name" placeholder="<?php echo $this->translate('placeholder_real_name'); ?>"
               value="<?php echo $this->esc($this->get('real_name')); ?>" class="form-control rounded-end" required>
        <div class="invalid-feedback">
            <?php echo $this->translate('error_real_name'); ?>
        </div>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="description"><?php echo $this->translate('description'); ?></label>
        <textarea rows="5" id="description" name="description" placeholder="<?php echo $this->translate('placeholder_description'); ?>"
                  class="form-control"><?php echo $this->esc($this->get('description')); ?></textarea>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="avatar"><?php echo $this->translate('avatar'); ?></label>
        <input type="file" id="avatar" name="avatar" class="form-control">
    </div>
    <div class="mb-3">
        <img id="avatar-preview" src="<?php echo $avatarURL; ?>" alt="<?php echo $this->translate('avatar'); ?>" class="img-thumbnail mt-2"
             style="max-width: 150px;">
    </div>
    <input type="hidden" name="token" value="<?php echo $this->token(); ?>">
    <button type="submit" name="save_profile" class="btn btn-primary"><?php echo $this->translate('save_profile'); ?></button>
</form>
<hr class="hr">
<form action="<?php echo $this->admin_url('?page=user', true); ?>" method="post" class="py-4 needs-validation"
      novalidate>
    <h3 class="monospace"><?php echo $this->translate('change_password'); ?></h3>
    <div class="input-group mb-3">
        <label class="input-group-text" for="old"><?php echo $this->translate('current_password'); ?></label>
        <input type="password" id="old" name="old" class="form-control rounded-end" required>
        <div class="invalid-feedback">
            <?php echo $this->translate('error_current_password'); ?>
        </div>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="new"><?php echo $this->translate('new_password'); ?></label>
        <input type="password" id="new" name="new" class="form-control rounded-end" required>
        <div class="invalid-feedback">
            <?php echo $this->translate('error_new_password'); ?>
        </div>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="confirm"><?php echo $this->translate('confirm_password'); ?></label>
        <input type="password" id="confirm" name="confirm" class="form-control rounded-end" required>
        <div class="invalid-feedback">
            <?php echo $this->translate('error_confirm_password'); ?>
        </div>
    </div>
    <?php echo $this->get_action('form'); ?>
    <input type="hidden" name="token" value="<?php echo $this->token(); ?>">
    <button type="submit" name="password" class="btn btn-primary"><?php echo $this->translate('change_password'); ?></button>
</form>

<script>
    $(document).ready(function () {
        $('#avatar').change(function () {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#avatar-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>
