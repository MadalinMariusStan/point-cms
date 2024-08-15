<?php echo $header; ?>
<div class="mt-3">
    <h3 class="float-start"><?php echo __('subscribers.subscribers'); ?></h3>
    <?php if ($subscribers->count): ?>
        <div class="float-end">
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <svg width="16" height="16" role="img">
                        <use href="#bi-three-dots-vertical"></use>
                    </svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item"
                           href="<?php echo Uri::to('admin/extend/subscribers/newsletter'); ?>"><?php echo __('subscribers.send_newsletter'); ?></a>
                    </li>
                    <li><a id="export-subscribers-button" class="dropdown-item"
                           href="<?php echo Uri::to('admin/extend/subscribers/newsletter'); ?>"><?php echo __('subscribers.export_email'); ?></a>
                    </li>
                    <li><a id="reset-subscribers-button" class="dropdown-item text-danger"
                           href="#"><?php echo __('subscribers.reset_subscribers'); ?></a></li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="clearfix"></div>
<div class="row mt-3 mb-3">
    <div class="col">
        <?php if ($subscribers->count): ?>
            <table class="table">
                <thead>
                <tr>
                    <th><?php echo __('subscribers.email'); ?></th>
                    <th class="text-end"><?php echo __('subscribers.date'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($subscribers->results as $subscriber): ?>
                    <tr>
                        <td><?php echo $subscriber->email; ?></td>
                        <td class="text-end"><?php echo $subscriber->created_at; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <nav class="mt-3" aria-label="Page navigation">
                    <ul class="pagination">
                        <?php echo $subscribers->links(); ?>
                    </ul>
                </nav>
            </div>
        <?php else : ?>
            <p class="mt-3 text-center">
                <?php echo __('global.text_no_results'); ?>
            </p>
        <?php endif; ?>
    </div>
</div>
<script>
    document.getElementById('export-subscribers-button').addEventListener('click', function () {
        // Make an Ajax request to the export route
        $.ajax({
            url: '<?php echo Uri::to('admin/extend/subscribers/export-csv'); ?>', // Replace with the actual URL of your reset script
            method: 'GET',
            success: function (data) {
                // Create a temporary anchor element to trigger the file download
                var a = document.createElement('a');
                a.href = 'data:application/csv;charset=utf-8,' + encodeURIComponent(data);
                a.target = '_blank';
                a.download = 'email_addresses.csv';
                a.click();
            },
            error: function () {
                alert('Error exporting email addresses.');
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#reset-subscribers-button').click(function () {
            $.ajax({
                type: 'POST',
                url: '<?php echo Uri::to('admin/extend/subscribers/reset'); ?>', // Replace with the actual URL of your reset script
                success: function (response) {
                    if (response.notification) {
                        showSuccessAlert(response.notification);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    } else {
                        showErrorAlert('An error occurred while resetting subscribers.');
                    }
                }
            });

            function showSuccessAlert(message) {
                $('.notifications').html('<div class="alert alert-success">' + message + '</div>');
            }

            function showErrorAlert(message) {
                $('.notifications').html('<div class="alert alert-danger">' + message + '</div>');
            }
        });
    });
</script>
<?php echo $footer; ?>
