<?php defined( 'App' ) or die( 'PointCMS' ); global $App, $layout, $page ?>
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="<?= $App->get( 'lang' ) ?>" data-bs-theme="<?php echo $App->get('admin_theme'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="generator" content="BoidCMS">
    <meta name="theme-color" content="#ffffff">
    <title><?= $layout['title'] ?></title>
    <link rel="stylesheet" href="<?= $App->asset('bootstrap-icons/bootstrap-icons.min.css'); ?>">
    <link rel="stylesheet" href="<?= $App->asset('bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?= $App->assetUrl('css/admin.css'); ?>">
    <link rel="stylesheet" href="<?= $App->assetUrl('bootstrap-tags/use-bootstrap-tag.min.css'); ?>">
    <script src="<?= $App->assetUrl('bootstrap-tags/use-bootstrap-tag.min.js'); ?>"></script>
    <script src="<?= $App->asset('js/jquery-3.7.1.min.js'); ?>"></script>

    <?= $App->get_action('admin_head') ?>
</head>
<body>
<?= $App->get_action('admin_top') ?>
<?php if ($App->logged_in) : ?>
    <header class="p-3 border-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
                    <nav class="navbar d-flex px-0 py-1">
                        <a href="<?= $App->admin_url('?page=dashboard') ?>" class="text-body-emphasis text-decoration-none mr-3">
                            <?php echo $this->translate('dashboard'); ?> <span class="d-none d-sm-inline-block"></span>
                        </a>
                        <ul class="navbar-nav flex-row">
                            <?php if ($page !== 'create'): // Only show if not on the create page ?>
                                <a href="<?= $App->admin_url('?page=create') ?>" type="button" class="btn btn-outline-primary ms-3 me-1 pt-2 ">
                                    <?php echo $this->translate('create_page'); ?>
                                </a>
                            <?php endif; ?>
                            <li class="ms-3 me-4 pt-2">
                                <a class="text-body-emphasis" href="#" data-bs-toggle="modal" data-bs-target="#searchModal">
                                    <i class="bi bi-search fs-5"></i>
                                </a>
                            </li>
                            <div id="dropdown-menu" class="dropdown keep-open">
                                <img src="<?= $App->getAvatarUrl(); ?>" alt="Administrator" id="dropdown" class="dropdown-toggle border avatar-sm me-2" data-bs-toggle="dropdown">
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item<?= ($page === 'user' ? ' active' : '') ?>" href="<?= $App->admin_url('?page=user',true) ?>">
                                            <i class="bi bi-person-circle"></i>  <?= $App->get( 'real_name' ) ?>
                                        </a>
                                    </li>
                                    <hr class="dropdown-divider">
                                    <li>
                                        <a class="dropdown-item" target="_blank" href="<?= $App->url() ?>"><i class="bi bi-eye"></i> <?php echo $this->translate('visit_blog'); ?></a>
                                    </li>
                                    <hr class="dropdown-divider">
                                    <li>
                                        <a class="dropdown-item<?= ($page === 'content' ? ' active' : '') ?>"
                                           href="<?= $App->admin_url('?page=content', true) ?>"><?php echo $this->translate('content'); ?></a>
                                    </li>
                                    <hr class="dropdown-divider">
                                    <li class="dropdown-item">
                                        <a class="nav-link<?= ($page === 'media' ? ' active' : '') ?>"
                                           href="<?= $App->admin_url('?page=media', true) ?>"><?php echo $this->translate('media'); ?></a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="nav-link<?= ($page === 'plugins' ? ' active' : '') ?>"
                                           href="<?= $App->admin_url('?page=plugins', true) ?>"><?php echo $this->translate('plugins'); ?></a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="nav-link<?= ($page === 'themes' ? ' active' : '') ?>"
                                           href="<?= $App->admin_url('?page=themes', true) ?>"><?php echo $this->translate('themes'); ?></a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item<?= ($page === 'settings' ? ' active' : '') ?>"
                                           href="<?= $App->admin_url('?page=settings', true) ?>"><?php echo $this->translate('settings'); ?></a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger<?= ($page === 'logout' ? ' active' : '') ?>"
                                           href="<?= $App->admin_url('?page=logout&token=' . $App->token(), true) ?>"><?php echo $this->translate('logout'); ?></a>
                                </ul>
                            </div>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
<?php endif ?>
<div class="container p-3 m-auto flex-column min-vh-100">
    <div class="row">
        <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12 main">
            <?php $App->alerts() ?>
            <?= $App->get_action('admin_middle') ?>
            <?= $App->get_action($page . '_top') ?>
            <?php if ($App->logged_in) : ?>
                <h3><?= $layout['title'] ?></h3>
            <?php endif ?>
            <?= $layout['content'] ?>
            <?= $App->get_action($page . '_bottom') ?>
        </div>
    </div>
</div>
<?php if ($App->logged_in) : ?>
    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <p class="text-muted"><?php echo $this->translate('powered_by'); ?> <a class="text-decoration-none text-body-emphasis" href="https://point.pixel.com.ro" target="_blank">PointCMS</a>
                v<?= $App->version ?>
            </p>
        </div>
    </footer>
    <!-- Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <input type="text" id="searchInput" class="form-control mb-3" placeholder="<?php echo $this->translate('type_to_search'); ?>">
                    <div id="searchResults" class="container"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Function to handle form submission and validation
            function handleFormSubmission(form, event) {
                let isFormValid = true;
                let firstInvalidInput = null;

                const requiredInputs = form.querySelectorAll('[required]');
                requiredInputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                        const feedbackElement = input.nextElementSibling;
                        if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                            feedbackElement.style.display = 'block';
                        }
                        isFormValid = false;
                        if (!firstInvalidInput) firstInvalidInput = input;
                    } else {
                        input.classList.remove('is-invalid');
                        const feedbackElement = input.nextElementSibling;
                        if (feedbackElement && feedbackElement.classList.contains('invalid-feedback')) {
                            feedbackElement.style.display = 'none';
                        }
                    }
                });

                if (!isFormValid) {
                    event.preventDefault();
                    event.stopPropagation();
                    if (firstInvalidInput) {
                        firstInvalidInput.focus();
                        firstInvalidInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }

                return isFormValid;
            }

            // Event listener for form submissions and validation
            $('form.needs-validation').on('submit', function(event) {
                if (!handleFormSubmission(this, event)) {
                    return;
                }

                // Proceed with form submission if validation passes
                this.submit();
            });

            // Event listener for search input handling
            $('#searchInput').on('input', function() {
                var query = $(this).val();

                if (query.length > 2) {
                    $.ajax({
                        url: '<?php echo $this->admin_url('?page=search', true); ?>',
                        type: 'POST',
                        data: { query: query },
                        dataType: 'json',
                        success: function(data) {
                            $('#searchResults').empty();

                            if (data.length === 0) {
                                $('#searchResults').html('<div class="alert alert-warning"><?php echo $this->translate('no_results_found'); ?></div>');
                            } else {
                                var list = $('<ul class="list-group list-group-flush"></ul>');
                                $.each(data, function(i, item) {
                                    var listItem = $('<li class="list-group-item ps-0"></li>');
                                    listItem.html(`<div class="search-suggestion">
                                <div class="search-suggestion-item">${item.title}<br><small>Type: ${item.type}</small></div>
                                <div class="search-suggestion-options">
                                    <a href="${item.editUrl}" class=""><?php echo $this->translate('edit'); ?></a>
                                    <a href="${item.viewUrl}" class="" target="_blank"><?php echo $this->translate('view'); ?></a>
                                </div>
                            </div>`);
                                    list.append(listItem);
                                });
                                $('#searchResults').append(list);
                            }
                        },
                        error: function() {
                            $('#searchResults').html('<div class="alert alert-danger"><?php echo $this->translate('error_search'); ?></div>');
                        }
                    });
                } else {
                    $('#searchResults').empty();
                }
            });
        });

    </script>
<?php endif ?>
<?= $App->get_action('admin_footer') ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
