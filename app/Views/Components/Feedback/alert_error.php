<?php
/**
 * Reusable error alert component
 *
 * @var string $message Alert message (translation key or direct text)
 * @var array $options Additional options:
 *   - dismissible: bool Whether alert can be dismissed (default: true)
 *   - class: string Additional CSS classes
 *   - id: string Alert ID
 *   - icon: string Glyphicon class (default: 'exclamation-sign')
 *   - title: string Optional title (translation key)
 */

$defaults = [
    'dismissible' => true,
    'class' => '',
    'id' => null,
    'icon' => 'exclamation-sign',
    'title' => null,
];
$options = array_merge($defaults, $options ?? []);

$alert_class = 'alert alert-danger ' . $options['class'];
if ($options['dismissible']) {
    $alert_class .= ' alert-dismissible';
}
?>

<div class="<?= $alert_class ?>" role="alert"<?= $options['id'] ? ' id="' . $options['id'] . '"' : '' ?>>
    <?php if ($options['dismissible']): ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="<?= lang('Common.close') ?>">
        <span aria-hidden="true">&times;</span>
    </button>
    <?php endif; ?>

    <?php if ($options['icon']): ?>
    <span class="glyphicon glyphicon-<?= $options['icon'] ?>" aria-hidden="true"></span>&nbsp;
    <?php endif; ?>

    <?php if ($options['title']): ?>
    <strong><?= lang($options['title']) ?></strong>&nbsp;
    <?php endif; ?>

    <?= lang($message) ?>
</div>
