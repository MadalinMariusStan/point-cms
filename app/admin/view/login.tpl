<?php defined('App') or die('PointCMS'); ?>
<form action="<?php echo $this->admin_url('', true); ?>" method="post" class="py-4 needs-validation" novalidate>
    <h2 class="monospace"><?php echo $this->translate('login_heading'); ?></h2>
    <div class="mb-3">
        <label for="username" class="form-label"><?php echo $this->translate('username'); ?></label>
        <div class="input-group has-validation">
            <span class="input-group-text bg-body-secondary border border-0" id="inputGroupPrepend"><i class="bi bi-person"></i></span>
            <input type="text" id="username" name="username" class="form-control bg-body-secondary border border-0" required>
            <div class="invalid-feedback">
                <?php echo $this->translate('username_required'); ?>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label"><?php echo $this->translate('password'); ?></label>
        <div class="input-group has-validation">
            <span class="input-group-text bg-body-secondary border border-0" id="inputGroupPrepend"><i class="bi bi-lock"></i></span>
            <input type="password" id="password" name="password" class="form-control bg-body-secondary border border-0" required>
            <div class="invalid-feedback">
                <?php echo $this->translate('password_required'); ?>
            </div>
        </div>
    </div>
    <?php echo $this->get_action('form'); ?>
    <input type="hidden" name="token" value="<?php echo $this->token(); ?>">
    <button type="submit" name="login" class="btn btn-primary w-100"><?php echo $this->translate('login_button'); ?></button>
</form>
<script>
    (() => {
        'use strict';

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation');

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
