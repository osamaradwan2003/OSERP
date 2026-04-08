<?php
/**
 * Reusable modal dialog wrapper component
 *
 * @var string $id Modal ID
 * @var string $title Modal title (translation key)
 * @var string $content Modal content (can be passed or rendered inside)
 * @var array $options Additional options:
 *   - size: string Modal size ('sm', 'md', 'lg')
 *   - class: string Additional CSS classes
 *   - backdrop: bool|string Backdrop setting (true, false, 'static')
 *   - keyboard: bool Whether to close on escape key
 *   - show_close: bool Whether to show close button
 *   - show_footer: bool Whether to show modal footer
 *   - footer_content: string Footer content
 *   - icon: string Glyphicon class for title
 */

$defaults = [
    'size' => 'md',
    'class' => '',
    'backdrop' => 'static',
    'keyboard' => true,
    'show_close' => true,
    'show_footer' => false,
    'footer_content' => null,
    'icon' => null,
];
$options = array_merge($defaults, $options ?? []);

$size_class = $options['size'] === 'md' ? '' : 'modal-' . $options['size'];
?>

<div class="modal fade <?= $options['class'] ?>"
     id="<?= $id ?>"
     tabindex="-1"
     role="dialog"
     aria-labelledby="<?= $id ?>_label"
     data-backdrop="<?= $options['backdrop'] ?>"
     data-keyboard="<?= $options['keyboard'] ? 'true' : 'false' ?>">
    <div class="modal-dialog <?= $size_class ?>" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php if ($options['show_close']): ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?= lang('Common.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
                <?php endif; ?>
                <h4 class="modal-title" id="<?= $id ?>_label">
                    <?php if ($options['icon']): ?>
                    <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
                    <?php endif; ?>
                    <?= lang($title) ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php if (isset($content)): ?>
                <?= $content ?>
                <?php elseif (isset($slots) && $slots->has('content')): ?>
                <?= $slots->content ?>
                <?php endif; ?>
            </div>
            <?php if ($options['show_footer']): ?>
            <div class="modal-footer">
                <?= $options['footer_content'] ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
