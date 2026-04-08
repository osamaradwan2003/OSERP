<?php
/**
 * Reusable bootstrap table wrapper component
 *
 * @var string $id Table ID (default: 'table')
 * @var array $options Additional options:
 *   - class: string Additional CSS classes
 *   - toolbar: string Toolbar ID to associate
 *   - data_url: string Data fetch URL
 *   - unique_id: string Unique identifier column
 *   - page_size: int Default page size
 *   - sort_name: string Default sort column
 *   - sort_order: string Default sort order ('asc' or 'desc')
 *   - pagination: bool Enable pagination
 *   - search: bool Enable search
 *   - show_columns: bool Show column toggle
 *   - show_export: bool Show export button
 *   - show_refresh: bool Show refresh button
 *   - sticky_header: bool Enable sticky header
 *   - card_view: bool Enable card view for mobile
 */

$defaults = [
    'class' => '',
    'toolbar' => '#toolbar',
    'data_url' => null,
    'unique_id' => null,
    'page_size' => 25,
    'sort_name' => null,
    'sort_order' => 'asc',
    'pagination' => true,
    'search' => true,
    'show_columns' => true,
    'show_export' => true,
    'show_refresh' => true,
    'sticky_header' => true,
    'card_view' => false,
];
$options = array_merge($defaults, $options ?? []);

$table_id = $id ?? 'table';
?>

<div id="table_holder" class="<?= $options['class'] ?>">
    <table id="<?= $table_id ?>"></table>
</div>

<script type="text/javascript">
(function() {
    var tableOptions = {
        toolbar: '<?= $options['toolbar'] ?>',
        <?= $options['data_url'] ? "url: '" . $options['data_url'] . "'," : '' ?>
        uniqueId: '<?= $options['unique_id'] ?? 'id' ?>',
        pagination: <?= $options['pagination'] ? 'true' : 'false' ?>,
        pageSize: <?= $options['page_size'] ?>,
        search: <?= $options['search'] ? 'true' : 'false' ?>,
        showColumns: <?= $options['show_columns'] ? 'true' : 'false' ?>,
        showExport: <?= $options['show_export'] ? 'true' : 'false' ?>,
        showRefresh: <?= $options['show_refresh'] ? 'true' : 'false' ?>,
        <?= $options['sort_name'] ? "sortName: '" . $options['sort_name'] . "'," : '' ?>
        sortOrder: '<?= $options['sort_order'] ?>',
        stickyHeader: <?= $options['sticky_header'] ? 'true' : 'false' ?>,
        cardView: <?= $options['card_view'] ? 'true' : 'false' ?>,
        sidePagination: 'server',
        silentSort: false,
        icons: {
            refresh: 'glyphicon-refresh icon-refresh',
            columns: 'glyphicon-th icon-th',
            export: 'glyphicon-export icon-export'
        }
    };

    $('#<?= $table_id ?>').bootstrapTable(tableOptions);
})();
</script>
