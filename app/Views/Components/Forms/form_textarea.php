<?php
/**
 * Reusable textarea component
 *
 * @var string $name Field name
 * @var string $label Label text (translation key)
 * @var string $value Field value
 * @var array $options Additional options:
 *   - required: bool Whether field is required
 *   - class: string Additional CSS classes
 *   - placeholder: string Placeholder text
 *   - disabled: bool Whether field is disabled
 *   - readonly: bool Whether field is readonly
 *   - label_class: string Label width class (default: col-xs-3)
 *   - input_class: string Input wrapper width class (default: col-xs-8)
 *   - help_text: string Help text below textarea
 *   - rows: int Number of rows (default: 3)
 *   - maxlength: string Maximum length
 */

$defaults = [
    'required' => false,
    'class' => '',
    'placeholder' => '',
    'disabled' => false,
    'readonly' => false,
    'label_class' => 'col-xs-3',
    'input_class' => 'col-xs-8',
    'help_text' => null,
    'rows' => 3,
    'maxlength' => null,
];
$options = array_merge($defaults, $options ?? []);

$label_attrs = ['class' => 'control-label ' . $options['label_class']];
if ($options['required']) {
    $label_attrs['class'] .= ' required';
}

$textarea_attrs = [
    'name' => $name,
    'id' => $name,
    'class' => 'form-control input-sm ' . $options['class'],
    'rows' => $options['rows'],
];
if ($options['placeholder']) {
    $textarea_attrs['placeholder'] = lang($options['placeholder']);
}
if ($options['disabled']) {
    $textarea_attrs['disabled'] = 'disabled';
}
if ($options['readonly']) {
    $textarea_attrs['readonly'] = 'readonly';
}
if ($options['maxlength'] !== null) {
    $textarea_attrs['maxlength'] = $options['maxlength'];
}
?>

<div class="form-group form-group-sm">
    <?= form_label(lang($label), $name, $label_attrs) ?>
    <div class="<?= $options['input_class'] ?>">
        <?= form_textarea($textarea_attrs, $value ?? '') ?>
        <?php if ($options['help_text']): ?>
        <span class="help-block"><?= lang($options['help_text']) ?></span>
        <?php endif; ?>
    </div>
</div>
