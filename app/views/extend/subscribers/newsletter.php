<?php echo $header; ?>
<div class="mt-3">
    <h3><?php echo __('subscribers.newsletter'); ?></h3>
    <div class="row mt-3 mb-3">
        <div class="col">
            <form method="post" action="<?php echo Uri::to('admin/extend/subscribers/newsletter/send'); ?>" id="newsletter-form">
                <input name="token" type="hidden" value="<?php echo $token; ?>">
                <div class="mb-3">
                <label for="subject" class="form-subject">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5"></textarea>
            </div>
            </form>
        </div>
        <div class="sticky-sm-bottom bg-body row">
            <div class="col px-0 d-grid gap-2">
                <button id="send-newsletter" class="btn btn-success m-2"><?php echo __('subscribers.send_newsletter'); ?></button>
            </div>
            <div class="col px-0 d-grid gap-2">
                <?php echo Html::link('admin/extend/subscribers/', __('global.cancel'), [
                    'class' => 'btn btn-link btn-block fw-bold text-muted text-decoration-none m-2'
                ]); ?>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo asset('app/views/assets/js/pixelEditor/pixeleditor.min.css'); ?>">
<script src="<?php echo asset('app/views/assets/js/pixelEditor/jquery.pixeleditor.min.js'); ?>"></script>
<script>
    jQuery(document).ready(function($) {
        new PixelEditor('#message');
    });
</script>
<script>
    $(document).ready(function() {
        $('#send-newsletter').on('click', function() {
            // Serialize the form data
            var formData = $('#newsletter-form').serialize();

            // Send an AJAX POST request to your server
            $.ajax({
                type: 'POST',
                url: $('#newsletter-form').attr('action'),
                data: formData,
                dataType: 'json',
                success: function(data) {
                    if (data.errors) {
                        // Handle validation errors
                        var errorHtml = '<ul>';
                        $.each(data.errors, function(key, value) {
                            errorHtml += '<li>' + value + '</li>';
                        });
                        errorHtml += '</ul>';
                        showNotification(errorHtml, 'alert-danger');
                    } else if (data.notification) {
                        // Display success notification
                        showNotification(data.notification, 'alert-success');
                        // Reload the page after a successful submission
                        setTimeout(function() {
                            location.reload();
                        }, 5000);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    console.error(xhr.responseText);
                }
            });
        });
    });

    function showNotification(message, cssClass) {
        var notification = '<div class="alert ' + cssClass + ' alert-dismissible fade show">' + message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>';
        $('.notifications').html(notification);
    }
</script>
<?php echo $footer; ?>
