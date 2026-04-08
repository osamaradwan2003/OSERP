<?php
/**
 * Cashflow Entries Management View
 *
 * @var string $controller_name
 * @var string $table_headers
 * @var array $accounts
 * @var array $types
 * @var array $statuses
 * @var array $categories
 * @var array $categories_by_type
 * @var array $config
 * @var bool $is_drafts_list
 * @var bool $show_post_button
 * @var string $default_status
 * @var bool $status_fixed
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
$(document).ready(function() {
	<?= view('partial/bootstrap_tables_locale') ?>

	var show_deleted = new URLSearchParams(window.location.search).get('show_deleted') === '1';

	<?= view('partial/daterangepicker') ?>
	$('#daterangepicker').data('daterangepicker').setStartDate("<?= date($config['dateformat'], mktime(0, 0, 0, 1, 1, 2010)) ?>");
	var start_date = "<?= date('Y-m-d', mktime(0, 0, 0, 1, 1, 2010)) ?>";
	var end_date = "<?= date('Y-m-d') ?>";
	var allCategories = <?= json_encode($categories) ?>;
	var categoriesByType = <?= json_encode($categories_by_type) ?>;

	var updateCategoryFilter = function() {
		var type = $('#entry_type').val();
		var $category = $('#category_id');
		var current = $category.val();
		var options = allCategories;

		if (type !== '') {
			options = categoriesByType[type] || { '': "<?= lang('Reports.all') ?>" };
		}

		$category.empty();
		$.each(options, function(value, label) {
			$category.append($('<option>', { value: value, text: label }));
		});

		if (options[current] !== undefined) {
			$category.val(current);
		} else {
			$category.val('');
		}
	};

	updateCategoryFilter();

	$("#daterangepicker").on('apply.daterangepicker', function() {
		table_support.refresh();
	});

	$('#entry_type').change(function() {
		updateCategoryFilter();
		table_support.refresh();
	});
	$('#status, #account_id, #category_id').change(function() {
		table_support.refresh();
	});

	<?php if (!empty($status_fixed)) { ?>
	$('#status').prop('disabled', true);
	<?php } ?>

	<?php if (!empty($show_post_button)) { ?>
	$('#post').click(function() {
		var ids = table_support.selected_ids();
		if (!ids.length) {
			return false;
		}
		if (confirm($.fn.bootstrapTable.defaults.formatConfirmAction('post'))) {
			$.post('<?= esc($controller_name) ?>/post', {'ids[]': ids}, function(response) {
				if (response.success) {
					$.notify(response.message, {type: 'success'});
					table_support.refresh();
				} else {
					$.notify(response.message, {type: 'danger'});
				}
			}, 'json');
		}
		return false;
	});
	<?php } ?>

	table_support.init({
		resource: '<?= esc($controller_name) ?>',
		headers: <?= $table_headers ?>,
		pageSize: <?= $config['lines_per_page'] ?>,
		uniqueId: 'entry_id',
		queryParams: function() {
			return $.extend(arguments[0], {
				start_date: start_date,
				end_date: end_date,
				entry_type: $('#entry_type').val(),
				status: $('#status').val(),
				account_id: $('#account_id').val(),
				category_id: $('#category_id').val(),
				show_deleted: show_deleted ? 1 : 0
			});
		}
	});

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

<?= render_title_bar([
	[
		'label' => empty($is_drafts_list) ? 'Cashflow.drafts' : 'Cashflow.back_to_entries',
		'icon' => empty($is_drafts_list) ? 'list-alt' : 'arrow-left',
		'class' => 'btn-default',
		'href' => empty($is_drafts_list) ? site_url('cashflow/drafts') : site_url('cashflow'),
	],
	[
		'label' => 'Cashflow.manage_categories',
		'icon' => 'tags',
		'class' => 'btn-default',
		'href' => site_url('cashflow_categories'),
	],
	[
		'label' => 'Cashflow.manage_accounts',
		'icon' => 'briefcase',
		'class' => 'btn-default',
		'href' => site_url('cashflow_accounts'),
	],
	[
		'label' => 'Cashflow.new_entry',
		'icon' => 'plus',
		'class' => 'btn-info',
		'href' => site_url('cashflow/view'),
		'modal' => true,
		'title' => 'Cashflow.new_entry',
		'data' => ['btn_submit' => 'Common.submit'],
	],
]) ?>

<?php
$toolbarActions = [
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
];
if (!empty($show_post_button)) {
	array_unshift($toolbarActions, [
		'label' => 'Cashflow.accept',
		'icon' => 'ok',
		'class' => 'btn-success',
		'id' => 'post',
	]);
}
?>
<?= render_table_toolbar(
	$toolbarActions,
	[
		[
			'type' => 'daterange',
			'name' => 'daterangepicker',
		],
		[
			'type' => 'select',
			'name' => 'entry_type',
			'options' => $types,
			'selected' => '',
		],
		[
			'type' => 'select',
			'name' => 'status',
			'options' => $statuses,
			'selected' => $default_status,
		],
		[
			'type' => 'select',
			'name' => 'account_id',
			'options' => $accounts,
			'selected' => '',
		],
		[
			'type' => 'select',
			'name' => 'category_id',
			'options' => $categories,
			'selected' => '',
		],
	],
	['id' => 'toolbar']
) ?>

<div id="table_holder">
	<table id="table"></table>

    <script>
        console.log("tets");
        console.log($("button#toggle_deleted").removeAttr("disabled"))
    </script>
</div>

<?= view('partial/footer') ?>
