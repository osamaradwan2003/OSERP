<?php
/**
 * Reusable form actions (submit/cancel buttons) component
 *
 * @var array $options Additional options:
 *   - submit_label: string Submit button label (translation key, default: 'Common.submit')
 *   - cancel_label: string Cancel button label (translation key, default: 'Common.cancel')
 *   - submit_class: string Submit button class (default: 'btn-primary')
 *   - cancel_class: string Cancel button class (default: 'btn-default')
 *   - submit_icon: string Submit button icon
 *   - cancel_icon: string Cancel button icon
 *   - show_cancel: bool Whether to show cancel button (default: true)
 *   - cancel_href: string Cancel button href (for links)
 *   - cancel_onclick: string Cancel button onclick handler
 *   - submit_id: string Submit button ID
 *   - cancel_id: string Cancel button ID
 *   - wrapper_class: string Wrapper div class (default: 'col-xs-8 col-xs-offset-3')
 *   - align: string Button alignment ('left', 'center', 'right')
 */

$defaults = [
    'submit_label' => 'Common.submit',
    'cancel_label' => 'Common.cancel',
    'submit_class' => 'btn-primary',
    'cancel_class' => 'btn-default',
    'submit_icon' => 'ok',
    'cancel_icon' => null,
    'show_cancel' => true,
    'cancel_href' => null,
    'cancel_onclick' => null,
    'submit_id' => 'submit',
    'cancel_id' => 'cancel',
    'wrapper_class' => 'col-xs-8 col-xs-offset-3',
    'align' => 'left',
];
$options = array_merge($defaults, $options ?? []);

$align_class = '';
switch ($options['align']) {
    case 'center':
        $align_class = 'text-center';
        break;
    case 'right':
        $align_class = 'text-right';
        break;
    default:
        $align_class = 'text-left';
}
?>

<div class="form-group form-group-sm">
    <div class="<?= $options['wrapper_class'] ?>">
        <div class="<?= $align_class ?>">
            <button type="submit" id="<?= $options['submit_id'] ?>"
                    class="btn <?= $options['submit_class'] ?> btn-sm"
                    name="submit">
                <?php if ($options['submit_icon']): ?>
                <span class="glyphicon glyphicon-<?= $options['submit_icon'] ?>"></span>&nbsp;
                <?php endif; ?>
                <?= lang($options['submit_label']) ?>
            </button>

            <?php if ($options['show_cancel']): ?>
            <?php if ($options['cancel_href']): ?>
            <a href="<?= $options['cancel_href'] ?>"
               id="<?= $options['cancel_id'] ?>"
               class="btn <?= $options['cancel_class'] ?> btn-sm">
                <?php if ($options['cancel_icon']): ?>
                <span class="glyphicon glyphicon-<?= $options['cancel_icon'] ?>"></span>&nbsp;
                <?php endif; ?>
                <?= lang($options['cancel_label']) ?>
            </a>
            <?php elseif ($options['cancel_onclick']): ?>
            <button type="button"
                    id="<?= $options['cancel_id'] ?>"
                    class="btn <?= $options['cancel_class'] ?> btn-sm"
                    onclick="<?= $options['cancel_onclick'] ?>">
                <?php if ($options['cancel_icon']): ?>
                <span class="glyphicon glyphicon-<?= $options['cancel_icon'] ?>"></span>&nbsp;
                <?php endif; ?>
                <?= lang($options['cancel_label']) ?>
            </button>
            <?php else: ?>
            <button type="button"
                    id="<?= $options['cancel_id'] ?>"
                    class="btn <?= $options['cancel_class'] ?> btn-sm"
                    data-dismiss="modal">
                <?php if ($options['cancel_icon']): ?>
                <span class="glyphicon glyphicon-<?= $options['cancel_icon'] ?>"></span>&nbsp;
                <?php endif; ?>
                <?= lang($options['cancel_label']) ?>
            </button>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
