<?php
/**
 * Items Management View
 *
 * @var string $controller_name
 * @var string $table_headers
 * @var string $table_headers_all
 * @var string $table_headers_single
 * @var array $filters
 * @var array $stock_locations
 * @var int $stock_location
 * @var array $config
 * @var string|null $start_date
 * @var string|null $end_date
 * @var array $selected_filters
 */

use App\Models\Employee;
?>

<?= view('partial/header') ?>

<script type="text/javascript">
$(document).ready(function() {
	const tableHeadersAll = <?= $table_headers_all ?>;
	const tableHeadersSingle = <?= $table_headers_single ?>;
	var show_deleted = new URLSearchParams(window.location.search).get('show_deleted') === '1';
	const updateTableColumnsByStockLocation = function() {
		const stockLocation = parseInt($('#stock_location').val(), 10);
		const isAllStores = stockLocation === -1;
		$('#table').bootstrapTable('refreshOptions', {
			columns: isAllStores ? tableHeadersAll : tableHeadersSingle
		});
	};

	// Refresh table when filters are changed and dropdown closes
	$('#filters').on('hidden.bs.select', function(e) {
		table_support.refresh();
	});

	// Refresh table when stock location changes
	$('#stock_location').on('changed.bs.select change', function(e) {
		updateTableColumnsByStockLocation();
		table_support.refresh();
	});

	$('#generate_barcodes').click(function() {
		window.open(
			'index.php/items/generateBarcodes/' + table_support.selected_ids().join(':'),
			'_blank'
		);
	});

	// Load the preset daterange picker
	<?= view('partial/daterangepicker') ?>
	// Set the beginning of time as starting date
	$('#daterangepicker').data('daterangepicker').setStartDate("<?= date($config['dateformat'], mktime(0, 0, 0, 01, 01, 2010)) ?>");
	// Update the hidden inputs with the selected dates before submitting the search data
	var start_date = "<?= date('Y-m-d', mktime(0, 0, 0, 01, 01, 2010)) ?>";
	var end_date = "<?= date('Y-m-d') ?>";

	// Override dates from server if provided
	<?php if (isset($start_date) && $start_date): ?>
	start_date = "<?= esc($start_date) ?>";
	<?php endif; ?>
	<?php if (isset($end_date) && $end_date): ?>
	end_date = "<?= esc($end_date) ?>";
	<?php endif; ?>

	<?php
	echo view('partial/bootstrap_tables_locale');
	$employee = model(Employee::class);
	?>

	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
		table_support.refresh();
	});

	table_support.init({
		employee_id: <?= $employee->get_logged_in_employee_info()->person_id ?>,
		resource: '<?= esc($controller_name) ?>',
		headers: <?= $table_headers ?>,
		pageSize: <?= $config['lines_per_page'] ?>,
		uniqueId: 'items.item_id',
		queryParams: function() {
			return $.extend(arguments[0], {
				"start_date": start_date,
				"end_date": end_date,
				"stock_location": $("#stock_location").val(),
				"filters": $("#filters").val(),
				"show_deleted": show_deleted ? 1 : 0
			});
		},
		onLoadSuccess: function(response) {
			$('a.rollover').imgPreview({
				imgCSS: {
					width: 200
				},
				distanceFromCursor: {
					top: 10,
					left: -210
				}
			})
		}
	});

	updateTableColumnsByStockLocation();

	var $toggleDeleted = $('#toggle_deleted');
	$toggleDeleted.toggleClass('btn-warning', show_deleted);
	$toggleDeleted.html('<span class="glyphicon glyphicon-eye-open">&nbsp;</span>' + (show_deleted ? "<?= lang('Common.hide_deleted') ?>" : "<?= lang('Common.show_deleted') ?>"));

	$toggleDeleted.click(function() {
		var params = new URLSearchParams(window.location.search);
		if (show_deleted) {
			params.delete('show_deleted');
		} else {
			params.set('show_deleted', '1');
		}
		window.location.search = params.toString();
	});
});
</script>

<?= view('partial/table_filter_persistence', ['options' => ['additional_params' => ['stock_location']]]) ?>

<?= render_title_bar([
	[
		'label' => 'Common.import_csv',
		'icon' => 'import',
		'class' => 'btn-info',
		'href' => "$controller_name/csvImport",
		'modal' => true,
		'title' => 'Items.import_items_csv',
		'data' => ['btn_submit' => 'Common.submit'],
	],
	[
		'label' => ucfirst($controller_name) . '.new',
		'icon' => 'tag',
		'class' => 'btn-info',
		'href' => "$controller_name/view",
		'modal' => true,
		'title' => ucfirst($controller_name) . '.new',
		'data' => ['btn_new' => 'Common.new', 'btn_submit' => 'Common.submit'],
	],
]) ?>

<?= render_table_toolbar(
	[
		[
			'label' => 'Common.show_deleted',
			'icon' => 'eye-open',
			'class' => 'btn-default',
			'id' => 'toggle_deleted',
		],
		[
			'label' => 'Common.delete',
			'icon' => 'trash',
			'class' => 'btn-default',
			'id' => 'delete',
		],
		[
			'label' => 'Common.restore',
			'icon' => 'repeat',
			'class' => 'btn-default',
			'id' => 'restore',
		],
		[
			'label' => 'Items.bulk_edit',
			'icon' => 'edit',
			'class' => 'btn-default',
			'id' => 'bulk_edit',
			'modal' => true,
			'data' => ['btn_submit' => 'Common.submit', 'href' => 'items/bulkEdit'],
			'title' => 'Items.edit_multiple_items',
		],
		[
			'label' => 'Items.generate_barcodes',
			'icon' => 'barcode',
			'class' => 'btn-default',
			'id' => 'generate_barcodes',
			'data' => ['href' => "$controller_name/generateBarcodes"],
			'title' => 'Items.generate_barcodes',
		],
	],
	[
		[
			'type' => 'daterange',
			'name' => 'daterangepicker',
		],
		[
			'type' => 'multiselect',
			'name' => 'filters',
			'options' => $filters,
			'selected' => $selected_filters ?? [],
			'placeholder' => 'Common.none_selected_text',
		],
	],
	['id' => 'toolbar']
) ?>

<?php if (count($stock_locations) > 1): ?>
<script type="text/javascript">
$(document).ready(function() {
	$('#toolbar').append(
		<?= json_encode(form_dropdown(
			'stock_location',
			$stock_locations,
			$stock_location,
			[
				'id' => 'stock_location',
				'class' => 'selectpicker show-menu-arrow',
				'data-style' => 'btn-default btn-sm',
				'data-width' => 'fit'
			]
		)) ?>
	);
});
</script>
<?php endif; ?>

<div id="table_holder">
	<table id="table"></table>
</div>

<?= view('partial/footer') ?>
