<?php theme_include('header'); ?>
<div class="col-xl-10 mx-auto">
    <article>
        <h1 class="fw-light"><?php echo article_title(); ?></h1>
        <div class="mt-3">
            <?php echo cover(); ?>
        </div>
        <div class="mt-2">
            <?php echo article_description(); ?>
        </div>
        <div class="fw-light ps-0 text-muted d-flex justify-content-between align-items-center">
            <!-- Text content aligned to the left -->
            <span class="me-auto">
    <?php echo __('site.this_article_is_my'); ?>
                <?php echo numeral(article_number(article_author_id()), true); ?> <!-- Pass author ID to article_number -->
    <?php echo __('site.oldest'); ?>
                <?php echo __('site.it_is'); ?>
                <?php echo count_words(article_description()); ?>
                <?php echo __('site.words_long'); ?>
                <?php if (comments_open()): ?>,
                    <?php echo __('site.and_it_s_got'); ?>
                    <?php echo total_comments() . pluralise(total_comments(), ' comment'); ?>
                <?php endif; ?>
                <?php echo __('site.reading'); ?> <?php echo readingTime(); ?>
</span>

            <!-- Like button aligned to the right -->
            <button id="like-button" class="btn btn-like text-danger" data-article-id="<?php echo article_id(); ?>"
                    data-bs-toggle="tooltip"
                    data-bs-title="<?php echo hasLiked(article_id()) ? __('site.liked') : __('site.like_this_post_vote'); ?>"
                <?php echo hasLiked(article_id()) ? 'disabled' : ''; ?>>

                <?php if (hasLiked(article_id())): ?>
                    <!-- Display filled heart icon -->
                    <svg class="bi" aria-hidden="true">
                        <use xlink:href="#bi-heart-fill"/>
                    </svg>
                <?php else: ?>
                    <!-- Display empty heart icon -->
                    <svg class="bi" aria-hidden="true">
                        <use xlink:href="#bi-heart"/>
                    </svg>
                <?php endif; ?>
                <span id="like-count" class="text-body-emphasis"><?php echo total_likes(); ?></span>
            </button>
        </div>
        <div class="card mt-2 mb-3">
            <div class="card-body">
                <div class="input-group mb-2">
                    <input type="text" id="copylink" class="form-control" placeholder="Copy link" aria-label="Copy link"
                           aria-describedby="copyLink">
                    <a class="input-group-text" id="copyLink" onclick="copyLink()" href="javascript:void(0)"
                       data-bs-toggle="tooltip" data-bs-title="Copy link">
                        <i class="bi bi-copy"></i>
                    </a>
                </div>
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link link-body-emphasis" onclick="shareFacebook()" href="javascript:void(0)">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-body-emphasis" onclick="shareTwitter()" href="javascript:void(0)">
                            <i class="bi bi-twitter"></i> Twitter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-body-emphasis" onclick="shareLinkedIn()" href="javascript:void(0)">
                            <i class="bi bi-linkedin"></i> LinkedIn
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-body-emphasis" onclick="shareWhatsApp()" href="javascript:void(0)">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link link-body-emphasis" onclick="shareEmail()" href="javascript:void(0)">
                            <i class="bi bi-envelope"></i> Email
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </article>
    <div class="card d-flex flex-row align-items-center mb-3">
        <div class="card-body d-flex align-items-center">
            <div class="flex-shrink-0">
                <img src="<?php echo article_author_avatar(); ?>" class="rounded-circle"
                     alt="<?php echo article_author(); ?>" style="width: 60px; height: 60px;">
            </div>
            <div class="flex-grow-1 ms-3">
                <h5 class="fw-light mb-1"><?php echo article_author(); ?></h5>
                <p class="mb-0"><?php echo article_author_bio(); ?></p>
            </div>
        </div>
    </div>

    <?php if (comments_open()): ?>
        <section class="comments">
            <?php if (has_comments()): ?>
                <?php $i = 0;
                while (comments()): $i++; ?>
                    <div class="d-flex comment-main">
                        <div class="flex-shrink-0">
                            <?php echo getProfilePicture(comment_name()); ?>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="name"><?php echo comment_name(); ?></h5>
                            <p>
                                <?php echo comment_text(); ?>
                            </p>
                            <p>
                                <small class="text-muted"><i><?php echo relative_time(comment_time()); ?></i></small>
                            </p>
                            <p class="d-sm-flex mb-2">
                                <!-- Reply button -->
                                <button class="reply-button btn btn-link text-decoration-none link-dark ms-auto"
                                        data-comment-id="<?php echo comment_id(); ?>"><i class="bi bi-reply-fill"></i>
                                    Reply
                                </button>
                            </p>
                            <?php if (has_replies()): ?>
                                <?php while (comment_replies()): ?>
                                    <div class="d-flex mb-2 comment-replay">
                                        <div class="flex-shrink-0">
                                            <?php echo getProfilePicture(comment_reply_name()); ?>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5><?php echo comment_reply_name(); ?></h5>
                                            <p>
                                                <?php echo comment_reply_text(); ?>
                                            </p>
                                            <p>
                                                <small class="text-muted"><i><?php echo relative_time(comment_reply_time()); ?></i></small>
                                            </p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
            <!-- Comment form -->
            <form id="comment-form" class="comment-form" method="post" action="<?php echo comment_form_url(); ?>">
                <!-- Add a hidden input field to store the parent comment ID -->
                <input type="hidden" class="parent-comment-id" id="parent_comment_id" name="parent_comment_id" value="">
                <div class="notify"></div>
                <p class="mt-3">
                    <label for="name"><?php echo __('site.your_name'); ?>.</label>
                    <?php echo Form::text('name', Input::previous('name'), array(
                        'name' => 'name',
                        'type' => 'text',
                        'id' => 'name',
                        'class' => 'form-control',
                        'placeholder' => __('site.your_name_placeholder')
                    )); ?>
                </p>
                <p class="mt-3">
                    <label for="email"><?php echo __('site.your_email'); ?>:</label>
                    <!-- Email input field -->
                    <?php echo Form::text('email', Input::previous('email'), array(
                        'name' => 'email',
                        'type' => 'email',
                        'id' => 'email',
                        'class' => 'form-control',
                        'placeholder' => __('site.your_email_placeholder')
                    )); ?>
                </p>
                <p class="mt-3">
                    <label for="text"><?php echo __('site.your_comment'); ?>:</label>
                    <!-- Comment input field -->
                    <?php echo Form::textarea('text', Input::previous('text'), array(
                        'id' => 'text',
                        'class' => 'form-control',
                        'placeholder' => __('site.your_comment_placeholder')
                    )); ?>
                </p>
                <p class="mt-3">
                    <button type="button" class="btn btn-primary"
                            id="submit-comment"><?php echo __('site.comment_submit'); ?></button>
                </p>
            </form>
        </section>
    <?php endif; ?>
</div>
<script>
    var input = document.getElementById("copylink"); // "moo" is the 'id' of the text field
    input.value = location.href;

    function copyLink() {
        var currentURL = window.location.href;

        // Create a temporary text area element
        var tempTextArea = document.createElement("textarea");
        tempTextArea.value = currentURL;
        tempTextArea.style.position = "fixed"; // to prevent it from affecting the layout
        document.body.appendChild(tempTextArea);

        // Select the text inside the text area
        tempTextArea.select();

        // Use the Clipboard API to copy the selected text
        navigator.clipboard.writeText(currentURL)
            .then(() => {
                console.log("URL copied to clipboard:", currentURL);
            })
            .catch(error => {
                console.error("Unable to copy URL to clipboard:", error);
            })
            .finally(() => {
                // Clean up: remove the temporary text area
                document.body.removeChild(tempTextArea);
            });
    }

    function shareFacebook() {
        window.open("https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(window.location.href));
    }

    function shareTwitter() {
        window.open("https://twitter.com/intent/tweet?url=" + encodeURIComponent(window.location.href));
    }

    function shareLinkedIn() {
        window.open("https://www.linkedin.com/sharing/share-offsite/?url=" + encodeURIComponent(window.location.href));
    }

    function shareWhatsApp() {
        window.open("https://api.whatsapp.com/send?text=" + encodeURIComponent(window.location.href));
    }

    function shareEmail() {
        var subject = encodeURIComponent("Check out this interesting article");
        // Simplify the body to ensure compatibility across email clients
        var body = encodeURIComponent("I found this article interesting and thought you might like it. Please copy and paste the following link into your browser to view it: " + window.location.href);

        window.open("mailto:?subject=" + subject + "&body=" + body);
    }
</script>
<!-- JavaScript for handling AJAX requests -->
<script>
    $(document).ready(function () {
        $('#like-button').click(function () {
            console.log("Button clicked"); // Log a message to confirm the button click
            var article_id = $(this).data('article-id');
            console.log("Article ID: " + article_id); // Log the article ID

            // Send an AJAX request to like the article
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('like'); ?>',
                data: {article_id: article_id},
                success: function (response) {
                    if (response.success) {
                        console.log("Like successful"); // Log if the like was successful
                        console.log("Like count: " + response.likeCount); // Log the updated like count

                        // Update the like count
                        $('#like-count').text(response.likeCount);

                        // Find the like button and icon elements
                        var likeBtn = document.querySelector('.btn-like');
                        var likeIcon = likeBtn.querySelector('svg use');

                        // Change the icon to a filled heart
                        likeIcon.setAttribute('href', '#bi-heart-fill');  // Ensure the SVG symbol ID exists

                        // Update tooltip using Bootstrap's API
                        var tooltipInstance = bootstrap.Tooltip.getInstance(likeBtn);
                        if (tooltipInstance) {
                            tooltipInstance.setContent({'.tooltip-inner': "<?php echo __('site.liked'); ?>"});
                        }

                        // Show the updated tooltip
                        tooltipInstance.show();

                        // Optionally, hide the tooltip after a delay
                        setTimeout(function () {
                            tooltipInstance.hide();
                        }, 2000);

                        // Disable the button to prevent multiple likes
                        likeBtn.disabled = true;
                    } else {
                        console.log("Like failed"); // Log if the like failed
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", status, error); // Log AJAX errors
                }
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Handle "Reply" button clicks
        $('.reply-button').on('click', function () {
            var commentId = $(this).data('comment-id'); // This captures the correct parent comment ID
            var parentCommentIdField = $('.comment-form .parent-comment-id');
            var commenterName = $(this).closest('.comment-main').find('.name').text(); // Get commenter's name

            // Set the parent comment ID in the form's hidden field
            parentCommentIdField.val(commentId);

            // Add "Reply to [Commenter's Name]" label
            $('.comment-form .reply-label').remove(); // Remove any existing label
            var replyLabel = '<h4 class="reply-label">Reply to ' + commenterName + ':</h4>';
            $('.comment-form').prepend(replyLabel);

            // Add "Cancel Reply" button
            $('.comment-form .cancel-reply-button').remove(); // Remove any existing button
            var cancelReplyButton = '<button type="button" class="btn btn-secondary cancel-reply-button">Cancel Reply</button>';
            $('.comment-form').append(cancelReplyButton);

            // Scroll to the comment form
            $('html, body').animate({
                scrollTop: $('.comment-form').offset().top - 50 // You can adjust the offset as needed
            }, 500); // You can adjust the animation speed
        });

        // Handle "Cancel Reply" button clicks
        $(document).on('click', '.cancel-reply-button', function () {
            // Clear the parent comment ID in the form's hidden field
            $('.comment-form .parent-comment-id').val('');

            // Remove the "Reply to [Commenter's Name]" label
            $('.comment-form .reply-label').remove();

            // Remove the "Cancel Reply" button
            $('.comment-form .cancel-reply-button').remove();
        });


        // Handle comment submission using AJAX
        $('#submit-comment').on('click', function () {
            var commentForm = $('.comment-form');
            var formData = commentForm.serialize(); // Serialize form data
            var notifyDiv = $('.notify'); // The <div class="notify"></div> element

            $.ajax({
                type: 'POST',
                url: commentForm.attr('action'), // Use the form's action URL
                data: formData,
                dataType: 'json',
                success: function (response) {
                    // Clear any previous notifications
                    notifyDiv.html('');

                    if (response.errors) {
                        // There are errors in the response, display them in the notify div
                        notifyDiv.html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + response.errors.join('<br>') + '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    } else if (response.isReply) {
                        // It's a reply, update the UI accordingly
                        // For example, you can append the new reply to the appropriate comment section
                        // and display a success message within that comment section.
                        // Example:
                        var replyMessage = '<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.notification + '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        notifyDiv.html(replyMessage);
                        // Optionally, you can clear the form fields after a successful submission
                        commentForm.trigger('reset');
                    } else {
                        // It's a new comment, display the success message in the notify div
                        notifyDiv.html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.notification + '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        // Optionally, you can clear the form fields after a successful submission
                        commentForm.trigger('reset');
                    }

                },
                error: function (xhr, status, error) {
                    // Handle errors, e.g., display an error message
                    console.error(xhr.responseText);
                    // Display a generic error message in the notify div
                    notifyDiv.html('<div class="alert alert-danger alert-dismissible fade show" role="alert">An error occurred. Please try again.  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            });
        });
    });
</script>

<?php theme_include('footer'); ?>
