<?php
/**
 * Reusable delete button component
 *
 * @var string $label Button label (translation key, default: 'Common.delete')
 * @var array $options Additional options:
 *   - icon: string Glyphicon class (default: 'trash')
 *   - class: string Additional button classes
 *   - size: string Button size ('xs', 'sm', 'md', 'lg')
 *   - id: string Button ID
 *   - href: string Link URL (for link buttons)
 *   - onclick: string JavaScript onclick handler
 *   - confirm: string Confirmation message (translation key)
 *   - data: array Additional data attributes
 *   - disabled: bool Whether button is disabled
 *   - bulk: bool Whether this is a bulk delete button
 *   - controller: string Controller name for bulk delete
 */

$defaults = [
    'icon' => 'trash',
    'class' => 'btn-danger',
    'size' => 'sm',
    'id' => 'delete',
    'href' => null,
    'onclick' => null,
    'confirm' => 'Common.confirm_delete',
    'data' => [],
    'disabled' => false,
    'bulk' => false,
    'controller' => null,
];
$options = array_merge($defaults, $options ?? []);

$label = $label ?? 'Common.delete';
$size_class = $options['size'] === 'md' ? '' : 'btn-' . $options['size'];
$btn_attrs = [
    'class' => "btn {$options['class']} {$size_class}",
];

if ($options['id']) {
    $btn_attrs['id'] = $options['id'];
}
if ($options['disabled']) {
    $btn_attrs['disabled'] = 'disabled';
}
if ($options['bulk']) {
    $btn_attrs['class'] .= ' print_hide';
}
foreach ($options['data'] as $key => $value) {
    $btn_attrs['data-' . $key] = $value;
}
?>

<?php if ($options['href']): ?>
<a href="<?= $options['href'] ?>" <?= stringify_attributes($btn_attrs) ?>
   onclick="return confirm('<?= lang($options['confirm']) ?>')">
    <?php if ($options['icon']): ?>
    <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
    <?php endif; ?>
    <?= lang($label) ?>
</a>
<?php else: ?>
<button type="button" <?= stringify_attributes($btn_attrs) ?>>
    <?php if ($options['icon']): ?>
    <span class="glyphicon glyphicon-<?= $options['icon'] ?>"></span>&nbsp;
    <?php endif; ?>
    <?= lang($label) ?>
</button>
<?php endif; ?>

<?php if ($options['bulk']): ?>
<script type="text/javascript">
(function() {
    var deleteBtn = document.getElementById('<?= $options['id'] ?>');
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function() {
            var selectedIds = table_support.selected_ids();
            if (selectedIds.length === 0) {
                alert('<?= lang('Common.select_at_least_one') ?>');
                return false;
            }
            if (!confirm('<?= lang($options['confirm']) ?>')) {
                return false;
            }
            <?php if ($options['controller']): ?>
            $.post('<?= site_url($options['controller'] . '/delete') ?>', {
                'ids[]': selectedIds,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }, function(response) {
                table_support.refresh();
                $.notify(response.message, { type: response.success ? 'success' : 'danger' });
            }, 'json');
            <?php endif; ?>
        });
    }
})();
</script>
<?php endif; ?>
