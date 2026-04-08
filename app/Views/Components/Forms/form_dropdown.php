<?php
/**
 * Reusable dropdown/select component
 *
 * @var string $name Field name
 * @var string $label Label text (translation key)
 * @var array $options Select options [value => label]
 * @var mixed $selected Selected value
 * @var array $attrs Additional attributes:
 *   - required: bool Whether field is required
 *   - class: string Additional CSS classes
 *   - label_class: string Label width class
 *   - input_class: string Select wrapper width class
 *   - empty_option: string Add empty option with this text
 *   - multiple: bool Whether multiple select
 *   - onchange: string JavaScript onchange handler
 *   - disabled: bool Whether field is disabled
 *   - selectpicker: bool Whether to use bootstrap-select
 *   - data_width: string Data width for selectpicker
 *   - data_live_search: bool Enable live search for selectpicker
 */

$defaults = [
    'required' => false,
    'class' => '',
    'label_class' => 'col-xs-3',
    'input_class' => 'col-xs-8',
    'empty_option' => null,
    'multiple' => false,
    'onchange' => null,
    'disabled' => false,
    'selectpicker' => false,
    'data_width' => '100%',
    'data_live_search' => false,
];
$attrs = array_merge($defaults, $attrs ?? []);

$label_attrs = ['class' => 'control-label ' . $attrs['label_class']];
if ($attrs['required']) {
    $label_attrs['class'] .= ' required';
}

$select_class = 'form-control input-sm ' . $attrs['class'];
if ($attrs['selectpicker']) {
    $select_class .= ' selectpicker show-menu-arrow';
}

$select_attrs = [
    'id' => $name,
    'class' => $select_class,
];
if ($attrs['multiple']) {
    $select_attrs['multiple'] = 'multiple';
}
if ($attrs['onchange']) {
    $select_attrs['onchange'] = $attrs['onchange'];
}
if ($attrs['disabled']) {
    $select_attrs['disabled'] = 'disabled';
}
if ($attrs['selectpicker']) {
    $select_attrs['data-width'] = $attrs['data_width'];
    if ($attrs['data_live_search']) {
        $select_attrs['data-live-search'] = 'true';
    }
}

// Add empty option if specified
if ($attrs['empty_option'] && !$attrs['multiple']) {
    $options = ['' => lang($attrs['empty_option'])] + $options;
}
?>

<div class="form-group form-group-sm">
    <?= form_label(lang($label), $name, $label_attrs) ?>
    <div class="<?= $attrs['input_class'] ?>">
        <?= form_dropdown($name, $options, $selected, $select_attrs) ?>
    </div>
</div>
