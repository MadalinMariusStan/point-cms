<?php echo $header; ?>
<div class="d-flex justify-content-between align-items-center mt-3">
    <h3><?php echo __('errors.errors_log'); ?></h3>
    <button id="clear-errors-button" class="btn btn-danger btn-sm"><?php echo __('errors.clear'); ?></button>
</div>
<?php if (isset($notification)): ?>
    <div class="mt-3">
        <div class="alert alert-warning"><?php echo $notification; ?></div>
    </div>
<?php elseif (isset($sizeInfo)): ?>
    <div class="mt-3">
        <div class="alert alert-info"><?php echo $sizeInfo; ?></div>
    </div>
<?php endif; ?>

<div class="row mt-3">
    <div class="col-12">
        <?php if (!empty($errorLogContent)): ?>
            <pre class="rounded border p-2" style="min-height:350px;">
                <code class="sow-hl style-bootstrap d-block"><?php echo $errorLogContent; ?></code>
            </pre>
        <?php else: ?>
            <p>No error log content available.</p>
        <?php endif; ?>
    </div>
</div>
<script src="<?php echo asset('app/views/assets/js/sow-highlighter.min.js'); ?>"></script>

<script>
    $(document).ready(function () {
        $('#clear-errors-button').click(function () {
            // Send a GET request to clear the log file
            $.get('<?php echo Uri::to('admin/extend/tool/log/clear-errors'); ?>', function (response) {
                // Check the response for success or failure
                if (response.notification) {
                    // Display the success notification
                    $('.notifications').html('<div class="alert alert-success">' + response.notification + '</div>');
                    // Reload the page after 2 seconds
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                } else {
                    // Display an error message
                    $('.notifications').html('<div class="alert alert-danger">Failed to clear log file</div>');
                }
            })
                .fail(function (xhr, status, error) {
                    // Display an error message if the request fails
                    $('.notifications').html('<div class="alert alert-danger">Failed to clear log file: ' + error + '</div>');
                });
        });
    });
</script>
<?php echo $footer; ?>
