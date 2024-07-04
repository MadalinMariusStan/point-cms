<?php defined('App') or die('PointCMS'); ?>
<?php
            if ($this->isUpgradeAvailable()) {
echo '<p>New version available: ' . $this->getLatestVersion() . '</p>';
if (isset($_POST['upgrade'])) {
if ($this->upgradeCMS()) {
echo '<p>Upgrade applied successfully!</p>';
$this->applyDatabaseMigrations();
} else {
echo '<p>Upgrade failed. Attempting rollback...</p>';
if ($this->rollback()) {
echo '<p>Rollback successful.</p>';
} else {
echo '<p>Rollback failed.</p>';
}
}
} else {
echo '
<form method="POST">
    <button type="submit" name="upgrade">Upgrade Now</button>
</form>';
}
} else {
echo '<p>Your CMS is up to date.</p>';
}
?>