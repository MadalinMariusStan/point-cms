<?php echo $header; ?>
<section class="mt-5">
    <div class="mt-3">
        <h1>Your first account</h1>
        <p>Oh, we're so tantalisingly close! All we need now is a username and password to log in to the admin area with.</p>
        <?php echo Notify::read(); ?>
    </div>
    <form class="mt-3" method="post" action="<?php echo uri_to('account'); ?>" autocomplete="off">
        <fieldset>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="username">Username</label>
                <div class="col-sm-10">
                    <input tabindex="1" id="username" class="form-control" name="username"
                           value="<?php echo Input::previous('username', 'admin'); ?>" placeholder="Your Username">
                </div>
                <span class="form-text">You use this to log in.</span>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label" for="email">Email address</label>
                <div class="col-sm-10">
                    <input tabindex="2" id="email" class="form-control" type="email" name="email"
                           value="<?php echo Input::previous('email'); ?>" placeholder="Your Email">
                </div>
                <span class="form-text">Needed if you can’t log in.</span>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input class="form-control" tabindex="3" id="password" name="password" type="password"
                           value="<?php echo Input::previous('password'); ?>" oninput="updatePasswordStrength()" placeholder="Password">
                </div>
                <div class="col-sm-10 offset-sm-2 mt-2">
                    <div class="progress">
                        <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Make sure to pick a secure password.</div>
                    </div>
                </div>
            </div>
        </fieldset>
        <section class="mt-3">
            <a href="<?php echo uri_to('metadata'); ?>" class="btn btn-secondary">&laquo; Back</a>
            <button type="submit" class="float-end btn btn-primary">Complete</button>
        </section>
    </form>
</section>
<?php echo $footer; ?>

<script>
    function updatePasswordStrength() {
        const password = document.getElementById('password').value;
        const strengthBar = document.getElementById('password-strength-bar');
        let strength = 0;

        if (password.length >= 8) strength += 20;
        if (/[A-Z]/.test(password)) strength += 20;
        if (/[a-z]/.test(password)) strength += 20;
        if (/[0-9]/.test(password)) strength += 20;
        if (/[^A-Za-z0-9]/.test(password)) strength += 20;

        strengthBar.style.width = strength + '%';
        strengthBar.ariaValueNow = strength;

        if (strength === 0) {
            strengthBar.classList.remove('bg-success', 'bg-warning', 'bg-danger');
            strengthBar.textContent = 'Make sure to pick a secure password.';
        } else if (strength <= 40) {
            strengthBar.classList.remove('bg-success', 'bg-warning');
            strengthBar.classList.add('bg-danger');
            strengthBar.textContent = 'Weak';
        } else if (strength <= 80) {
            strengthBar.classList.remove('bg-success', 'bg-danger');
            strengthBar.classList.add('bg-warning');
            strengthBar.textContent = 'Moderate';
        } else {
            strengthBar.classList.remove('bg-warning', 'bg-danger');
            strengthBar.classList.add('bg-success');
            strengthBar.textContent = 'Strong';
        }
    }
</script>
