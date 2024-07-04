<?php defined('App') or die('PointCMS'); global $App; ?>
<?php include 'header.php'; ?>
<div class="row justify-content-center">
    <div class="col-sm-10 col-md-10 col-lg-10">
        <h1><?= $App->page('title') ?></h1>
        <?php if ('post' === $App->page('type')): ?>
            <?php if ($App->page('cover')): ?>
                <div class="text-center mb-3">
                    <img src="<?= $App->url('media/' . $App->page('cover')) ?>" alt="<?= $App->page('title') ?>"
                         class="img-fluid">
                </div>
            <?php endif ?>
            <p class="text-muted">
                <?= $this->translate('posted') ?> <?= time_elapsed_string($App->page('date')) ?> | <?= getWordCount($App->page('content')) ?>  <?= $this->translate('words_long') ?> | <?= $this->translate('reading_time') ?> <?= getReadingTime($App->page('content')) ?>.
            </p>
            <p>
                <?php if (!empty(trim($App->page('keywords')))): ?>
                    <?php
                    $keywords = explode(',', $App->page('keywords')); // Convert string to array
                    $badgeTypes = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark']; // Define badge colors
                    foreach ($keywords as $index => $keyword) {
                        $trimmedKeyword = trim($keyword);
                        $badgeType = $badgeTypes[$index % count($badgeTypes)]; // Cycle through badge types
                        echo '<a class="text-decoration-none p-2 badge text-bg-' . $badgeType . '" href="' . $App->url('keywords?keyword=' . urlencode($trimmedKeyword)) . '">' . htmlspecialchars($trimmedKeyword) . '</a> ';
                    }
                    ?>
                <?php endif ?>
            </p>
        <?php endif ?>
        <p><?= $App->page('content') ?></p>
        <?php if ('post' === $App->page('type')): ?>
            <div class="clearfix mt-3 mb-3">
                <div class="float-start"><?= $this->translate('views') ?>: <span id="view-count"><?= $this->translate('loading_views') ?></span></div>
                <button class="btn float-end btn-like text-danger float-end" data-bs-toggle="tooltip" title="<?= $App->hasLiked($App->page('id')) ? $this->translate('liked') : $this->translate('like_this_post_vote') ?>" onclick="likePost('<?= $App->page('id') ?>')" <?= $App->hasLiked($App->page('id')) ? 'disabled' : '' ?>>
                    <?php if ($App->hasLiked($App->page('id'))) : ?>
                        <svg class="bi" aria-hidden="true">
                            <use xlink:href="#heart-fill"/>
                        </svg>
                    <?php else : ?>
                        <svg class="bi" aria-hidden="true">
                            <use xlink:href="#heart"/>
                        </svg>
                    <?php endif; ?>
                    <span class="like-count text-body-emphasis"><?php echo $this->getPostLikes($App->page('id')); ?></span>
                </button>
            </div>
            <div class="card mt-3 mb-3">
                <div class="card-body">
                    <div class="input-group mb-2">
                        <input type="text" id="copylink" class="form-control" placeholder="Copy link" aria-label="Copy link"
                               aria-describedby="copyLink">
                        <a class="input-group-text btn-clipboard" id="copyLink" href="javascript:void(0)"
                           title="Copy to clipboard" data-bs-toggle="tooltip" data-bs-placement="bottom">
                            <svg class="bi" aria-hidden="true">
                                <use xlink:href="#clipboard"/>
                            </svg>
                        </a>
                    </div>
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link link-body-emphasis" onclick="shareFacebook()" href="javascript:void(0)">
                                <i class="bi bi-facebook"></i> Facebook
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-body-emphasis" onclick="shareTwitter()" href="javascript:void(0)">
                                <i class="bi bi-twitter-x"></i> Twitter X
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-body-emphasis" onclick="shareLinkedIn()" href="javascript:void(0)">
                                <i class="bi bi-linkedin"></i> LinkedIn
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-body-emphasis" onclick="shareWhatsApp()" href="javascript:void(0)">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link link-body-emphasis" onclick="shareEmail()" href="javascript:void(0)">
                                <i class="bi bi-envelope"></i> Email
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const postId = '<?php echo $App->page('id'); ?>'; // Dynamically get the postId from the server-side
                    const url = `?action=view&postId=${postId}`;

                    function updateViews() {
                        fetch(url)
                            .then(response => response.json())
                            .then(data => {
                                if (data.views) {
                                    document.getElementById('view-count').innerText = data.views; // Ensure this element exists
                                }
                            })
                            .catch(error => console.error('Error fetching view data:', error));
                    }

                    // Refresh the view count every 30 seconds
                    setInterval(updateViews, 30000);

                    // Also update views on initial load
                    updateViews();
                });
            </script>
            <script>
                var input = document.getElementById("copylink"); // "moo" is the 'id' of the text field
                input.value = location.href;
                document.addEventListener("DOMContentLoaded", function () {
                    var copyBtn = document.getElementById("copyLink");
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });

                    copyBtn.addEventListener("click", function () {
                        navigator.clipboard.writeText(location.href).then(() => {
                            // Update tooltip message
                            copyBtn.setAttribute('data-bs-original-title', "<?= $this->translate('copied') ?>");
                            copyBtn.querySelector('.bi use').setAttribute('xlink:href', '#check2'); // Change SVG to 'check' icon
                            bootstrap.Tooltip.getInstance(copyBtn).show();

                            // Reset tooltip after delay
                            setTimeout(() => {
                                copyBtn.setAttribute('data-bs-original-title', "<?= $this->translate('copy_to_clipboard') ?>");
                                copyBtn.querySelector('.bi use').setAttribute('xlink:href', '#clipboard'); // Change back to clipboard icon
                            }, 2000);
                        }).catch(err => {
                            console.error('Failed to copy:', err);
                        });
                    });
                });

                function shareFacebook() {
                    window.open("https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(window.location.href));
                }

                function shareTwitter() {
                    window.open("https://twitter.com/intent/tweet?url=" + encodeURIComponent(window.location.href));
                }

                function shareLinkedIn() {
                    window.open("https://www.linkedin.com/sharing/share-offsite/?url=" + encodeURIComponent(window.location.href));
                }

                function shareWhatsApp() {
                    window.open("https://api.whatsapp.com/send?text=" + encodeURIComponent(window.location.href));
                }

                function shareEmail() {
                    var subject = encodeURIComponent("<?= $this->translate('post_email_subject') ?>");
                    // Simplify the body to ensure compatibility across email clients
                    var body = encodeURIComponent("<?= $this->translate('post_email_body') ?> " + window.location.href);

                    window.open("mailto:?subject=" + subject + "&body=" + body);
                }

                function likePost(postId) {
                    fetch(`?action=like&postId=${postId}`)
                        .then(response => response.text())
                        .then(data => {
                            document.querySelector('.like-count').textContent = data;
                            var likeBtn = document.querySelector('.btn-like');
                            var likeIcon = likeBtn.querySelector('.bi use');
                            // Change icon to filled heart
                            likeIcon.setAttribute('xlink:href', '#heart-fill');
                            // Update tooltip message
                            likeBtn.setAttribute('data-bs-original-title', "<?= $this->translate('liked') ?>");
                            bootstrap.Tooltip.getInstance(likeBtn).show();
                            // Disable the button to prevent multiple likes
                            likeBtn.disabled = true;
                            // Optionally, reset tooltip after delay if needed
                            setTimeout(() => {
                                bootstrap.Tooltip.getInstance(likeBtn).hide();
                            }, 2000);
                        });
                }
            </script>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                });
            </script>
        <?php endif ?>
        <?php if ( 'post' === $App->page( 'type' ) ): ?>
            <?= $App->get_action( 'post' ) ?>
        <?php else: ?>
            <?= $App->get_action( 'page' ) ?>
        <?php endif ?>
    </div>
</div>
<?php include 'footer.php'; ?>
