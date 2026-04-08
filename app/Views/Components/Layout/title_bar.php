<?php
/**
 * Reusable title bar with action buttons
 *
 * @var array $actions Array of action buttons
 *   Each action: [
 *     'label' => string, Translation key
 *     'icon' => string, Glyphicon class
 *     'class' => string, Button class (default: btn-info)
 *     'href' => string, Link URL
 *     'modal' => bool, Whether opens in modal
 *     'data' => array, Additional data attributes
 *     'title' => string, Title/tooltip text (translation key)
 *     'id' => string, Button ID
 *     'onclick' => string, JavaScript onclick handler
 *   ]
 * @var string $class Additional wrapper classes
 */

$defaults = [
    'class' => '',
];
$options = array_merge($defaults, ['class' => $class ?? '']);
?>

<div id="title_bar" class="btn-toolbar print_hide <?= $options['class'] ?>">
    <?php foreach ($actions ?? [] as $action): ?>
    <?php
    $btn_class = $action['class'] ?? 'btn-info';
    $btn_attrs = ['class' => "btn {$btn_class} btn-sm pull-right"];
    $is_modal = (bool) ($action['modal'] ?? false);
    $has_href = !empty($action['href']);

    if ($is_modal) {
        $btn_attrs['class'] .= ' modal-dlg';
        if (isset($action['data']['btn_submit'])) {
            $btn_attrs['data-btn-submit'] = lang($action['data']['btn_submit']);
        }
        if (isset($action['data']['btn_new'])) {
            $btn_attrs['data-btn-new'] = lang($action['data']['btn_new']);
        }
    }

    if ($has_href) {
        $btn_attrs['href'] = $action['href'];
    }

    if (!empty($action['title'])) {
        $btn_attrs['title'] = lang($action['title']);
    }

    if (!empty($action['id'])) {
        $btn_attrs['id'] = $action['id'];
    }

    if (!empty($action['onclick'])) {
        $btn_attrs['onclick'] = $action['onclick'];
    }
    ?>
    <?php if ($has_href || $is_modal): ?>
    <a <?= stringify_attributes($btn_attrs) ?>>
        <?php if (!empty($action['icon'])): ?>
        <span class="glyphicon glyphicon-<?= $action['icon'] ?>">&nbsp;</span>
        <?php endif; ?>
        <?= lang($action['label']) ?>
    </a>
    <?php else: ?>
    <button <?= stringify_attributes($btn_attrs) ?>>
        <?php if (!empty($action['icon'])): ?>
        <span class="glyphicon glyphicon-<?= $action['icon'] ?>">&nbsp;</span>
        <?php endif; ?>
        <?= lang($action['label']) ?>
    </button>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
