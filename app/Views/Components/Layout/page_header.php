<?php
/**
 * Reusable page header component
 *
 * @var string $title Page title (translation key)
 * @var string $subtitle Optional subtitle (translation key)
 * @var array $options Additional options:
 *   - icon: string Glyphicon class for header
 *   - class: string Additional CSS classes
 *   - breadcrumbs: array Breadcrumb items [['label' => 'Key', 'href' => 'url']]
 *   - actions: array Action buttons to display in header
 */

$defaults = [
    'icon' => null,
    'class' => '',
    'breadcrumbs' => null,
    'actions' => null,
];
$options = array_merge($defaults, $options ?? []);
?>

<div class="page-header <?= $options['class'] ?>">
    <?php if ($options['breadcrumbs']): ?>
    <ol class="breadcrumb">
        <?php foreach ($options['breadcrumbs'] as $crumb): ?>
        <?php if (!empty($crumb['href'])): ?>
        <li><a href="<?= $crumb['href'] ?>"><?= lang($crumb['label']) ?></a></li>
        <?php else: ?>
        <li class="active"><?= lang($crumb['label']) ?></li>
        <?php endif; ?>
        <?php endforeach; ?>
    </ol>
    <?php endif; ?>

    <h1>
        <?php if ($options['icon']): ?>
        <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
        <?php endif; ?>
        <?= lang($title) ?>
        <?php if (isset($subtitle)): ?>
        <small><?= lang($subtitle) ?></small>
        <?php endif; ?>
    </h1>

    <?php if ($options['actions']): ?>
    <div class="header-actions pull-right">
        <?php foreach ($options['actions'] as $action): ?>
        <?php
        $btn_class = $action['class'] ?? 'btn-default';
        $btn_attrs = ['class' => "btn {$btn_class} btn-sm"];
        if (!empty($action['href'])) {
            $btn_attrs['href'] = $action['href'];
        }
        if (!empty($action['id'])) {
            $btn_attrs['id'] = $action['id'];
        }
        if (!empty($action['modal'])) {
            $btn_attrs['class'] .= ' modal-dlg';
        }
        ?>
        <?php if (!empty($action['href'])): ?>
        <a <?= stringify_attributes($btn_attrs) ?>>
            <?php if (!empty($action['icon'])): ?>
            <span class="glyphicon glyphicon-<?= $action['icon'] ?>"></span>&nbsp;
            <?php endif; ?>
            <?= lang($action['label']) ?>
        </a>
        <?php else: ?>
        <button <?= stringify_attributes($btn_attrs) ?>>
            <?php if (!empty($action['icon'])): ?>
            <span class="glyphicon glyphicon-<?= $action['icon'] ?>"></span>&nbsp;
            <?php endif; ?>
            <?= lang($action['label']) ?>
        </button>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
