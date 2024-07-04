<?php defined('App') or die('PointCMS');
global $App;
include 'header.php'; // Include your header file
?>
<h3 class="mb-3">Posts tagged with "<?= htmlspecialchars($keyword) ?>"</h3>
<?php if (!empty($posts)): ?>
    <?php foreach ($posts as $slug => $page): ?>
        <div class="mb-3 border-bottom">
            <a class="text-decoration-none" href="<?= $App->url($slug) ?>">
                <h5 class="text-body-emphasis"><?= htmlspecialchars($page['title']) ?></h5>
                <p class="text-body-secondary"><?= substr(htmlspecialchars($page['content']), 0, 150) ?>...</p>
            </a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No posts found with the keyword "<?= htmlspecialchars($keyword) ?>".</p>
<?php endif; ?>
<?php include 'footer.php'; // Include your footer file ?>
