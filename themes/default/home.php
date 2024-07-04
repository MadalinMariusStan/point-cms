<?php defined('App') or die('PointCMS'); global $App; ?>
<?php include 'header.php'; ?>
<?= $App->get_action('home_top') ?>
<div class="mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <?php $posts = array_reverse($App->data()['pages'], true) ?>
                <?php $posts = $App->get_filter($posts, 'recent_posts') ?>
                <?php foreach ($posts as $slug => $page): ?>
                    <?php if ('post' === $page['type'] && $page['pub']): ?>
                        <div class="col-md-3">
                            <?php if (!empty($page['cover'])): ?>
                                <img src="media/<?= $page['cover'] ?>" class="img-fluid rounded" alt="Post Cover">
                            <?php else: ?>
                                <img src="assets/img/no_image.png" class="img-fluid rounded" alt="No Image">
                            <?php endif ?>
                            <h3><a class="text-body-emphasis text-decoration-none fw-normal" href="<?= $slug ?>"><?= $page['title'] ?></a></h3>
                            <p class="text-muted">
                                <?= $this->translate('posted') ?> <?= time_elapsed_string($page['date']) ?> | <?= $this->translate('reading_time') ?> <?= getReadingTime($page['content']) ?>.
                            </p>
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
                            <p><?= substr(strip_tags($page['content']), 0, 100) ?>...</p>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
                <?php if (empty($posts)): ?>
                    <div class="col-md-9 text-center mx-auto my-0 my-md-5 py-0 py-lg-5 position-relative z-index-9">
                        <!-- SVG shape START -->
                        <figure class="position-absolute top-50 start-50 translate-middle opacity-7 z-index-n9">
                            <svg width="650" height="379" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 510 297">
                                <g>
                                    <path class="fill-primary opacity-1" d="M121,147.4c0,6-4.8,10.8-10.8,10.8H47.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
              c6,0,10.8,4.8,10.8,10.8V147.4z"></path>
                                    <path class="fill-primary opacity-1" d="M179.4,90.2c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V78.7c0-6,4.8-10.8,10.8-10.8h62.6
              c6,0,10.8,4.8,10.8,10.8V90.2z"></path>
                                    <path class="fill-primary opacity-1" d="M459.1,26.3c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V14.8c0-6,4.8-10.8,10.8-10.8h62.6
              c6,0,10.8,4.8,10.8,10.8V26.3z"></path>
                                    <path class="fill-primary opacity-1" d="M422.1,66.9c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8V55.3c0-6,4.8-10.8,10.8-10.8h62.6
              c6,0,10.8,4.8,10.8,10.8V66.9z"></path>
                                    <path class="fill-primary opacity-1" d="M275.8,282.6c0,5.9-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
              c6,0,10.8,4.8,10.8,10.8V282.6z"></path>
                                    <path class="fill-primary opacity-1" d="M87.7,42.9c0,5.9-4.8,10.8-10.8,10.8H14.3c-6,0-10.8-4.8-10.8-10.8V31.4c0-6,4.8-10.8,10.8-10.8h62.6
              c6,0,10.8,4.8,10.8,10.8V42.9z"></path>
                                    <path class="fill-primary opacity-1" d="M505.9,123.4c0,6-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
              c6,0,10.8,4.8,10.8,10.8V123.4z"></path>
                                    <path class="fill-primary opacity-1" d="M482.5,204.9c0,5.9-4.8,10.8-10.8,10.8h-62.6c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
              c5.9,0,10.8,4.8,10.8,10.8V204.9z"></path>
                                    <path class="fill-primary opacity-1" d="M408.3,258.8c0,5.9-4.8,10.8-10.8,10.8H335c-6,0-10.8-4.8-10.8-10.8v-11.5c0-6,4.8-10.8,10.8-10.8h62.6
              c6,0,10.8,4.8,10.8,10.8V258.8z"></path>
                                    <path class="fill-primary opacity-1" d="M147,252.5c0,5.9-4.8,10.8-10.8,10.8H73.6c-6,0-10.8-4.8-10.8-10.8V241c0-5.9,4.8-10.8,10.8-10.8h62.6
              c6,0,10.8,4.8,10.8,10.8V252.5z"></path>
                                </g>
                            </svg>
                        </figure>
                        <!-- SVG shape END -->
                        <!-- Content -->
                        <h1 class="display-1 text-primary fw-semibold"><?= $this->translate('no_posts_yet') ?></h1>
                        <p><?= $this->translate('please_check_back_later') ?></p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<?= $App->get_action('home_bottom') ?>
<?php include 'footer.php'; ?>
