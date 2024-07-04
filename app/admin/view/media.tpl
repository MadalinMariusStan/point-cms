<?php defined('App') or die('PointCMS'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bs5-lightbox/1.8.4/bs5-lightbox.min.css" integrity="sha512-2kEJIFd3N7AHzYV3g0FZb13VZh/jzS+Kj5lb04lMPzzUX9M2CxXnUtFbpXgM2PIKmrwZXrmZvgOQ/VuKvOe5Fg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bs5-lightbox/1.8.4/bs5-lightbox.min.js" integrity="sha512-mYDJmcDAJ1vZV6OeWw7k1SxLOa1rK9blsyP8K/sf1q0b+X1wODLZ4jqDk47R4+6+LoftfM7mFzovm+c1BbXABg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<form action="<?php echo $this->admin_url('?page=media', true); ?>" method="post" enctype="multipart/form-data" class="py-4">
    <div class="mb-3">
        <input type="file" id="file" name="file" class="form-control" required>
        <div class="form-text"><?php echo $this->translate('max_upload_size'); ?>: <?php echo ini_get('upload_max_filesize'); ?></div>
    </div>
    <input type="hidden" name="token" value="<?php echo $this->token(); ?>">
    <button type="submit" name="upload" class="btn btn-primary"><?php echo $this->translate('upload'); ?></button>
</form>
<hr class="hr">
<div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($this->medias as $media):
    $extension = pathinfo($media, PATHINFO_EXTENSION);
    $imageSrc = $this->url('media/' . $media);
    $iconMap = [
    'pdf' => 'pdf.png',
    'doc' => 'doc.png',
    'docx' => 'doc.png',
    'xls' => 'xls.png',
    'xlsx' => 'xls.png',
    'csv' => 'csv.png',
    'xml' => 'xml.png',
    'txt' => 'txt.png',
    'zip' => 'zip.png',
    'rar' => 'zip.png',
    'mp4' => 'video.png',
    'avi' => 'video.png',
    'mkv' => 'video.png'
    ];

    if (isset($iconMap[$extension])) {
    $imageSrc = $this->url('assets/img/' . $iconMap[$extension]);
    }
    ?>
    <div class="col">
        <div class="card h-100">
            <img src="<?php echo $imageSrc; ?>" class="card-img-top" alt="<?php echo $this->translate('media_preview'); ?>" onerror="this.onerror=null;this.src='<?php echo $this->url('assets/img/default.png'); ?>';">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($media); ?></h5>
                <div class="d-flex justify-content-center">
                    <a href="<?php echo $this->url('media/' . $media); ?>" target="_blank" class="btn btn-primary me-1"><?php echo $this->translate('view'); ?></a>
                    <a href="<?php echo $this->admin_url('?page=media&action=delete&file=' . $media . '&token=' . $this->token()); ?>" class="btn btn-danger"><?php echo $this->translate('delete'); ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
