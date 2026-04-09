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

<style>
.items-page { padding: 20px 0; }
.items-breadcrumb { padding: 15px 0; }
.items-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.items-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.items-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.items-toolbar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.items-toolbar-card .btn-toolbar .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s; }
.items-toolbar-card .btn-toolbar .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.items-table-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.items-table-card .table { margin-bottom: 0; }
.items-table-card .table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 15px 12px;
}
.items-table-card .table tbody tr:hover { background-color: #f8f9fa; }
.items-table-card .table tbody td { vertical-align: middle; padding: 12px; border-color: #e9ecef; }
.items-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.items-badge-success { background: #d4edda; color: #155724; }
.items-badge-warning { background: #fff3cd; color: #856404; }
.items-badge-danger { background: #f8d7da; color: #721c24; }
.items-badge-info { background: #d1ecf1; color: #0c5460; }
#table_holder { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
#table_holder .bootstrap-table .table { border-radius: 0; }
.bootstrap-table .table thead th { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; color: #fff; }
</style>

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

	$('#filters').on('hidden.bs.select', function(e) {
		table_support.refresh();
	});

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

	<?= view('partial/daterangepicker') ?>
	$('#daterangepicker').data('daterangepicker').setStartDate("<?= date($config['dateformat'], mktime(0, 0, 0, 01, 01, 2010)) ?>");
	var start_date = "<?= date('Y-m-d', mktime(0, 0, 0, 01, 01, 2010)) ?>";
	var end_date = "<?= date('Y-m-d') ?>";

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

<div class="items-page">
	<div class="items-breadcrumb">
		<ol class="breadcrumb">
			<li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
			<li class="active"><?= lang('Module.items') ?></li>
		</ol>
	</div>

	<div class="items-page-header">
		<h1><span class="glyphicon glyphicon-barcode" style="color: #667eea; margin-right: 10px;"></span><?= lang('Module.items') ?></h1>
	</div>

	<div class="items-toolbar-card">
		<div class="btn-toolbar" role="toolbar">
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
		</div>
	</div>

	<div class="btn-toolbar" role="toolbar" style="margin-bottom: 15px;">
		<div class="pull-left btn-group">
			<button id="toggle_deleted" class="btn btn-default btn-sm">
				<span class="glyphicon glyphicon-eye-open">&nbsp;</span><span class="toggle-label"><?= lang('Common.show_deleted') ?></span>
			</button>
			<button id="delete" class="btn btn-default btn-sm">
				<span class="glyphicon glyphicon-trash">&nbsp;</span><?= lang('Common.delete') ?>
			</button>
			<button id="restore" class="btn btn-default btn-sm">
				<span class="glyphicon glyphicon-repeat">&nbsp;</span><?= lang('Common.restore') ?>
			</button>
			<button id="bulk_edit" class="btn btn-default btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="items/bulkEdit" title="<?= lang('Items.edit_multiple_items') ?>">
				<span class="glyphicon glyphicon-edit">&nbsp;</span><?= lang('Items.bulk_edit') ?>
			</button>
			<button id="generate_barcodes" class="btn btn-default btn-sm" data-href="<?= "$controller_name/generateBarcodes" ?>" title="<?= lang('Items.generate_barcodes') ?>">
				<span class="glyphicon glyphicon-barcode">&nbsp;</span><?= lang('Items.generate_barcodes') ?>
			</button>
		</div>
	</div>

	<div class="form-inline" role="toolbar" style="margin-bottom: 15px;">
		<?= form_input(['name' => 'daterangepicker', 'class' => 'form-control input-sm', 'id' => 'daterangepicker']) ?>
		<?= form_multiselect('filters[]', $filters, $selected_filters ?? [], [
			'id'                        => 'filters',
			'data-none-selected-text'   => lang('Common.none_selected_text'),
			'class'                     => 'selectpicker show-menu-arrow',
			'data-selected-text-format' => 'count > 1',
			'data-style'                => 'btn-default btn-sm',
			'data-width'                => 'fit'
		]) ?>
	</div>

	<?php if (count($stock_locations) > 1): ?>
	<script type="text/javascript">
	$(document).ready(function() {
		$('.form-inline').append(
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

	<div class="items-table-card">
		<div id="table_holder">
			<table id="table"></table>
		</div>
	</div>
</div>

<?= view('partial/footer') ?>
