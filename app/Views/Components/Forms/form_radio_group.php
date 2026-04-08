<?php
/**
 * Reusable radio button group component
 *
 * @var string $name Field name
 * @var string $label Label text (translation key)
 * @var array $options Radio options [value => label]
 * @var mixed $selected Selected value
 * @var array $attrs Additional attributes:
 *   - required: bool Whether field is required
 *   - class: string Additional CSS classes
 *   - label_class: string Label width class
 *   - input_class: string Radio wrapper width class
 *   - inline: bool Whether to display radios inline
 *   - disabled: bool Whether field is disabled
 *   - help_text: string Help text below radio group
 */

$defaults = [
    'required' => false,
    'class' => '',
    'label_class' => 'col-xs-3',
    'input_class' => 'col-xs-8',
    'inline' => true,
    'disabled' => false,
    'help_text' => null,
];
$attrs = array_merge($defaults, $attrs ?? []);

$label_attrs = ['class' => 'control-label ' . $attrs['label_class']];
if ($attrs['required']) {
    $label_attrs['class'] .= ' required';
}

$radio_wrapper_class = $attrs['inline'] ? 'radio-inline' : 'radio';
?>

<div class="form-group form-group-sm">
    <?= form_label(lang($label), $name, $label_attrs) ?>
    <div class="<?= $attrs['input_class'] ?>">
        <?php foreach ($options as $value => $option_label): ?>
        <?php
        $radio_attrs = [
            'name' => $name,
            'id' => $name . '_' . $value,
            'value' => $value,
            'class' => $attrs['class'],
        ];
        if ($selected == $value) {
            $radio_attrs['checked'] = 'checked';
        }
        if ($attrs['disabled']) {
            $radio_attrs['disabled'] = 'disabled';
        }
        ?>
        <?php if ($attrs['inline']): ?>
        <label class="<?= $radio_wrapper_class ?>">
            <?= form_radio($radio_attrs) ?>
            <?= lang($option_label) ?>
        </label>
        <?php else: ?>
        <div class="<?= $radio_wrapper_class ?>">
            <label>
                <?= form_radio($radio_attrs) ?>
                <?= lang($option_label) ?>
            </label>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php if ($attrs['help_text']): ?>
        <span class="help-block"><?= lang($attrs['help_text']) ?></span>
        <?php endif; ?>
    </div>
</div>
