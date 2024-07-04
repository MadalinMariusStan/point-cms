<?php defined('App') or die('PointCMS'); ?>
<div class="row">
    <?php foreach ($this->themeDetails as $theme => $details): ?>
    <div class="col-md-4 mb-4 align-items-stretch">
        <div class="card">
            <img src="<?php echo $details['image']; ?>" class="card-img-top"
                 alt="<?php echo $details['name']; ?> <?php echo $this->translate('preview'); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo $details['name']; ?></h5>
                <p class="card-text"><?php echo $details['summary']; ?></p>
                <?php if ($this->get('theme') !== $theme): ?>
                <a href="<?php echo $this->admin_url('?page=themes&action=activate&theme=' . $theme . '&token=' . $this->token(), true); ?>"
                   class="btn btn-success w-100"><?php echo $this->translate('activate'); ?></a>
                <?php else: ?>
                <a href="<?php echo $this->admin_url('?page=' . $theme, true); ?>" class="btn btn-primary w-100"><?php echo $this->translate('configure'); ?></a>
                <?php endif; ?>
            </div>
            <div class="card-footer text-muted">
                <small><?php echo $this->translate('author'); ?>: <?php echo $details['author']; ?></small><br>
                <small><?php echo $this->translate('version'); ?>: <?php echo $details['version']; ?></small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
