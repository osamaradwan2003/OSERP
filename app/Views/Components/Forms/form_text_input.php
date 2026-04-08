<?php
/**
 * Reusable text input component
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
 *   - icon: string Glyphicon class for input group
 *   - label_class: string Label width class (default: col-xs-3)
 *   - input_class: string Input wrapper width class (default: col-xs-8)
 *   - help_text: string Help text below input
 *   - min: string Minimum value (for number inputs)
 *   - max: string Maximum value (for number inputs)
 *   - step: string Step value (for number inputs)
 *   - maxlength: string Maximum length
 *   - autocomplete: string Autocomplete attribute
 */

$defaults = [
    'required' => false,
    'type' => 'text',
    'class' => '',
    'placeholder' => '',
    'disabled' => false,
    'readonly' => false,
    'icon' => null,
    'label_class' => 'col-xs-3',
    'input_class' => 'col-xs-8',
    'help_text' => null,
    'min' => null,
    'max' => null,
    'step' => null,
    'maxlength' => null,
    'autocomplete' => null,
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
if ($options['min'] !== null) {
    $input_attrs['min'] = $options['min'];
}
if ($options['max'] !== null) {
    $input_attrs['max'] = $options['max'];
}
if ($options['step'] !== null) {
    $input_attrs['step'] = $options['step'];
}
if ($options['maxlength'] !== null) {
    $input_attrs['maxlength'] = $options['maxlength'];
}
if ($options['autocomplete'] !== null) {
    $input_attrs['autocomplete'] = $options['autocomplete'];
}
?>

<div class="form-group form-group-sm">
    <?= form_label(lang($label), $name, $label_attrs) ?>
    <div class="<?= $options['input_class'] ?>">
        <?php if ($options['icon']): ?>
        <div class="input-group">
            <span class="input-group-addon input-sm">
                <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>
            </span>
            <?= form_input($input_attrs) ?>
        </div>
        <?php else: ?>
        <?= form_input($input_attrs) ?>
        <?php endif; ?>
        <?php if ($options['help_text']): ?>
        <span class="help-block"><?= lang($options['help_text']) ?></span>
        <?php endif; ?>
    </div>
</div>
