<?php
/**
 * Reusable table row actions component
 *
 * @var array $actions Array of action configurations
 *   Each action: [
 *     'type' => string, 'edit', 'view', 'delete', 'custom'
 *     'label' => string, Translation key
 *     'icon' => string, Glyphicon class
 *     'class' => string, Button class
 *     'href' => string, Link URL (can use {id} placeholder)
 *     'modal' => bool, Whether opens in modal
 *     'data' => array, Additional data attributes
 *     'confirm' => string, Confirmation message
 *     'onclick' => string, JavaScript handler
 *   ]
 * @var mixed $row_id The row ID for action URLs
 * @var array $options Additional options:
 *   - class: string Additional CSS classes for wrapper
 *   - size: string Button size ('xs', 'sm', 'md')
 */

$defaults = [
    'class' => '',
    'size' => 'xs',
];
$options = array_merge($defaults, $options ?? []);

$size_class = $options['size'] === 'md' ? '' : 'btn-' . $options['size'];
?>

<div class="table-actions <?= $options['class'] ?>">
    <?php foreach ($actions ?? [] as $action): ?>
    <?php
    // Auto-configure based on action type
    $action_type = $action['type'] ?? 'custom';
    $action_class = $action['class'] ?? 'btn-default';
    $action_icon = $action['icon'] ?? null;
    $action_label = $action['label'] ?? null;

    switch ($action_type) {
        case 'edit':
            $action_icon = $action_icon ?? 'edit';
            $action_label = $action_label ?? 'Common.edit';
            break;
        case 'view':
            $action_icon = $action_icon ?? 'eye-open';
            $action_label = $action_label ?? 'Common.view';
            break;
        case 'delete':
            $action_icon = $action_icon ?? 'trash';
            $action_class = $action['class'] ?? 'btn-danger';
            $action_label = $action_label ?? 'Common.delete';
            break;
    }

    $btn_attrs = ['class' => "btn {$action_class} {$size_class}"];

    if ($action['modal'] ?? false) {
        $btn_attrs['class'] .= ' modal-dlg';
    }

    // Replace {id} placeholder in href
    $href = $action['href'] ?? null;
    if ($href && isset($row_id)) {
        $href = str_replace('{id}', $row_id, $href);
    }

    foreach ($action['data'] ?? [] as $key => $value) {
        $btn_attrs['data-' . $key] = $value;
    }

    $onclick = $action['onclick'] ?? null;
    if ($action['confirm'] ?? null) {
        $onclick = "if(!confirm('" . lang($action['confirm']) . "')) return false;" . ($onclick ?? '');
    }
    if ($onclick) {
        $btn_attrs['onclick'] = $onclick;
    }
    ?>

    <?php if ($href): ?>
    <a href="<?= $href ?>" <?= stringify_attributes($btn_attrs) ?> title="<?= lang($action_label) ?>">
        <?php if ($action_icon): ?>
        <span class="glyphicon glyphicon-<?= $action_icon ?>"></span>
        <?php endif; ?>
        <?php if (isset($action['show_label']) && $action['show_label']): ?>
        <?= lang($action_label) ?>
        <?php endif; ?>
    </a>
    <?php else: ?>
    <button type="button" <?= stringify_attributes($btn_attrs) ?> title="<?= lang($action_label) ?>">
        <?php if ($action_icon): ?>
        <span class="glyphicon glyphicon-<?= $action_icon ?>"></span>
        <?php endif; ?>
        <?php if (isset($action['show_label']) && $action['show_label']): ?>
        <?= lang($action_label) ?>
        <?php endif; ?>
    </button>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
