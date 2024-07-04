<?php defined('App') or die('PointCMS');
global $App;
include 'header.php';
?>
<?php if (!empty($results)): ?>
    <?php foreach ($results as $slug => $page): ?>
    <div class="mb-3 border-bottom">
        <a class="text-decoration-none text-body-emphasism" href="<?= $App->url($slug) ?>">
            <h5 class="text-body-emphasis"><?php echo htmlspecialchars($page['title']); ?></h5>
            <p class="text-body-secondary"><?= substr(htmlspecialchars($page['content']), 0, 150) ?>...</p>
            <span class="text-body-secondary font-monospace fs-xs p-0"> Type: <?php echo $page['type']; ?></span>
        </a>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No results found for '<?php echo htmlspecialchars($searchTerm); ?>'</p>
<?php endif; ?>

<?php include 'footer.php'; // Include your footer file ?>
