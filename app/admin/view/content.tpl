<?php defined('App') or die('PointCMS'); ?>
<?php if (!$this->is_page($action)): ?>
<!-- Filter by Type -->
<div class="d-flex mt-3 mb-3">
    <div class="ms-auto">
        <div class="input-group border-03">
            <select class="form-select" id="typeFilter" onchange="location = this.value;">
                <option value="<?php echo $this->admin_url('?page=content&type=all&pub=' . ($_GET['pub'] ?? 'all')); ?>"
                <?php if (!isset($_GET['type']) || $_GET['type'] === 'all') echo 'selected'; ?>><?php echo $this->translate('all_types'); ?></option>
                <option value="<?php echo $this->admin_url('?page=content&type=page&pub=' . ($_GET['pub'] ?? 'all')); ?>"
                <?php if (isset($_GET['type']) && $_GET['type'] === 'page') echo 'selected'; ?>><?php echo $this->translate('pages'); ?></option>
                <option value="<?php echo $this->admin_url('?page=content&type=post&pub=' . ($_GET['pub'] ?? 'all')); ?>"
                <?php if (isset($_GET['type']) && $_GET['type'] === 'post') echo 'selected'; ?>><?php echo $this->translate('posts'); ?></option>
            </select>
            <!-- Filter by Publish Status -->
            <select class="form-select" id="pubFilter" onchange="location = this.value;">
                <option value="<?php echo $this->admin_url('?page=content&type=' . ($_GET['type'] ?? 'all') . '&pub=all'); ?>"
                <?php if (!isset($_GET['pub']) || $_GET['pub'] === 'all') echo 'selected'; ?>><?php echo $this->translate('all_statuses'); ?></option>
                <option value="<?php echo $this->admin_url('?page=content&type=' . ($_GET['type'] ?? 'all') . '&pub=1'); ?>"
                <?php if (isset($_GET['pub']) && $_GET['pub'] === '1') echo 'selected'; ?>><?php echo $this->translate('published'); ?></option>
                <option value="<?php echo $this->admin_url('?page=content&type=' . ($_GET['type'] ?? 'all') . '&pub=0'); ?>"
                <?php if (isset($_GET['pub']) && $_GET['pub'] === '0') echo 'selected'; ?>><?php echo $this->translate('unpublished'); ?></option>
            </select>
            <label class="input-group-text"><i class="bi bi-funnel"></i></label>
        </div>
    </div>
</div>
<ul class="list-group mb-5">
    <?php foreach ($paginatedPages as $slug => $details): ?>
    <li class="list-group-item d-flex justify-content-between align-items-center border border-0 border-bottom">
        <div class="w-100">
            <strong class="position-relative">
                <?php echo htmlspecialchars($details['title']); ?>
                <!-- Status Indicator based on 'pub' field -->
                <span class="position-absolute top-0 start-100 translate-middle round round-sm <?php echo ($details['pub']) ? 'bg-success' : 'bg-danger'; ?>"></span>
            </strong>
            <br>
            <?php
            $type = ucfirst(htmlspecialchars($details['type']));
            $translatedType = ($type === 'Post') ? $this->translate('post') : $this->translate('page');
            ?>
            <?php echo $this->translate('type'); ?>: <?php echo $translatedType; ?>
            <br>
            <?php echo $this->translate('created'); ?>: <?php echo date('Y-m-d H:i:s', strtotime($details['date'])); ?>
            <br>
            <?php if ($details['type'] === 'post'): ?>
            <?php echo $this->translate('views'); ?>: <?php echo $this->getPostViews($details['id']); ?>
            | <?php echo $this->translate('likes'); ?>: <?php echo $this->getPostLikes($details['id']); ?>
            <?php endif; ?>
        </div>
        <div class="d-flex align-items-center">
            <!-- Update button -->
            <a href="<?php echo $this->admin_url('?page=update&action=' . $slug); ?>" class="btn btn-primary me-2">
                <i class="bi bi-pencil-square"></i>
            </a>
            <!-- Delete button with confirmation dialog -->
            <form action="<?php echo $this->admin_url('?page=delete', true); ?>" method="post"
                  onsubmit="return confirm('<?php echo $this->translate('confirm_delete'); ?>');" class="d-inline">
                <input type="hidden" name="pages[<?php echo htmlspecialchars($slug); ?>][slug]"
                       value="<?php echo htmlspecialchars($slug); ?>">
                <input type="hidden" name="pages[<?php echo htmlspecialchars($slug); ?>][id]"
                       value="<?php echo htmlspecialchars($details['id']); ?>">
                <input type="hidden" name="pages[<?php echo htmlspecialchars($slug); ?>][title]"
                       value="<?php echo htmlspecialchars($details['title']); ?>">
                <input type="hidden" name="token" value="<?php echo $this->token(); ?>">
                <button type="submit" name="delete" class="btn btn-danger">
                    <i class="bi bi-trash-fill"></i>
                </button>
            </form>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
<!-- Pagination Controls -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php
            // Additional filter parameters to maintain state while paginating
            $filterParams = '';
            if (isset($_GET['type'])) {
                $filterParams .= '&type=' . $_GET['type'];
            }
            if (isset($_GET['pub'])) {
                $filterParams .= '&pub=' . $_GET['pub'];
            }
        ?>
        <?php if ($currentPage > 1): ?>
        <li class="page-item">
            <a class="page-link"
               href="<?php echo $this->admin_url('?page=content&page_num=' . ($currentPage - 1) . $filterParams); ?>"
               aria-label="<?php echo $this->translate('previous'); ?>">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= ceil($totalPages / $perPage); $i++): ?>
        <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
            <a class="page-link"
               href="<?php echo $this->admin_url('?page=content&page_num=' . $i . $filterParams); ?>"><?php echo $i; ?></a>
        </li>
        <?php endfor; ?>
        <?php if ($currentPage < ceil($totalPages / $perPage)): ?>
        <li class="page-item">
            <a class="page-link"
               href="<?php echo $this->admin_url('?page=content&page_num=' . ($currentPage + 1) . $filterParams); ?>"
               aria-label="<?php echo $this->translate('next'); ?>">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>