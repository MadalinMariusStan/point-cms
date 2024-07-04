<?php defined('App') or die('PointCMS'); ?>
<!-- info.tpl.php -->
<div class="accordion" id="accordionExample">
    <?php $index = 1; ?>
    <?php foreach ($info as $section => $data): ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading<?= $index ?>">
            <button class="accordion-button <?= $index > 1 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>" aria-expanded="<?= $index === 1 ? 'true' : 'false' ?>" aria-controls="collapse<?= $index ?>">
                <?= $section ?>
            </button>
        </h2>
        <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 1 ? 'show' : '' ?>" aria-labelledby="heading<?= $index ?>" data-bs-parent="#accordionExample">
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

