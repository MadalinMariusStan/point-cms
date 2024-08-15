<?php echo $header; ?>
<section class="mt-5">
    <?php echo Notify::read(); ?>
    <h1 class="text-center">The script is already installed</h1>
    <div class="d-grid gap-2 col-6 mt-3 mx-auto">
        <a href="../admin" class="btn btn-primary">Visit your admin panel</a>
    </div>
</section>
<?php echo $footer; ?>
