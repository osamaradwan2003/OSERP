<?php
/**
 * @var array $stock_locations
 * @var array $config
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

<?= form_open('#', ['id' => 'item_form', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) ?>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Reports.date_range'), 'report_date_range_label', ['class' => 'control-label col-xs-2 required']) ?>
        <div class="col-xs-3">
            <?= form_input(['name' => 'daterangepicker', 'class' => 'form-control input-sm', 'id' => 'daterangepicker']) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Reports.stock_location'), 'reports_stock_location_label', ['class' => 'required control-label col-xs-2']) ?>
        <div id="report_stock_location" class="col-xs-3">
            <?= form_dropdown('stock_location', $stock_locations, 'all', 'id="location_id" class="form-control"') ?>
        </div>
    </div>

    <?php
    echo form_button(
        [
            'name'    => 'generate_report',
            'id'      => 'generate_report',
            'content' => lang('Common.submit'),
            'class'   => 'btn btn-primary btn-sm'
        ]
    );
    ?>

<?= form_close() ?>

<?= view('partial/footer') ?>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/daterangepicker') ?>

        $("#generate_report").click(function() {
            window.location = [window.location, start_date, end_date, $("#location_id").val() || 'all'].join("/");
        });
    });
</script>
