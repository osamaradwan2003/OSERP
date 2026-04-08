<?php
/**
 * Reusable button group component
 *
 * @var array $buttons Array of button configurations
 *   Each button: [
 *     'label' => string, Translation key
 *     'icon' => string, Glyphicon class
 *     'class' => string, Button class
 *     'href' => string, Link URL
 *     'onclick' => string, JavaScript handler
 *     'modal' => bool, Whether opens in modal
 *     'data' => array, Additional data attributes
 *     'id' => string, Button ID
 *     'disabled' => bool, Whether button is disabled
 *   ]
 * @var array $options Additional options:
 *   - class: string Additional CSS classes for group
 *   - size: string Button size ('xs', 'sm', 'md', 'lg')
 *   - vertical: bool Whether to stack buttons vertically
 *   - justified: bool Whether buttons should be justified
 */

$defaults = [
    'class' => '',
    'size' => 'sm',
    'vertical' => false,
    'justified' => false,
];
$options = array_merge($defaults, $options ?? []);

$group_class = $options['vertical'] ? 'btn-group-vertical' : 'btn-group';
if ($options['justified']) {
    $group_class .= ' btn-group-justified';
}
$size_class = $options['size'] === 'md' ? '' : 'btn-group-' . $options['size'];
?>

<div class="<?= $group_class ?> <?= $size_class ?> <?= $options['class'] ?>">
    <?php foreach ($buttons ?? [] as $button): ?>
    <?php
    $btn_class = $button['class'] ?? 'btn-default';
    $btn_size_class = $options['size'] === 'md' ? '' : 'btn-' . $options['size'];
    $btn_attrs = ['class' => "btn {$btn_class} {$btn_size_class}"];

    if ($button['modal'] ?? false) {
        $btn_attrs['class'] .= ' modal-dlg';
    }
    if (!empty($button['id'])) {
        $btn_attrs['id'] = $button['id'];
    }
    if (!empty($button['onclick'])) {
        $btn_attrs['onclick'] = $button['onclick'];
    }
    if (!empty($button['disabled'])) {
        $btn_attrs['disabled'] = 'disabled';
    }
    foreach ($button['data'] ?? [] as $key => $value) {
        $btn_attrs['data-' . $key] = $value;
    }
    ?>

    <?php if (!empty($button['href'])): ?>
    <a href="<?= $button['href'] ?>" <?= stringify_attributes($btn_attrs) ?>>
        <?php if (!empty($button['icon'])): ?>
        <span class="glyphicon glyphicon-<?= $button['icon'] ?>"></span>&nbsp;
        <?php endif; ?>
        <?= lang($button['label']) ?>
    </a>
    <?php else: ?>
    <button type="button" <?= stringify_attributes($btn_attrs) ?>>
        <?php if (!empty($button['icon'])): ?>
        <span class="glyphicon glyphicon-<?= $button['icon'] ?>"></span>&nbsp;
        <?php endif; ?>
        <?= lang($button['label']) ?>
    </button>
    <?php endif; ?>
    <?php endforeach; ?>
</div>
