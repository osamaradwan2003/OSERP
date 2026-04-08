<?php
/**
 * Reusable form section wrapper component
 *
 * @var string $title Section title (translation key)
 * @var string $content Section content (can be passed or rendered inside)
 * @var array $options Additional options:
 *   - collapsible: bool Whether section is collapsible
 *   - collapsed: bool Whether section starts collapsed
 *   - class: string Additional CSS classes for section
 *   - id: string Section ID
 *   - icon: string Glyphicon class for section header
 */

$defaults = [
    'collapsible' => false,
    'collapsed' => false,
    'class' => '',
    'id' => null,
    'icon' => null,
];
$options = array_merge($defaults, $options ?? []);

$section_id = $options['id'] ?? 'section_' . uniqid();
$panel_class = 'panel panel-default ' . $options['class'];
?>

<div class="<?= $panel_class ?>" id="<?= $section_id ?>">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?php if ($options['collapsible']): ?>
            <a data-toggle="collapse" data-target="#<?= $section_id ?>_body"
               href="javascript:void(0)"
               <?= $options['collapsed'] ? 'class="collapsed"' : '' ?>>
                <?php if ($options['icon']): ?>
                <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
                <?php endif; ?>
                <?= lang($title) ?>
                <span class="pull-right glyphicon glyphicon-chevron-<?= $options['collapsed'] ? 'right' : 'down' ?>"></span>
            </a>
            <?php else: ?>
            <?php if ($options['icon']): ?>
            <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
            <?php endif; ?>
            <?= lang($title) ?>
            <?php endif; ?>
        </h3>
    </div>
    <div id="<?= $section_id ?>_body" class="panel-collapse collapse <?= $options['collapsed'] ? '' : 'in' ?>">
        <div class="panel-body">
            <?php if (isset($content)): ?>
            <?= $content ?>
            <?php elseif (isset($slots) && $slots->has('content')): ?>
            <?= $slots->content ?>
            <?php endif; ?>
        </div>
    </div>
</div>
