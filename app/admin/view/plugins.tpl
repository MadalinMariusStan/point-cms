<?php defined('App') or die('PointCMS'); ?>
<div class="row">
    <?php if (!empty($this->pluginDetails)): ?>
    <?php foreach ($this->pluginDetails as $plugin => $details): ?>
    <div class="col-md-4 mb-4 align-items-stretch">
        <div class="card h-100">
            <img src="<?php echo $details['image']; ?>" class="card-img-top"
                 alt="<?php echo $details['name']; ?> <?php echo $this->translate('preview'); ?>">
            <div class="card-body">
                <h5 class="card-title"><?php echo $details['name']; ?></h5>
                <p class="card-text"><?php echo $details['summary']; ?></p>
                <div class="d-grid gap-2">
                    <?php if ($this->installed($plugin)): ?>
                    <a href="<?php echo $this->admin_url('?page=plugins&action=uninstall&plugin=' . $plugin . '&token=' . $this->token()); ?>"
                       class="btn btn-danger"><?php echo $this->translate('uninstall'); ?></a>
                    <a href="<?php echo $this->admin_url('?page=' . $plugin); ?>"
                       class="btn btn-secondary"><?php echo $this->translate('configure'); ?></a>
                    <?php else: ?>
                    <a href="<?php echo $this->admin_url('?page=plugins&action=install&plugin=' . $plugin . '&token=' . $this->token()); ?>"
                       class="btn btn-success"><?php echo $this->translate('install'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer text-muted">
                <small><?php echo $this->translate('author'); ?>: <?php echo $details['author']; ?></small><br>
                <small><?php echo $this->translate('version'); ?>: <?php echo $details['version']; ?></small>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="col-12">
        <p><?php echo $this->translate('no_plugins_available'); ?></p>
    </div>
    <?php endif; ?>
</div>
