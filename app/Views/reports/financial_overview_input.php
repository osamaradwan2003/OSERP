<?php
/**
 * @var string $report_title
 * @var string $report_url
 */
?>

<?= view('partial/header') ?>

<div id="page_title"><?= esc($report_title) ?></div>

<div class="form-horizontal">
    <div class="form-group form-group-sm">
        <?= form_label(lang('Reports.date_range'), 'report_date_range_label', ['class' => 'control-label col-xs-2 required']) ?>
        <div class="col-xs-3">
            <?= form_input(['name' => 'daterangepicker', 'class' => 'form-control input-sm', 'id' => 'daterangepicker']) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <div class="col-xs-offset-2 col-xs-3">
            <button id="generate_report" class="btn btn-primary btn-sm"><?= lang('Common.submit') ?></button>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/daterangepicker') ?>

        $('#generate_report').click(function() {
            var url = '<?= site_url() ?>/' + '<?= esc($report_url) ?>' + '/' + start_date + '/' + end_date;
            window.location = url;
        });
    });
</script>
