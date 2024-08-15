</div>
</div>
</div>
<?php if (Auth::user()): ?>
    <footer class="pt-5 my-5 text-muted">
        <div class="container text-center">
            <small class="fw-light pb-3"><?php echo __('global.motto'); ?> . <?php echo __('global.powered', VERSION); ?></small>
        </div>
    </footer>
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModal">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <button id="back-to-top" class="btn btn-primary back-to-top" role="button"><svg class="bi" fill="white" width="16" height="16" ><use href="#bi-chevron-up"></use></svg></button>
    <script src="<?php echo asset('app/views/assets/js/save.js'); ?>"></script>
    <script>
        $(document).ready(function () {
            // Check to see if the window is top if not then display button
            $(window).scroll(function () {
                // Show button after 100px
                var showAfter = 100;
                if ($(this).scrollTop() > showAfter) {
                    $('.back-to-top').fadeIn();
                } else {
                    $('.back-to-top').fadeOut();
                }
            });
            // Click event to scroll to top
            $('.back-to-top').click(function () {
                $('html, body').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });

            // Handle delete click
            $('.delete').on('click', function (evt) {
                evt.preventDefault();
                var source = $(this);
                // Show the confirmation modal
                $('#confirmationModal').modal('show');
                // Handle the confirmation
                $('#confirmDelete').on('click', function () {
                    // Here, you can perform the delete action or navigate to the delete URL
                    if (source.is('[type="submit"]')) {
                        // Handle form submission
                        $('.delete').off('click');
                        source.click();
                    } else if (source.is('a')) {
                        // Handle link redirection
                        window.location.href = source.attr('href');
                    }
                    // Close the modal
                    $('#confirmationModal').modal('hide');
                });
            });
            // Image Manager
            $(document).on('click', '[data-image-toggle=\'image\']', function (e) {
                var element = this;
                $('#modal-image').remove();
                $.ajax({
                    url: '<?php echo Uri::to('admin/filemanager'); ?>',
                    dataType: 'html',
                    beforeSend: function () {
                        $(element).prop('disabled', true).addClass('loading');
                    },
                    complete: function () {
                        $(element).prop('disabled', false).removeClass('loading');
                    },
                    success: function (html) {
                        $('body').append('<div id="modal-image" class="modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">' + html + '</div>');
                        $('#modal-image').modal('show');
                    }
                });
            });
        });
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    <script>
        $(document).ready(function () {
            // Handle delete click for the install folder
            $('#deleteInstall').on('click', function (evt) {
                evt.preventDefault();
                var source = $(this);
                // Show the confirmation modal
                $('#confirmationModal').modal('show');

                // Handle the confirmation click
                $('#confirmDelete').off('click').on('click', function () {
                    // Perform the delete action via AJAX
                    $.ajax({
                        url: '<?php echo Uri::to('admin/install/delete'); ?>',
                        type: 'POST',
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                // Display the success notification
                                $('.notifications').html('<div class="alert alert-success">' + response.message + '</div>');
                                // Reload the page after 2 seconds
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            } else {
                                // Display an error message
                                $('.notifications').html('<div class="alert alert-danger">' + response.message + '</div>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            $('.notifications').html('<div class="alert alert-danger">An error occurred while trying to remove the installation folder.</div>');
                        }
                    });
                    // Close the modal
                    $('#confirmationModal').modal('hide');
                });
            });
        });

        // Function to check for new notifications and update the badge
        function checkForNewNotificationsAndUpdateBadge() {
            $.ajax({
                url: '<?php echo Uri::to('admin/notifications/notifications'); ?>', // Updated endpoint
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    var totalNotifications = data.newNotificationsCount;

                    if (totalNotifications > 0) {
                        // If there are new notifications, update the badge and show it
                        updateBadge(totalNotifications);
                        showBadge();
                    } else {
                        // If no new notifications, hide the badge
                        hideBadge();
                    }
                },
                error: function (error) {
                    console.error('Error checking for new notifications:', error);
                }
            });
        }

        // Function to update the badge count
        function updateBadge(count) {
            var badge = document.getElementById('newNotificationsBadge');
            badge.textContent = count;
        }

        // Function to show the badge
        function showBadge() {
            var badge = document.getElementById('newNotificationsBadge');
            badge.style.display = 'block';
        }

        // Function to hide the badge
        function hideBadge() {
            var badge = document.getElementById('newNotificationsBadge');
            badge.style.display = 'none';
        }

        // Initial check for new notifications and badge visibility
        checkForNewNotificationsAndUpdateBadge();

        // Periodically check for new notifications (e.g., every 60 seconds)
        setInterval(checkForNewNotificationsAndUpdateBadge, 10000); // 10 seconds for demonstration

    </script>

<?php endif; ?>
</body>
</html>