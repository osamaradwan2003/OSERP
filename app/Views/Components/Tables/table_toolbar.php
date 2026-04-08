<?php
/**
 * Reusable table toolbar with filters and actions
 *
 * @var array $actions Action buttons (delete, bulk edit, etc.)
 *   Each action: [
 *     'label' => string, Translation key
 *     'icon' => string, Glyphicon class
 *     'class' => string, Button class
 *     'id' => string, Button ID
 *     'onclick' => string, JavaScript handler
 *     'modal' => bool, Whether opens in modal
 *     'data' => array, Additional data attributes
 *   ]
 * @var array $filters Filter configurations
 *   Each filter: [
 *     'type' => string, 'select', 'multiselect', 'daterange', 'text'
 *     'name' => string, Filter name
 *     'options' => array, Options for select/multiselect
 *     'selected' => mixed, Selected value(s)
 *     'placeholder' => string, Placeholder text
 *     'class' => string, Additional CSS classes
 *   ]
 * @var array $options Additional options:
 *   - class: string Additional CSS classes
 *   - id: string Toolbar ID (default: 'toolbar')
 *   - print_hide: bool Whether to hide on print
 */

$defaults = [
    'class' => '',
    'id' => 'toolbar',
    'print_hide' => true,
];
$options = array_merge($defaults, $options ?? []);

$toolbar_class = 'pull-left form-inline';
if ($options['print_hide']) {
    $toolbar_class .= ' print_hide';
}
?>

<div id="<?= $options['id'] ?>" class="<?= $toolbar_class ?>" role="toolbar">
    <?php if (!empty($actions)): ?>
    <?php foreach ($actions as $action): ?>
    <?php
    $btn_class = $action['class'] ?? 'btn-default';
    $btn_attrs = ['class' => "btn {$btn_class} btn-sm"];
    if ($options['print_hide']) {
        $btn_attrs['class'] .= ' print_hide';
    }
    if (!empty($action['id'])) {
        $btn_attrs['id'] = $action['id'];
    }
    if (!empty($action['onclick'])) {
        $btn_attrs['onclick'] = $action['onclick'];
    }
    if ($action['modal'] ?? false) {
        $btn_attrs['class'] .= ' modal-dlg';
    }
    foreach ($action['data'] ?? [] as $key => $value) {
        $btn_attrs['data-' . $key] = $value;
    }
    ?>
    <button <?= stringify_attributes($btn_attrs) ?>>
        <?php if (!empty($action['icon'])): ?>
        <span class="glyphicon glyphicon-<?= $action['icon'] ?>"></span>&nbsp;
        <?php endif; ?>
        <?= lang($action['label']) ?>
    </button>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($filters)): ?>
    <?php foreach ($filters as $filter): ?>
    <?php
    switch ($filter['type'] ?? 'text'):
        case 'daterange':
    ?>
    <?= form_input([
        'name' => $filter['name'] ?? 'daterangepicker',
        'class' => 'form-control input-sm',
        'id' => $filter['name'] ?? 'daterangepicker',
    ]) ?>
    <?php
            break;
        case 'select':
    ?>
    <?= form_dropdown(
        $filter['name'],
        $filter['options'] ?? [],
        $filter['selected'] ?? null,
        [
            'id' => $filter['name'],
            'class' => 'selectpicker show-menu-arrow ' . ($filter['class'] ?? ''),
            'data-style' => 'btn-default btn-sm',
            'data-width' => 'fit',
        ]
    ) ?>
    <?php
            break;
        case 'multiselect':
    ?>
    <?= form_multiselect(
        $filter['name'] . '[]',
        $filter['options'] ?? [],
        $filter['selected'] ?? [],
        [
            'id' => $filter['name'],
            'class' => 'selectpicker show-menu-arrow ' . ($filter['class'] ?? ''),
            'data-none-selected-text' => lang($filter['placeholder'] ?? 'Common.none_selected_text'),
            'data-selected-text-format' => 'count > 1',
            'data-style' => 'btn-default btn-sm',
            'data-width' => 'fit',
        ]
    ) ?>
    <?php
            break;
        default:
    ?>
    <?= form_input([
        'name' => $filter['name'],
        'id' => $filter['name'],
        'class' => 'form-control input-sm ' . ($filter['class'] ?? ''),
        'placeholder' => lang($filter['placeholder'] ?? ''),
        'value' => $filter['selected'] ?? '',
    ]) ?>
    <?php
            break;
    endswitch;
    ?>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
