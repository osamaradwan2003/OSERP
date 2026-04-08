<?php
/**
 * @var array $customers
 * @var array $suppliers
 */
?>

<?= view('partial/header') ?>

<script type="text/javascript">
    dialog_support.init("a.modal-dlg");
</script>

<div id="page_title"><?= lang('Reports.report_input') ?></div>

<?php
if (isset($error)) {
    echo '<div class="alert alert-dismissible alert-danger">' . esc($error) . '</div>';
}
?>

<?= form_open('#', ['id' => 'money_transactions_form', 'class' => 'form-horizontal']) ?>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Reports.date_range'), 'report_date_range_label', ['class' => 'control-label col-xs-2 required']) ?>
        <div class="col-xs-3">
            <?= form_input(['name' => 'daterangepicker', 'class' => 'form-control input-sm', 'id' => 'daterangepicker']) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Reports.type'), 'entity_type_label', ['class' => 'control-label col-xs-2 required']) ?>
        <div class="col-xs-3">
            <?= form_dropdown('entity_type', [
                'customer' => lang('Reports.customer'),
                'supplier' => lang('Reports.supplier')
            ], 'customer', 'id="entity_type" class="form-control"') ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Reports.name'), 'entity_id_label', ['class' => 'control-label col-xs-2 required']) ?>
        <div class="col-xs-3">
            <?= form_dropdown('entity_id', $customers, '', 'id="entity_id" class="form-control selectpicker" data-live-search="true"') ?>
        </div>
    </div>

    <?php
    echo form_button([
        'name'    => 'generate_report',
        'id'      => 'generate_report',
        'content' => lang('Common.submit'),
        'class'   => 'btn btn-primary btn-sm'
    ]);
    ?>

<?= form_close() ?>

<?= view('partial/footer') ?>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/daterangepicker') ?>

        var customers = <?= json_encode($customers) ?>;
        var suppliers = <?= json_encode($suppliers) ?>;

        var updateEntityOptions = function() {
            var type = $('#entity_type').val();
            var options = type === 'supplier' ? suppliers : customers;
            var $entity = $('#entity_id');
            var current = $entity.val();

            $entity.empty();
            $.each(options, function(value, label) {
                $entity.append($('<option>', { value: value, text: label }));
            });

            if (options[current] !== undefined) {
                $entity.val(current);
            } else {
                $entity.val('');
            }

            $entity.selectpicker('refresh');
        };

        updateEntityOptions();
        $('#entity_type').change(updateEntityOptions);

        $('#generate_report').click(function() {
            var entityId = $('#entity_id').val();
            if (!entityId) {
                return;
            }
            window.location = [window.location, start_date, end_date, $('#entity_type').val(), entityId].join("/");
        });
    });
</script>

