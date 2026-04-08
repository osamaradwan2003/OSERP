<?php
/**
 * Cashflow Entry Form
 *
 * @var string $controller_name
 * @var array $entry
 * @var array $accounts
 * @var array $entry_types
 * @var array $categories_by_type
 * @var array $type_calc_methods
 * @var array $customers
 * @var array $suppliers
 * @var array $attachments
 * @var string $sale_reference
 * @var string $receiving_reference
 * @var array $sale_reference_options
 * @var array $receiving_reference_options
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open_multipart("$controller_name/save/{$entry['entry_id']}", ['id' => 'cashflow_form', 'class' => 'form-horizontal']) ?>

<?= form_input(['name' => 'entry_date', 'type' => 'datetime-local', 'class' => 'form-control input-sm', 'id' => 'entry_date', 'value' => to_datetime(strtotime($entry['entry_date']))]) ?>

<?= render_form_dropdown('entry_type', 'Reports.type', $entry_types, $entry['entry_type'], [
	'required' => true,
	'onchange' => 'updateTypeUI()',
]) ?>

<?php
$currentType = (string) ($entry['entry_type'] ?? '');
$categoryOptions = $categories_by_type[$currentType] ?? ['' => lang('Common.none_selected_text')];
?>
<div id="category_group" class="form-group form-group-sm">
	<?= form_label(lang('Cashflow.category'), 'category_id', ['class' => 'required control-label col-xs-3']) ?>
	<div class="col-xs-8">
		<?= form_dropdown('category_id', $categoryOptions, $entry['category_id'] ?? '', ['id' => 'category_id', 'class' => 'form-control input-sm']) ?>
	</div>
</div>

<?= render_form_input('amount', 'Cashflow.amount', to_currency_no_money($entry['amount']), [
	'required' => true,
	'type' => 'number',
	'step' => '0.01',
	'min' => '0.01',
]) ?>

<div id="single_account_group" class="form-group form-group-sm">
	<?= form_label(lang('Cashflow.account'), 'account_id', ['class' => 'required control-label col-xs-3']) ?>
	<div class="col-xs-8">
		<?= form_dropdown('account_id', $accounts, $entry['account_id'], ['id' => 'account_id', 'class' => 'form-control input-sm']) ?>
	</div>
</div>

<div id="transfer_accounts_group">
	<div class="form-group form-group-sm">
		<?= form_label(lang('Cashflow.from_account'), 'from_account_id', ['class' => 'required control-label col-xs-3']) ?>
		<div class="col-xs-8">
			<?= form_dropdown('from_account_id', $accounts, $entry['from_account_id'], ['id' => 'from_account_id', 'class' => 'form-control input-sm']) ?>
		</div>
	</div>
	<div class="form-group form-group-sm">
		<?= form_label(lang('Cashflow.to_account'), 'to_account_id', ['class' => 'required control-label col-xs-3']) ?>
		<div class="col-xs-8">
			<?= form_dropdown('to_account_id', $accounts, $entry['to_account_id'], ['id' => 'to_account_id', 'class' => 'form-control input-sm']) ?>
		</div>
	</div>
</div>

<?= render_form_dropdown('customer_id', 'Cashflow.customer', $customers, $entry['customer_id'], [
	'required' => false,
]) ?>

<?= render_form_dropdown('supplier_id', 'Cashflow.supplier', $suppliers, $entry['supplier_id'], [
	'required' => false,
]) ?>

<?= render_form_dropdown('sale_reference', 'Cashflow.sale_reference', $sale_reference_options, $sale_reference, [
	'required' => false,
	'selectpicker' => true,
	'data_live_search' => true,
	'data_width' => '100%',
]) ?>

<?= render_form_dropdown('receiving_reference', 'Cashflow.receiving_reference', $receiving_reference_options, $receiving_reference, [
	'required' => false,
	'selectpicker' => true,
	'data_live_search' => true,
	'data_width' => '100%',
]) ?>

<?= render_form_dropdown('status', 'Cashflow.status', [
	'draft' => lang('Cashflow.draft'),
	'posted' => lang('Cashflow.posted'),
], $entry['status'], [
	'required' => true,
]) ?>

<?= render_form_textarea('description', 'Common.description', $entry['description'], [
	'required' => false,
	'rows' => 3,
]) ?>

<?= render_form_file_upload('attachments', 'Cashflow.attachments', [
	'multiple' => true,
]) ?>

<?php if (!empty($attachments)): ?>
<div class="form-group form-group-sm">
	<?= form_label(lang('Cashflow.current_attachments'), '', ['class' => 'control-label col-xs-3']) ?>
	<div class="col-xs-8">
		<?php foreach ($attachments as $attachment): ?>
		<div class="clearfix" style="margin-bottom: 6px;">
			<a href="<?= base_url('writable/' . $attachment['file_path']) ?>" target="_blank"><?= esc($attachment['file_name']) ?></a>
			<button type="button" class="btn btn-xs btn-danger pull-right delete-attachment" data-attachment-id="<?= (int) $attachment['attachment_id'] ?>">
				<?= lang('Common.delete') ?>
			</button>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php endif; ?>

<div class="form-group form-group-sm">
	<div class="col-xs-8 col-xs-offset-3">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?= lang('Common.det') ?></h3>
			</div>
			<div class="panel-body">
				<dl class="dl-horizontal" id="cashflow_details">
					<dt><?= lang('Reports.date') ?></dt>
					<dd data-detail="entry_date">-</dd>
					<dt><?= lang('Reports.type') ?></dt>
					<dd data-detail="entry_type">-</dd>
					<dt><?= lang('Cashflow.category') ?></dt>
					<dd data-detail="category">-</dd>
					<dt><?= lang('Cashflow.amount') ?></dt>
					<dd data-detail="amount">-</dd>
					<dt><?= lang('Cashflow.account') ?></dt>
					<dd data-detail="account">-</dd>
					<dt><?= lang('Cashflow.party') ?></dt>
					<dd data-detail="party">-</dd>
					<dt><?= lang('Cashflow.status') ?></dt>
					<dd data-detail="status">-</dd>
					<dt><?= lang('Common.description') ?></dt>
					<dd data-detail="description">-</dd>
				</dl>
			</div>
		</div>
	</div>
</div>
<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
	var $form = $('#cashflow_form');
	var categoriesByType = <?= json_encode($categories_by_type) ?>;
	var typeCalcMethods = <?= json_encode($type_calc_methods) ?>;

	window.updateTypeUI = function() {
		var type = $form.find('#entry_type').val();
		var transfer = typeCalcMethods[type] === 'transfer';
		$form.find('#single_account_group').toggle(!transfer);
		$form.find('#transfer_accounts_group').toggle(transfer);
		$form.find('#category_group').toggle(true);
		$form.find("label[for='category_id']").toggleClass('required', !transfer);

		var $category = $form.find('#category_id');
		var current = $category.val();
		var options = categoriesByType[type] || {'': "<?= lang('Common.none_selected_text') ?>"};

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

	updateTypeUI();
	$form.find('#entry_type').change(updateTypeUI);

	$('.selectpicker').each(function() {
		var $selectpicker = $(this);
		$.fn.selectpicker.call($selectpicker, $selectpicker.data());
	});

	$form.find('#customer_id').change(function() {
		if ($(this).val() !== '') {
			$form.find('#supplier_id').val('');
		}
	});
	$form.find('#supplier_id').change(function() {
		if ($(this).val() !== '') {
			$form.find('#customer_id').val('');
		}
	});

	$form.find('#sale_reference').change(function() {
		if ($(this).val() !== '') {
			var $receiving = $form.find('#receiving_reference');
			$receiving.val('');
			$receiving.selectpicker('refresh');
		}
	});
	$form.find('#receiving_reference').change(function() {
		if ($(this).val() !== '') {
			var $sale = $form.find('#sale_reference');
			$sale.val('');
			$sale.selectpicker('refresh');
		}
	});

	$('.delete-attachment').click(function() {
		var id = $(this).data('attachment-id');
		var csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
		var csrfValue = $('input[name="<?= csrf_token() ?>"]').val();
		var payload = {};
		payload[csrfName] = csrfValue;

		$.post('<?= site_url('cashflow/deleteAttachment') ?>/' + id, payload, function(response) {
			$.notify(response.message, { type: response.success ? 'success' : 'danger' });
			if (response.success) {
				location.reload();
			}
		}, 'json');
	});

	$('#cashflow_form').validate($.extend({
		submitHandler: function(form) {
			$(form).ajaxSubmit({
				dataType: 'json',
				success: function(response) {
					dialog_support.hide();
					table_support.handle_submit('cashflow', response);
				}
			});
		},
		rules: {
			entry_date: 'required',
			amount: {
				required: true,
				number: true,
				min: 0.01
			}
		},
		messages: {
			entry_date: "<?= lang('Cashflow.entry_date_required') ?>",
			amount: "<?= lang('Cashflow.invalid_amount') ?>"
		}
	}, form_support.error));
});
</script>
