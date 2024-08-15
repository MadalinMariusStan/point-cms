<?php echo $header; ?>
<div class="d-flex mt-3 mb-3">
    <div class="me-auto"><h3><?php echo __('notifications.notifications'); ?></h3></div>
    <div>
        <!-- Mark All as Read Button -->
        <button class="btn btn-link text-decoration-none" id="mark-all-read">Mark All as Read</button>
    </div>
</div>

<?php if (!empty($notifications)): ?>
    <?php foreach ($notifications as $notification): ?>
        <div class="d-flex mb-3">
            <div class="flex-shrink-0">
                <?php if ($notification->type == 'comment'): ?>
                    <a href="<?php echo Uri::to('admin/comments/edit/' . $notification->details['id']); ?>"
                       class="text-decoration-none"
                       onclick="markAsRead(<?php echo $notification->id; ?>)">
                        <img class="rounded-circle avatar-xl mt-3" src="<?php echo Uri::to('app/views/assets/img/comment.png'); ?>" alt="Avatar">
                    </a>
                <?php elseif ($notification->type == 'post'): ?>
                    <?php
                    $avatarUrl = isset($notification->details['author']) ? getAuthorAvatar($notification->details['author']) : Uri::to('app/views/assets/img/no_avatar.png');
                    ?>
                    <a href="<?php echo Uri::to('admin/posts/edit/' . $notification->id); ?>"
                       class="text-decoration-none"
                       onclick="markAsRead(<?php echo $notification->id; ?>)">
                        <img class="rounded-circle avatar-xl mt-3" src="<?php echo htmlspecialchars($avatarUrl); ?>" alt="Avatar">
                    </a>
                <?php elseif ($notification->type == 'like' && isset($notification->details['post_id'])): ?>
                    <a href="<?php echo Uri::to('admin/posts/edit/' . $notification->details['post_id']); ?>"
                       class="text-decoration-none"
                       onclick="markAsRead(<?php echo $notification->id; ?>)">
                        <img class="rounded-circle avatar-xl mt-3"
                             src="<?php echo Uri::to('app/views/assets/img/like.png'); ?>" alt="Avatar">
                    </a>
                <?php elseif ($notification->type == 'subscriber'): ?>
                    <a href="javascript:void(0);" class="text-decoration-none" onclick="markAsRead(<?php echo $notification->id; ?>)">
                        <img class="rounded-circle avatar-xl mt-2" src="<?php echo Uri::to('app/views/assets/img/subscriber.png'); ?>" alt="Avatar">
                    </a>
                <?php endif; ?>
            </div>
            <div class="fs-9 flex-grow-1 ms-3">
                <?php if ($notification->type == 'comment'): ?>
                    <a href="<?php echo Uri::to('admin/comments/edit/' . $notification->details['id']); ?>"
                       class="text-decoration-none"
                       onclick="markAsRead(<?php echo $notification->id; ?>)">
                        <h6 class="text-body-emphasis mb-1"><?php echo htmlspecialchars($notification->details['name']); ?></h6>
                        <p class="text-body-emphasis mb-1">
                            <span class="me-1">💬</span>Commented:
                            <span class="fw-bold">"<?php echo htmlspecialchars($notification->details['text']); ?>"</span>
                        </p>
                    </a>
                    <p class="text-body-secondary mb-0">
                        <svg width="12" height="12"><use href="#bi-clock"></use></svg>
                        <span class="fw-bold"><?php echo date('h:i A', strtotime($notification->created_at)); ?> </span><?php echo date('F j, Y', strtotime($notification->created_at)); ?>
                    </p>
                <?php elseif ($notification->type == 'post' && isset($notification->details['author'])): ?>
                    <a href="<?php echo Uri::to('admin/posts/edit/' . $notification->id); ?>"
                       class="text-decoration-none"
                       onclick="markAsRead(<?php echo $notification->id; ?>)">
                        <h6 class="text-body-emphasis mb-1"><?php echo htmlspecialchars(getAuthorRealName($notification->details['author'])); ?></h6>
                        <p class="text-body-emphasis mb-1">
                            <span class="me-1">✍️</span>Created a draft post titled
                            <span class="fw-bold">"<?php echo htmlspecialchars($notification->details['title']); ?>"</span>
                        </p>
                    </a>
                    <p class="text-body-secondary mb-0">
                        <svg width="12" height="12"><use href="#bi-clock"></use></svg>
                        <span class="fw-bold"><?php echo date('h:i A', strtotime($notification->created_at)); ?> </span><?php echo date('F j, Y', strtotime($notification->created_at)); ?>
                    </p>
                <?php elseif ($notification->type == 'like' && isset($notification->details['post_id'])): ?>
                    <a href="<?php echo Uri::to('admin/posts/edit/' . $notification->details['post_id']); ?>"
                       class="text-decoration-none"
                       onclick="markAsRead(<?php echo $notification->id; ?>)">
                        <p class="text-body-emphasis mt-2 mb-1">
                            <span class="me-1">👍</span>Someone liked your post:
                            <span class="fw-bold">"<?php echo htmlspecialchars($notification->details['post_title']); ?>"</span>
                        </p>
                    </a>
                    <p class="text-body-secondary mb-0">
                        <svg width="12" height="12"><use href="#bi-clock"></use></svg>
                        <span class="fw-bold"><?php echo date('h:i A', strtotime($notification->created_at)); ?> </span><?php echo date('F j, Y', strtotime($notification->created_at)); ?>
                    </p>
                <?php elseif ($notification->type == 'subscriber'): ?>
                    <a href="javascript:void(0);" class="text-decoration-none" onclick="markAsRead(<?php echo $notification->id; ?>)">
                        <p class="text-body-emphasis mb-1">
                            <span class="me-1">📧</span>New subscription:
                            <span class="fw-bold">"<?php echo htmlspecialchars($notification->details['email']); ?>"</span>
                        </p>
                    </a>
                    <p class="text-body-secondary mb-0">
                        <svg width="12" height="12"><use href="#bi-clock"></use></svg>
                        <span class="fw-bold"><?php echo date('h:i A', strtotime($notification->created_at)); ?> </span><?php echo date('F j, Y', strtotime($notification->created_at)); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        <nav class="mt-3" aria-label="Page navigation">
            <ul class="pagination">
                <?php echo $pagination->links(); ?>
            </ul>
        </nav>
    </div>
<?php else: ?>
    <p>No notifications found for this status.</p>
<?php endif; ?>

<?php echo $footer; ?>

<script>
    function markAsRead(notificationId) {
        $.ajax({
            url: '<?php echo Uri::to('admin/notifications/mark-read'); ?>',
            method: 'POST',
            data: {
                id: notificationId
            },
            success: function(response) {
                if (response.success) {
                    console.log('Notification marked as read');
                    // Optionally, you could add a visual indication that the notification has been read
                } else {
                    console.log('Failed to mark notification as read');
                }
            },
            error: function(error) {
                console.error('Error marking notification as read:', error);
            }
        });
    }

    $('#mark-all-read').click(function() {
        $.ajax({
            url: '<?php echo Uri::to('admin/notifications/mark-all-read'); ?>',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    console.log('All notifications marked as read');
                    location.reload(); // Reload the page to update the UI
                } else {
                    console.log('Failed to mark all notifications as read');
                }
            },
            error: function(error) {
                console.error('Error marking all notifications as read:', error);
            }
        });
    });
</script>
