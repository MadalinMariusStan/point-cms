</div>
<?= $App->get_action('footer') ?>
<footer class="mt-3 py-3">
    <div class="container">
        <div class="col-xl-10 mx-auto">
            <div class="mb-3">
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link text-body-emphasis text-decoration-none"
                           href="<?= $App->url('') ?>">Home</a>
                    </li>
                    <?php $this->prepare_footer_pages(); ?>
                    <?php foreach ($App->footerPages as $slug => $page): ?>
                        <li class="nav-item">
                            <a href="<?= $App->url($slug) ?>" class="nav-link text-body-emphasis text-decoration-none">
                                <?= htmlspecialchars($page['title'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="d-flex justify-content-between py-4 my-4 border-top">
                <p><?php echo $this->get('sitename'); ?> © <?php echo date('Y'); ?> </p>
                <!-- Social Media Links -->
                <ul class="nav">
                    <?php foreach ($App->getSocialMediaLinks() as $key => $data): ?>
                        <li class="nav-item">
                            <a class="nav-link text-body-emphasis fs-5" href="<?= htmlspecialchars($data['link']) ?>" target="_blank" data-bs-toggle="tooltip" title="<?= htmlspecialchars($data['tooltip']) ?>">
                                <i class="bi <?= htmlspecialchars($data['icon']) ?>"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</footer>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var collapseSearch = document.getElementById('collapseSearch');
        var searchInput = document.getElementById('search');

        if (sessionStorage.getItem("searchFormState") === "show") {
            new bootstrap.Collapse(collapseSearch, {
                show: true
            });
        }

        collapseSearch.addEventListener('show.bs.collapse', function () {
            sessionStorage.setItem("searchFormState", "show");
        });

        collapseSearch.addEventListener('hide.bs.collapse', function () {
            sessionStorage.setItem("searchFormState", "hide");
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll("[data-bs-toggle='tooltip']"))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
<script src="<?= $App->asset('bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
