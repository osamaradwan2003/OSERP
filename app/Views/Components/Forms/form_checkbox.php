<?php
/**
 * Reusable checkbox component
 *
 * @var string $name Field name
 * @var string $label Label text (translation key)
 * @var bool $checked Whether checkbox is checked
 * @var array $options Additional options:
 *   - value: string Checkbox value (default: 1)
 *   - class: string Additional CSS classes
 *   - disabled: bool Whether field is disabled
 *   - label_class: string Label wrapper class
 *   - input_class: string Input wrapper class
 *   - help_text: string Help text below checkbox
 *   - toggle: bool Whether to use bootstrap-toggle
 *   - toggle_style: string Toggle style (default: 'btn-sm')
 *   - data_on: string Text for toggle on state
 *   - data_off: string Text for toggle off state
 */

$defaults = [
    'value' => '1',
    'class' => '',
    'disabled' => false,
    'label_class' => 'col-xs-3',
    'input_class' => 'col-xs-8',
    'help_text' => null,
    'toggle' => false,
    'toggle_style' => 'btn-sm',
    'data_on' => null,
    'data_off' => null,
];
$options = array_merge($defaults, $options ?? []);

$checkbox_attrs = [
    'id' => $name,
    'name' => $name,
    'value' => $options['value'],
    'class' => 'checkbox-toggle ' . $options['class'],
];
if ($checked) {
    $checkbox_attrs['checked'] = 'checked';
}
if ($options['disabled']) {
    $checkbox_attrs['disabled'] = 'disabled';
}
if ($options['toggle']) {
    $checkbox_attrs['data-toggle'] = 'toggle';
    $checkbox_attrs['data-style'] = $options['toggle_style'];
    if ($options['data_on']) {
        $checkbox_attrs['data-on'] = lang($options['data_on']);
    }
    if ($options['data_off']) {
        $checkbox_attrs['data-off'] = lang($options['data_off']);
    }
}
?>

<div class="form-group form-group-sm">
    <?= form_label(lang($label), $name, ['class' => 'control-label ' . $options['label_class']]) ?>
    <div class="<?= $options['input_class'] ?>">
        <?= form_checkbox($checkbox_attrs) ?>
        <?php if ($options['help_text']): ?>
        <span class="help-block"><?= lang($options['help_text']) ?></span>
        <?php endif; ?>
    </div>
</div>
