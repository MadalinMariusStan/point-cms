<?php echo $header; ?>
<div class="d-flex mt-3 mb-3">
    <div class="me-auto"><h3><?php echo __('stats.posts'); ?></h3></div>
</div>
<?php if ($posts->count): ?>
    <?php foreach ($posts as $post): ?>
        <a href="<?php echo Uri::to('admin/extend/stats/post/' . $post->id); ?>" class="mt-3 text-decoration-none">
            <div class="d-flex align-items-center pt-3 pb-3">
                <div class="pl-2 col-md-11 col-sm-11 col-11 py-1">
                    <p class="text-truncate lead text-body-emphasis font-weight-bold mb-0">
                        <?php echo $post->title; ?>
                    </p>
                    <p class="mb-1 text-secondary text-truncate">
                        <?php echo substr(strip_tags(html_entity_decode($post->description)), 0, 100); ?>...
                    </p>
                    <p class="text-secondary mt-1 mb-0">
                        <?php echo $post->reading_time; ?> min(s) read
                        <span class="d-none d-md-inline">― Updated: <?php echo Date::format($post->updated); ?></span>
                        - <?php echo $post->view_count; ?> views
                    </p>
                </div>
                <div class="pl-5 pe-0 ml-auto">
                    <!-- Icon or additional details -->
                </div>
            </div>
        </a>
    <?php endforeach; ?>
    <div class="d-flex justify-content-center">
        <nav class="mt-3" aria-label="Page navigation">
            <ul class="pagination">
                <?php echo $pagination->links(); ?>
            </ul>
        </nav>
    </div>
<?php else: ?>
    <p class="pt-5 lead"><?php echo __('stats.no_stats'); ?></p>
<?php endif; ?>
<?php echo $footer; ?>
