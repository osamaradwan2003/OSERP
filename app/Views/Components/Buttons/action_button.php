<?php
/**
 * Reusable action button component
 *
 * @var string $action Action type ('edit', 'view', 'delete', 'custom')
 * @var string $label Button label (translation key)
 * @var array $options Additional options:
 *   - icon: string Glyphicon class
 *   - class: string Button class (default: 'btn-default')
 *   - size: string Button size ('xs', 'sm', 'md', 'lg')
 *   - href: string Link URL
 *   - modal: bool Whether opens in modal
 *   - data: array Additional data attributes
 *   - title: string Title/tooltip text (translation key)
 *   - id: string Button ID
 *   - onclick: string JavaScript onclick handler
 *   - disabled: bool Whether button is disabled
 *   - confirm: string Confirmation message before action
 */

$defaults = [
    'icon' => null,
    'class' => 'btn-default',
    'size' => 'sm',
    'href' => null,
    'modal' => false,
    'data' => [],
    'title' => null,
    'id' => null,
    'onclick' => null,
    'disabled' => false,
    'confirm' => null,
];
$options = array_merge($defaults, $options ?? []);

// Auto-set icon based on action type
if ($options['icon'] === null) {
    switch ($action) {
        case 'edit':
            $options['icon'] = 'edit';
            break;
        case 'view':
            $options['icon'] = 'eye-open';
            break;
        case 'delete':
            $options['icon'] = 'trash';
            $options['class'] = 'btn-danger';
            break;
        case 'add':
            $options['icon'] = 'plus';
            break;
        case 'save':
            $options['icon'] = 'ok';
            break;
        case 'cancel':
            $options['icon'] = 'remove';
            break;
    }
}

// Auto-set label based on action type
if (!isset($label) && $action) {
    switch ($action) {
        case 'edit':
            $label = 'Common.edit';
            break;
        case 'view':
            $label = 'Common.view';
            break;
        case 'delete':
            $label = 'Common.delete';
            break;
        case 'add':
            $label = 'Common.new';
            break;
        case 'save':
            $label = 'Common.submit';
            break;
        case 'cancel':
            $label = 'Common.cancel';
            break;
    }
}

$size_class = $options['size'] === 'md' ? '' : 'btn-' . $options['size'];
$btn_attrs = [
    'class' => "btn {$options['class']} {$size_class}",
];

if ($options['modal']) {
    $btn_attrs['class'] .= ' modal-dlg';
}
if ($options['id']) {
    $btn_attrs['id'] = $options['id'];
}
if ($options['title']) {
    $btn_attrs['title'] = lang($options['title']);
}
if ($options['disabled']) {
    $btn_attrs['disabled'] = 'disabled';
}
if ($options['onclick']) {
    $btn_attrs['onclick'] = $options['onclick'];
}
if ($options['confirm']) {
    $btn_attrs['onclick'] = "return confirm('" . lang($options['confirm']) . "');" . ($btn_attrs['onclick'] ?? '');
}
foreach ($options['data'] as $key => $value) {
    $btn_attrs['data-' . $key] = $value;
}
?>

<?php if ($options['href']): ?>
<a href="<?= $options['href'] ?>" <?= stringify_attributes($btn_attrs) ?>>
    <?php if ($options['icon']): ?>
    <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
    <?php endif; ?>
    <?= lang($label) ?>
</a>
<?php else: ?>
<button type="button" <?= stringify_attributes($btn_attrs) ?>>
    <?php if ($options['icon']): ?>
    <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
    <?php endif; ?>
    <?= lang($label) ?>
</button>
<?php endif; ?>
