<?php theme_include('header'); ?>
    <div class="col-xl-10 mx-auto">
        <h1 class="fw-light">Contact Us</h1>
        <!-- Notifications -->
        <div class="notify"></div>
        <div class="mt-5">
            <form method="post" action="<?php echo current_url(); ?>" id="contact-form">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label for="contact-subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="contact-subject" name="contact-subject" type="text" placeholder="Subject" value="<?php echo Input::previous('contact-subject'); ?>">
                    </div>
                    <div class="col-sm-6">
                        <label for="contact-name" class="form-label">Your Name</label>
                        <input class="form-control" id="contact-name" name="contact-name" type="text" placeholder="Your Name" value="<?php echo Input::previous('contact-name'); ?>">
                    </div>
                    <div class="col-sm-12">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" id="contact-email" name="contact-email" type="email" placeholder="your_email@domain.com" value="<?php echo Input::previous('contact-email'); ?>">
                    </div>
                    <div class="col-sm-12">
		                <textarea class="form-control" name="contact-message" placeholder="Message" rows="5"><?php echo Input::previous('contact-message'); ?></textarea>
                    </div>
                    <div class="col-sm-12">
                        <button class="w-100 btn btn-primary btn-lg" type="button" id="send-button">Send</button>
                    </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#send-button').on('click', function() {
                // Serialize the form data
                var formData = $('#contact-form').serialize();

                // Send an AJAX POST request to your server
                $.ajax({
                    type: 'POST',
                    url: $('#contact-form').attr('action'),
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
                            $('.notify').html('<div class="alert alert-danger alert-dismissible fade show">' + errorHtml + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                        } else if (data.notification) {
                            // Display success or failure notification
                            var notificationClass = data.notification === 'Email sent!' ? 'alert-success' : 'alert-danger';
                            $('.notify').html('<div class="alert ' + notificationClass + ' alert-dismissible fade show">' + data.notification + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                            $('#contact-form')[0].reset();
                            // Use setTimeout for fade-out effect
                            setTimeout(function () {
                                $('.notify').fadeOut(600, function () {
                                    $(this).remove();
                                });
                            }, 3000);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
<?php theme_include('footer'); ?>