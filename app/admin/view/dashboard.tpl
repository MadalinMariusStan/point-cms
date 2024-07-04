<?php defined('App') or die('PointCMS'); ?>
<div class="container py-4">
    <p class="h6"><?php echo $this->translate('welcome_back'); ?> <b><?php echo $this->esc($this->get('real_name'));
            ?></b>.</p>
    <h3 class="monospace">
        <i class="bi bi-journal-text"></i> <?php echo $this->translate('activities'); ?>
        <span class="badge bg-primary"><?php echo $this->countPages(); ?></span> <?php echo $this->translate('pages');
        ?>
        <span class="badge bg-secondary"><?php echo $this->countPosts(); ?></span> <?php echo $this->translate('posts');
        ?>
    </h3>
    <hr class="hr">
    <ul class="list-group">
        <h4 class="monospace"><?php echo $this->translate('recently_created'); ?></h4>
        <?php
        // Sort the list by date in descending order
        uasort($list, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        foreach ($list as $key => $item): ?>
        <li class="list-group-item border border-0 border-bottom p-0 m-0">
            <p class="fs-5 mb-1 text-body-emphasis text-decoration-none fw-bold">
                <?php echo $this->esc($this->page('title', $key)); ?>
            </p>
            <small class="text-muted">
                <?php echo $this->translate('created_on'); ?>: <?php echo date('F j, Y', strtotime($this->page('date',
                $key))); ?> -
                Type: <?php echo $this->page('type', $key) == 'post' ? $this->translate('type_post') :
                $this->translate('type_page'); ?>
            </small>
            <small class="d-flex justify-content-start mb-2">
                <a class="text-body-emphasis ps-0"
                   href="<?php echo $this->admin_url('?page=update&action=' . $key); ?>"><?php echo $this->
                    translate('edit'); ?></a>
                <a class="text-body-emphasis ms-2" href="<?php echo $this->url($key); ?>"
                   target="_blank"><?php echo $this->
                    translate('view'); ?></a>
            </small>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php echo $this->get_action('dashboard'); ?>
</div>
