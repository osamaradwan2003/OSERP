<?php
/**
 * Reusable submit button component
 *
 * @var string $label Button label (translation key, default: 'Common.submit')
 * @var array $options Additional options:
 *   - icon: string Glyphicon class (default: 'ok')
 *   - class: string Button class (default: 'btn-primary')
 *   - size: string Button size ('xs', 'sm', 'md', 'lg')
 *   - id: string Button ID
 *   - name: string Button name attribute
 *   - disabled: bool Whether button is disabled
 *   - loading_text: string Text to show while loading (translation key)
 *   - form: string Form ID to submit
 *   - onclick: string JavaScript onclick handler
 */

$defaults = [
    'icon' => 'ok',
    'class' => 'btn-primary',
    'size' => 'sm',
    'id' => 'submit',
    'name' => 'submit',
    'disabled' => false,
    'loading_text' => 'Common.saving',
    'form' => null,
    'onclick' => null,
];
$options = array_merge($defaults, $options ?? []);

$label = $label ?? 'Common.submit';
$size_class = $options['size'] === 'md' ? '' : 'btn-' . $options['size'];
$btn_attrs = [
    'type' => 'submit',
    'class' => "btn {$options['class']} {$size_class}",
];

if ($options['id']) {
    $btn_attrs['id'] = $options['id'];
}
if ($options['name']) {
    $btn_attrs['name'] = $options['name'];
}
if ($options['disabled']) {
    $btn_attrs['disabled'] = 'disabled';
}
if ($options['form']) {
    $btn_attrs['form'] = $options['form'];
}
if ($options['onclick']) {
    $btn_attrs['onclick'] = $options['onclick'];
}
?>

<button <?= stringify_attributes($btn_attrs) ?>>
    <?php if ($options['icon']): ?>
    <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
    <?php endif; ?>
    <?= lang($label) ?>
</button>

<?php if ($options['loading_text']): ?>
<script type="text/javascript">
(function() {
    var btn = document.getElementById('<?= $options['id'] ?>');
    if (btn) {
        btn.addEventListener('click', function() {
            var originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<span class="glyphicon glyphicon-refresh spinning"></span>&nbsp;<?= lang($options['loading_text']) ?>';
            // Re-enable after form submission completes (handled by form submit)
            setTimeout(function() {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }, 5000);
        });
    }
})();
</script>
<?php endif; ?>
