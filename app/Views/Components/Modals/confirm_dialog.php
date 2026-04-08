<?php
/**
 * Reusable confirmation dialog component
 *
 * @var string $id Modal ID
 * @var string $title Dialog title (translation key)
 * @var string $message Confirmation message (translation key)
 * @var array $options Additional options:
 *   - confirm_label: string Confirm button label (translation key)
 *   - cancel_label: string Cancel button label (translation key)
 *   - confirm_class: string Confirm button class (default: 'btn-primary')
 *   - cancel_class: string Cancel button class (default: 'btn-default')
 *   - confirm_icon: string Confirm button icon
 *   - cancel_icon: string Cancel button icon
 *   - size: string Modal size ('sm', 'md', 'lg')
 *   - icon: string Glyphicon class for title
 *   - danger: bool Whether this is a dangerous action (changes confirm button to red)
 *   - on_confirm: string JavaScript to execute on confirm
 */

$defaults = [
    'confirm_label' => 'Common.confirm',
    'cancel_label' => 'Common.cancel',
    'confirm_class' => 'btn-primary',
    'cancel_class' => 'btn-default',
    'confirm_icon' => 'ok',
    'cancel_icon' => 'remove',
    'size' => 'sm',
    'icon' => 'warning-sign',
    'danger' => false,
    'on_confirm' => null,
];
$options = array_merge($defaults, $options ?? []);

if ($options['danger']) {
    $options['confirm_class'] = 'btn-danger';
    $options['confirm_icon'] = 'trash';
}

$size_class = $options['size'] === 'md' ? '' : 'modal-' . $options['size'];
?>

<div class="modal fade"
     id="<?= $id ?>"
     tabindex="-1"
     role="dialog"
     aria-labelledby="<?= $id ?>_label"
     data-backdrop="static">
    <div class="modal-dialog <?= $size_class ?>" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= lang('Common.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="<?= $id ?>_label">
                    <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
                    <?= lang($title) ?>
                </h4>
            </div>
            <div class="modal-body">
                <p><?= lang($message) ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn <?= $options['cancel_class'] ?>" data-dismiss="modal">
                    <span class="glyphicon glyphicon-<?= $options['cancel_icon'] ?>"></span>&nbsp;
                    <?= lang($options['cancel_label']) ?>
                </button>
                <button type="button"
                        class="btn <?= $options['confirm_class'] ?>"
                        id="<?= $id ?>_confirm"
                        <?= $options['on_confirm'] ? 'onclick="' . $options['on_confirm'] . '"' : '' ?>>
                    <span class="glyphicon glyphicon-<?= $options['confirm_icon'] ?>"></span>&nbsp;
                    <?= lang($options['confirm_label']) ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function() {
    var confirmBtn = document.getElementById('<?= $id ?>_confirm');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            $('#<?= $id ?>').modal('hide');
        });
    }
})();
</script>
