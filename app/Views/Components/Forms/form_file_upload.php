<?php
/**
 * Reusable file upload component
 *
 * @var string $name Field name
 * @var string $label Label text (translation key)
 * @var array $options Additional options:
 *   - required: bool Whether field is required
 *   - class: string Additional CSS classes
 *   - label_class: string Label width class (default: col-xs-3)
 *   - input_class: string Input wrapper width class (default: col-xs-8)
 *   - help_text: string Help text below input
 *   - accept: string Accepted file types (e.g., '.csv,.xlsx')
 *   - multiple: bool Whether multiple files can be uploaded
 *   - disabled: bool Whether field is disabled
 */

$defaults = [
    'required' => false,
    'class' => '',
    'label_class' => 'col-xs-3',
    'input_class' => 'col-xs-8',
    'help_text' => null,
    'accept' => null,
    'multiple' => false,
    'disabled' => false,
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
    'type' => 'file',
];
if ($options['accept']) {
    $input_attrs['accept'] = $options['accept'];
}
if ($options['multiple']) {
    $input_attrs['multiple'] = 'multiple';
}
if ($options['disabled']) {
    $input_attrs['disabled'] = 'disabled';
}
?>

<div class="form-group form-group-sm">
    <?= form_label(lang($label), $name, $label_attrs) ?>
    <div class="<?= $options['input_class'] ?>">
        <?= form_input($input_attrs) ?>
        <?php if ($options['help_text']): ?>
        <span class="help-block"><?= lang($options['help_text']) ?></span>
        <?php endif; ?>
    </div>
</div>
