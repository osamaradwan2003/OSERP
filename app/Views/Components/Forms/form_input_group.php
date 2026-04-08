<?php
/**
 * Reusable input group component (input with icon/addon)
 *
 * @var string $name Field name
 * @var string $label Label text (translation key)
 * @var mixed $value Field value
 * @var array $options Additional options:
 *   - required: bool Whether field is required
 *   - type: string Input type (text, email, number, etc.)
 *   - class: string Additional CSS classes
 *   - placeholder: string Placeholder text
 *   - disabled: bool Whether field is disabled
 *   - readonly: bool Whether field is readonly
 *   - label_class: string Label width class (default: col-xs-3)
 *   - input_class: string Input wrapper width class (default: col-xs-8)
 *   - help_text: string Help text below input
 *   - icon: string Glyphicon class (required)
 *   - icon_position: string Icon position 'left' or 'right' (default: 'left')
 *   - addon_text: string Text to show in addon instead of icon
 *   - button: array Optional button configuration
 */

$defaults = [
    'required' => false,
    'type' => 'text',
    'class' => '',
    'placeholder' => '',
    'disabled' => false,
    'readonly' => false,
    'label_class' => 'col-xs-3',
    'input_class' => 'col-xs-8',
    'help_text' => null,
    'icon' => null,
    'icon_position' => 'left',
    'addon_text' => null,
    'button' => null,
];
$options = array_merge($defaults, $options ?? []);

$label_attrs = ['class' => 'control-label ' . $options['label_class']];
if ($options['required']) {
    $label_attrs['class'] .= ' required';
}

$input_attrs = [
    'name' => $name,
    'id' => $name,
    'class' => 'form-control input-sm ' . $options['class'],
    'type' => $options['type'],
    'value' => $value ?? '',
];
if ($options['placeholder']) {
    $input_attrs['placeholder'] = lang($options['placeholder']);
}
if ($options['disabled']) {
    $input_attrs['disabled'] = 'disabled';
}
if ($options['readonly']) {
    $input_attrs['readonly'] = 'readonly';
}
?>

<div class="form-group form-group-sm">
    <?= form_label(lang($label), $name, $label_attrs) ?>
    <div class="<?= $options['input_class'] ?>">
        <div class="input-group">
            <?php if ($options['icon_position'] === 'left' && ($options['icon'] || $options['addon_text'])): ?>
            <span class="input-group-addon input-sm">
                <?php if ($options['icon']): ?>
                <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>
                <?php elseif ($options['addon_text']): ?>
                <?= lang($options['addon_text']) ?>
                <?php endif; ?>
            </span>
            <?php endif; ?>

            <?= form_input($input_attrs) ?>

            <?php if ($options['icon_position'] === 'right' && ($options['icon'] || $options['addon_text'])): ?>
            <span class="input-group-addon input-sm">
                <?php if ($options['icon']): ?>
                <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>
                <?php elseif ($options['addon_text']): ?>
                <?= lang($options['addon_text']) ?>
                <?php endif; ?>
            </span>
            <?php endif; ?>

            <?php if ($options['button']): ?>
            <span class="input-group-btn">
                <button class="btn btn-default btn-sm" type="button"
                    <?php if (!empty($options['button']['id'])): ?>
                    id="<?= esc($options['button']['id']) ?>"
                    <?php endif; ?>
                    <?php if (!empty($options['button']['onclick'])): ?>
                    onclick="<?= esc($options['button']['onclick']) ?>"
                    <?php endif; ?>>
                    <?php if (!empty($options['button']['icon'])): ?>
                    <span class="glyphicon glyphicon-<?= esc($options['button']['icon']) ?>"></span>
                    <?php endif; ?>
                    <?php if (!empty($options['button']['label'])): ?>
                    <?= lang($options['button']['label']) ?>
                    <?php endif; ?>
                </button>
            </span>
            <?php endif; ?>
        </div>
        <?php if ($options['help_text']): ?>
        <span class="help-block"><?= lang($options['help_text']) ?></span>
        <?php endif; ?>
    </div>
</div>
