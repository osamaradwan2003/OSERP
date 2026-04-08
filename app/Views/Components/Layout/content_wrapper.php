<?php
/**
 * Reusable content wrapper component
 *
 * @var string $content Content to wrap (can be passed or rendered inside)
 * @var array $options Additional options:
 *   - class: string Additional CSS classes
 *   - id: string Wrapper ID
 *   - container: bool Whether to wrap in container (default: true)
 *   - container_class: string Container class (default: 'container')
 *   - row: bool Whether to wrap in row (default: true)
 *   - panel: bool Whether to wrap in panel
 *   - panel_class: string Panel class (default: 'panel-default')
 *   - panel_heading: string Panel heading text (translation key)
 *   - panel_icon: string Panel heading icon
 */

$defaults = [
    'class' => '',
    'id' => null,
    'container' => true,
    'container_class' => 'container',
    'row' => true,
    'panel' => false,
    'panel_class' => 'panel-default',
    'panel_heading' => null,
    'panel_icon' => null,
];
$options = array_merge($defaults, $options ?? []);
?>

<?php if ($options['container']): ?>
<div class="<?= $options['container_class'] ?>">
<?php endif; ?>

<?php if ($options['row']): ?>
<div class="row">
<?php endif; ?>

<?php if ($options['panel']): ?>
<div class="panel <?= $options['panel_class'] ?>">
    <?php if ($options['panel_heading']): ?>
    <div class="panel-heading">
        <h3 class="panel-title">
            <?php if ($options['panel_icon']): ?>
            <span class="glyphicon glyphicon-<?= $options['panel_icon'] ?>"></span>&nbsp;
            <?php endif; ?>
            <?= lang($options['panel_heading']) ?>
        </h3>
    </div>
    <?php endif; ?>
    <div class="panel-body">
<?php endif; ?>

<div class="<?= $options['class'] ?>"<?= $options['id'] ? ' id="' . $options['id'] . '"' : '' ?>>
    <?php if (isset($content)): ?>
    <?= $content ?>
    <?php elseif (isset($slots) && $slots->has('content')): ?>
    <?= $slots->content ?>
    <?php endif; ?>
</div>

<?php if ($options['panel']): ?>
    </div>
</div>
<?php endif; ?>

<?php if ($options['row']): ?>
</div>
<?php endif; ?>

<?php if ($options['container']): ?>
</div>
<?php endif; ?>
