<?php echo $header; ?>
<div id="alert"></div>
<section class="mt-5 p-5">
    <h1 class="text-center">Install complete!</h1>
    <?php if ($htaccess): ?>
        <p class="code">We could not write the <code>.htaccess</code> file for you. Please copy the contents below and
            create a <code>.htaccess</code> file in your blog script root folder.<br>
            <textarea id="htaccess" class="form-control" rows="10"><?php echo htmlspecialchars($htaccess); ?></textarea>
        </p>
        <script>document.getElementById('htaccess').select();</script>
    <?php endif; ?>
    <div class="d-grid gap-2 col-6 mt-3 mx-auto">
        <a href="<?php echo $admin_uri; ?>" class="btn btn-primary">Visit your admin panel</a>
    </div>
</section>
<?php echo $footer; ?>
