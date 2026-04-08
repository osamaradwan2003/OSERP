<?php
/**
 * Reusable modal with form component
 *
 * @var string $id Modal ID
 * @var string $title Modal title (translation key)
 * @var string $form_action Form action URL
 * @var string $form_content Form content (can be passed or rendered inside)
 * @var array $options Additional options:
 *   - size: string Modal size ('sm', 'md', 'lg')
 *   - class: string Additional CSS classes
 *   - form_id: string Form ID
 *   - form_class: string Form CSS classes
 *   - multipart: bool Whether form has file upload
 *   - submit_label: string Submit button label (translation key)
 *   - cancel_label: string Cancel button label (translation key)
 *   - submit_class: string Submit button class
 *   - submit_icon: string Submit button icon
 *   - icon: string Glyphicon class for title
 *   - show_required_message: bool Whether to show required fields message
 */

$defaults = [
    'size' => 'md',
    'class' => '',
    'form_id' => 'modal_form',
    'form_class' => 'form-horizontal',
    'multipart' => false,
    'submit_label' => 'Common.submit',
    'cancel_label' => 'Common.cancel',
    'submit_class' => 'btn-primary',
    'submit_icon' => 'ok',
    'icon' => null,
    'show_required_message' => true,
];
$options = array_merge($defaults, $options ?? []);

$size_class = $options['size'] === 'md' ? '' : 'modal-' . $options['size'];
?>

<div class="modal fade <?= $options['class'] ?>"
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
                    <?php if ($options['icon']): ?>
                    <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
                    <?php endif; ?>
                    <?= lang($title) ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php if ($options['show_required_message']): ?>
                <div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
                <?php endif; ?>
                <ul id="error_message_box" class="error_message_box"></ul>

                <?php if ($options['multipart']): ?>
                <?= form_open_multipart($form_action, ['id' => $options['form_id'], 'class' => $options['form_class']]) ?>
                <?php else: ?>
                <?= form_open($form_action, ['id' => $options['form_id'], 'class' => $options['form_class']]) ?>
                <?php endif; ?>

                <?php if (isset($form_content)): ?>
                <?= $form_content ?>
                <?php elseif (isset($slots) && $slots->has('form_content')): ?>
                <?= $slots->form_content ?>
                <?php endif; ?>

                <?= form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remove"></span>&nbsp;
                    <?= lang($options['cancel_label']) ?>
                </button>
                <button type="button"
                        class="btn <?= $options['submit_class'] ?>"
                        id="<?= $options['form_id'] ?>_submit">
                    <span class="glyphicon glyphicon-<?= $options['submit_icon'] ?>"></span>&nbsp;
                    <?= lang($options['submit_label']) ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function() {
    var submitBtn = document.getElementById('<?= $options['form_id'] ?>_submit');
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            var form = document.getElementById('<?= $options['form_id'] ?>');
            if (form) {
                $(form).submit();
            }
        });
    }
})();
</script>
