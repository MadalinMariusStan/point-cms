<?php defined('App') or die('PointCMS'); ?>
<form action="<?php echo $this->admin_url('?page=create', true); ?>" method="post" enctype="multipart/form-data" class="py-4 needs-validation" novalidate>
    <div class="input-group mb-3">
        <label class="input-group-text" for="title"><?php echo $this->translate('title'); ?></label>
        <input type="text" id="title" name="title" placeholder="<?php echo $this->translate('page_title'); ?>" value="<?php echo $this->esc($_POST['title'] ?? ''); ?>" class="form-control form-control-lg rounded-end" required>
        <div class="invalid-feedback"><?php echo $this->translate('please_enter_title'); ?></div>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="type"><?php echo $this->translate('type'); ?></label>
        <select id="type" name="type" class="form-select">
            <option value="post"<?php echo ($_POST['type'] ?? '') === 'post' ? ' selected' : ''; ?>><?php echo $this->translate('post'); ?></option>
            <option value="page"<?php echo ($_POST['type'] ?? '') === 'page' ? ' selected' : ''; ?>><?php echo $this->translate('page'); ?></option>
            <?php echo $this->get_action('type'); ?>
        </select>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="descr"><?php echo $this->translate('description'); ?></label>
        <textarea rows="5" id="descr" name="descr" placeholder="<?php echo $this->translate('page_description'); ?>" class="form-control"><?php echo $this->esc($_POST['descr'] ?? ''); ?></textarea>
    </div>
    <div class="mb-3">
        <textarea rows="20" id="content" name="content" placeholder="<?php echo $this->translate('start_writing'); ?>" class="form-control"><?php echo $this->esc($_POST['content'] ?? ''); ?></textarea>
    </div>
    <div class="mb-3" id="keywords-container" style="display: none;">
        <input type="text"  name="keywords" placeholder="<?php echo $this->translate('keywords'); ?>" value="<?php echo $this->esc($_POST['keywords'] ?? ''); ?>" class="form-control" data-ub-tag-variant="primary" id="keywords">
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="permalink"><?php echo $this->translate('permalink'); ?></label>
        <input type="text" id="permalink" name="permalink" placeholder="<?php echo $this->translate('custom_permalink'); ?>" value="<?php echo $this->esc_slug($_POST['permalink'] ?? ''); ?>" class="form-control">
    </div>
    <div id="cover-container" class="mb-3">
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#coverModal"><?php echo $this->translate('upload_select_cover'); ?></button>
        </div>
        <input type="hidden" id="cover-selected" name="cover">
        <div id="cover-preview" class="mt-3 text-center">
            <img src="<?php echo $this->url('assets/img/no_image.png'); ?>" class="img-thumbnail" alt="<?php echo $this->translate('thumbnail_preview'); ?>">
        </div>
    </div>
    <div class="input-group mb-3" id="date-container" style="display: none;">
        <label class="input-group-text" for="date"><?php echo $this->translate('date'); ?></label>
        <input type="datetime-local" id="date" name="date" value="<?php echo $this->esc($_POST['date'] ?? date('Y-m-d\TH:i')); ?>" class="form-control">
    </div>
    <div id="page-options" style="display: none;">
        <div class="mb-3 form-switch">
            <input type="checkbox" class="form-check-input" id="showInMenu" name="showInMenu" value="true">
            <label class="form-check-label" for="showInMenu"><?php echo $this->translate('show_in_menu'); ?></label>
        </div>
        <div class="mb-3 form-switch">
            <input type="checkbox" class="form-check-input" id="showInFooter" name="showInFooter" value="true">
            <label class="form-check-label" for="showInFooter"><?php echo $this->translate('show_in_footer'); ?></label>
        </div>
    </div>
    <div class="input-group mb-3">
        <label class="input-group-text" for="pub"><?php echo $this->translate('publish'); ?></label>
        <select id="pub" name="pub" class="form-select">
            <option value="true"<?php echo ($_POST['pub'] ?? '') === 'true' ? ' selected' : ''; ?>><?php echo $this->translate('yes'); ?></option>
            <option value="false"<?php echo ($_POST['pub'] ?? '') === 'false' ? ' selected' : ''; ?>><?php echo $this->translate('no'); ?></option>
        </select>
    </div>
    <?php echo $this->get_action('form'); ?>
    <input type="hidden" name="token" value="<?php echo $this->esc($_POST['token'] ?? $this->token()); ?>">
    <input type="hidden" name="id" value="<?php echo $this->esc($_POST['id'] ?? $this->generateID()); ?>">
    <input type="hidden" name="tpl" value="theme.php">
    <button type="submit" name="create" class="btn btn-primary"><?php echo $this->translate('create'); ?></button>
</form>

<!-- Modal for uploading and selecting covers -->
<div class="modal fade" id="coverModal" tabindex="-1" aria-labelledby="coverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="coverModalLabel"><?php echo $this->translate('upload_select_cover'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="cover-upload-modal" class="form-label"><?php echo $this->translate('upload_cover'); ?></label>
                    <input type="file" id="cover-upload-modal" class="form-control">
                </div>
                <hr>
                <div class="row">
                    <?php
                        foreach ($this->medias as $media) {
                    $ext = pathinfo($media, PATHINFO_EXTENSION);
                    $extensions = $this->_l('thumb_ext', array('avif', 'gif', 'jpeg', 'jpg', 'png', 'webp'));
                    if (in_array($ext, $extensions)) {
                    echo '<div class="col-3">';
                        echo '<img src="' . $this->url('media/' . $media) . '" class="img-thumbnail cover-select" data-media="' . $media . '">';
                        echo '</div>';
                    }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Set up events for when a file is selected or changed
        $('#cover-upload-modal').change(function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#cover-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" alt="<?php echo $this->translate('cover_preview'); ?>">');
                // Clear the hidden field to ensure it does not submit 'no_image.png'
                $('#cover-selected').val('');
                $('#coverModal').modal('hide');
            }
            reader.readAsynchronously(this.files[0]);
        });

        // Set up click event for cover selections
        $('.cover-select').click(function() {
            var media = $(this).data('media');
            $('#cover-preview').html('<img src="' + this.src + '" class="img-thumbnail" alt="<?php echo $this->translate('cover_preview'); ?>">');
            $('#cover-selected').val(media); // Set the selected cover
            $('#cover-upload').val(''); // Clear the upload input
            $('#coverModal').modal('hide');
        });

        // Manage display of cover options based on type selection
        $('#type').change(function() {
            if ($(this).val() === 'post') {
                $('#cover-container').show();
                $('#keywords-container').show();
                $('#date-container').show();
                $('#page-options').hide();
            } else {
                $('#cover-container').hide();
                $('#keywords-container').hide();
                $('#date-container').hide();
                $('#page-options').show();
            }
        }).trigger('change');
    });
</script>
<script>
    UseBootstrapTag(document.getElementById('keywords'))
</script>
