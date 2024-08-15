<?php echo $header; ?>
<h2>System Information</h2>

<?php if (!empty($system_info)): ?>
    <div class="accordion" id="accordionInfo">
        <?php $index = 1; ?>
        <?php foreach ($system_info as $section => $data): ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading<?= $index ?>">
                    <button class="accordion-button <?= $index > 1 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="<?= $index === 1 ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>">
                        <?= htmlspecialchars($section) ?>
                    </button>
                </h2>
                <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 1 ? 'show' : '' ?>" aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordionInfo">
                    <div class="accordion-body">
                        <table class="table table-striped info-data">
                            <?php foreach ($data as $key => $value): ?>
                                <tr>
                                    <td class="info-data-key"><strong><?= htmlspecialchars($key) ?></strong></td>
                                    <td class="info-data-value"><?= htmlspecialchars($value) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php $index++; ?>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No system information available.</p>
<?php endif; ?>
<?php echo $footer; ?>

