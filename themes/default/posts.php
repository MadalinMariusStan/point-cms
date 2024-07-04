<?php defined('App') or die('PointCMS'); global $App; ?>
<?php include 'header.php'; ?>
<div class="mt-5">
    <?php $pagination = $App->get_paginated_posts(10);
    foreach ($pagination['posts'] as $slug => $page):
        if (is_numeric($slug)) {
            $slug = array_search($page, $App->data()['pages']);
        } ?>
        <div class="d-flex mb-3">
            <div class="flex-shrink-0 w-25">
                <?php if (!empty($page['cover'])): ?>
                    <img src="media/<?= $page['cover'] ?>" class="img-fluid rounded" alt="<?= $page['title'] ?>">
                <?php else: ?>
                    <img src="assets/img/no_image.png" class="img-fluid rounded" alt="No Image">
                <?php endif ?>
            </div>
            <div class="flex-grow-1 ms-3">
                <h2 class="mb-3"><a class="text-decoration-none" href="<?= $App->url($slug) ?>"><?= $page['title'] ?></a></h2>
                <p class="text-muted">
                    <?= $this->translate('posted') ?> <?= time_elapsed_string($page['date']) ?> | <?= getWordCount($page['content']) ?>  <?= $this->translate('words_long') ?> | <?= $this->translate('reading_time') ?> <?= getReadingTime($page['content']) ?>.
                </p>
                <p>
                    <?php if (!empty(trim($page['keywords']))): ?>
                        <?php
                        $keywords = explode(',', $page['keywords']); // Convert string to array
                        $badgeTypes = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark']; // Define badge colors
                        foreach ($keywords as $index => $keyword) {
                            $trimmedKeyword = trim($keyword);
                            $badgeType = $badgeTypes[$index % count($badgeTypes)]; // Cycle through badge types
                            echo '<a class="text-decoration-none mb-1 p-2 badge text-bg-' . $badgeType . '" href="' . $App->url('keywords?keyword=' . urlencode($trimmedKeyword)) . '">' . htmlspecialchars($trimmedKeyword) . '</a> ';
                        }
                        ?>
                    <?php endif ?>
                </p>
                <p><?= substr( strip_tags( $page[ 'content' ] ), 0, 200 ) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Pagination Links -->
    <nav class="d-flex justify-content-center">
        <ul class="pagination">
            <?php if ($pagination['current_page'] > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?>">Previous</a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
<?php include 'footer.php'; ?>
