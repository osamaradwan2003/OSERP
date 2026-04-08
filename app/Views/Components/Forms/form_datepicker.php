<?php
/**
 * Reusable date/datetime picker component
 *
 * @var string $name Field name
 * @var string $label Label text (translation key)
 * @var mixed $value Field value
 * @var array $options Additional options:
 *   - required: bool Whether field is required
 *   - class: string Additional CSS classes
 *   - placeholder: string Placeholder text
 *   - disabled: bool Whether field is disabled
 *   - readonly: bool Whether field is readonly
 *   - label_class: string Label width class (default: col-xs-3)
 *   - input_class: string Input wrapper width class (default: col-xs-8)
 *   - help_text: string Help text below input
 *   - format: string Date format (default: 'Y-m-d')
 *   - timepicker: bool Whether to include time picker
 *   - icon: string Glyphicon class (default: 'calendar')
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
    'format' => 'Y-m-d',
    'timepicker' => false,
    'icon' => 'calendar',
];
$options = array_merge($defaults, $options ?? []);

$label_attrs = ['class' => 'control-label ' . $options['label_class']];
if ($options['required']) {
    $label_attrs['class'] .= ' required';
}

$input_attrs = [
    'name' => $name,
    'id' => $name,
    'class' => 'form-control input-sm date-picker ' . $options['class'],
    'type' => 'text',
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

// Add datetime picker class if timepicker is enabled
if ($options['timepicker']) {
    $input_attrs['class'] .= ' datetime-picker';
}
?>

<div class="form-group form-group-sm">
    <?= form_label(lang($label), $name, $label_attrs) ?>
    <div class="<?= $options['input_class'] ?>">
        <div class="input-group">
            <span class="input-group-addon input-sm">
                <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>
            </span>
            <?= form_input($input_attrs) ?>
        </div>
        <?php if ($options['help_text']): ?>
        <span class="help-block"><?= lang($options['help_text']) ?></span>
        <?php endif; ?>
    </div>
</div>

<?php // JavaScript initialization is handled by the existing datepicker_locale.php partial ?>
