<link rel="stylesheet" href="<?php echo asset('app/views/assets/js/pixelEditor/pixeleditor.min.css'); ?>">
<script src="<?php echo asset('app/views/assets/js/pixelEditor/jquery.pixeleditor.min.js'); ?>"></script>
<script>
    jQuery(document).ready(function($) {
        new PixelEditor('#description');
    });
</script>