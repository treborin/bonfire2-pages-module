<?php if (isset($fieldGroups) && count($fieldGroups)) : ?>
<?php foreach ($fieldGroups as $group => $fields) : ?>
<fieldset>
    <div class="row">
        <div class="col">
            <legend><?= esc($group) ?></legend>

            <?php foreach ($fields as $field => $info) : ?>
            <?php if ($info['type'] === 'checkbox') : ?>
            <div class="form-check col-12 col-md-6 mt-3">
                <input type="hidden"
                    name="meta[<?= strtolower((string) $field) ?>]"
                    value="false">
                <input type="checkbox" class="form-check-input"
                    name="meta[<?= strtolower((string) $field) ?>]"
                    value="true"
                    <?= set_checkbox('meta.' . strtolower((string) $field), 'true', ($page->meta(strtolower((string) $field))) === 'true') ?>>
                <label for="meta[<?= $field ?>]" class="form-check-label"><?= esc($info['label']) ?></label>
            </div>
            <?php elseif ($info['type'] === 'textarea') : ?>
                <div class="form-group col-12 col-lg-6">
                    <label for="meta[<?= $field ?>]" class="form-label"><?= esc($info['label']) ?></label>
                    <textarea class="form-control" rows="3" name="meta[<?= strtolower((string) $field) ?>]"
                        ><?= old('meta[' . strtolower((string) $field) . ']', $user->meta(strtolower((string) $field)) ?? '') ?></textarea>
                    <?php if (has_error('meta.' . $field)) : ?>
                        <p class="text-danger"><?= error('meta.' . $field) ?></p>
                    <?php endif ?>
                </div>
            <?php elseif (in_array($info['type'], ['text', 'number', 'password', 'email', 'tel', 'url', 'date', 'time', 'week', 'month', 'color'])) : ?>
                <div class="form-group col-12 col-md-6">
                    <label for="meta[<?= $field ?>]" class="form-label"><?= esc($info['label']) ?></label>
                    <input type="text"
                        name="meta[<?= strtolower((string) $field) ?>]"
                        class="form-control"
                        autocomplete="<?= strtolower((string) $field) ?>"
                        value="<?= old('meta.' . strtolower((string) $field), $page->meta(strtolower((string) $field)) ?: '') ?>">
                    <?php if (has_error('meta.' . $field)) : ?>
                        <p class="text-danger">
                            <?= error('meta.' . $field) ?>
                        </p>
                    <?php endif ?>
                </div>
            <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
</fieldset>
<?php endforeach ?>
<?php endif ?>
