<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $filters
 * @var array $selected_filters
 * @var array $config
 * @var string|null $start_date
 * @var string|null $end_date
 */
?>

<?= view('partial/header') ?>

<style>
.sales-page { padding: 20px 0; }
.sales-breadcrumb { padding: 15px 0; }
.sales-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.sales-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.sales-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.sales-toolbar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.sales-toolbar-card .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s; }
.sales-toolbar-card .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.sales-toolbar-card .form-control { border-radius: 8px; }
.sales-toolbar-card .selectpicker { border-radius: 8px; }
.sales-table-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
#table_holder { padding: 15px; }
#table_holder .bootstrap-table .table { border-radius: 8px; overflow: hidden; }
#table_holder .table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
}
#payment_summary {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $('#filters').on('hidden.bs.select', function(e) {
            table_support.refresh();
        });

        <?= view('partial/daterangepicker') ?>

        $("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
            table_support.refresh();
        });

        <?= view('partial/bootstrap_tables_locale') ?>

        <?php if (isset($start_date) && $start_date): ?>
        start_date = "<?= esc($start_date) ?>";
        <?php endif; ?>
        <?php if (isset($end_date) && $end_date): ?>
        end_date = "<?= esc($end_date) ?>";
        <?php endif; ?>

        table_support.query_params = function() {
            return {
                "start_date": start_date,
                "end_date": end_date,
                "filters": $("#filters").val()
            }
        };

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'sale_id',
            onLoadSuccess: function(response) {
                if ($("#table tbody tr").length > 1) {
                    $("#payment_summary").html(response.payment_summary);
                    $("#table tbody tr:last td:first").html("");
                    $("#table tbody tr:last").css('font-weight', 'bold');
                }
            },
            queryParams: function() {
                return $.extend(arguments[0], table_support.query_params());
            },
            columns: {
                'invoice': {
                    align: 'center'
                }
            }
        });
     });
</script>

<?= view('partial/table_filter_persistence') ?>

<?= view('partial/print_receipt', ['print_after_sale' => false, 'selected_printer' => 'takings_printer']) ?>

<div class="sales-page">
    <div class="sales-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active"><?= lang('Module.sales') ?></li>
        </ol>
    </div>

    <div class="sales-page-header">
        <h1><span class="glyphicon glyphicon-shopping-cart" style="color: #667eea; margin-right: 10px;"></span><?= lang('Module.sales') ?></h1>
        <div>
            <button onclick="javascript:printdoc()" class="btn btn-info btn-sm">
                <span class="glyphicon glyphicon-print">&nbsp;</span><?= lang('Common.print') ?>
            </button>
            <?= anchor("sales", '<span class="glyphicon glyphicon-shopping-cart">&nbsp;</span>' . lang('Sales.register'), ['class' => 'btn btn-info btn-sm', 'id' => 'show_sales_button']) ?>
        </div>
    </div>

    <div class="sales-toolbar-card">
        <div class="btn-toolbar">
            <div class="pull-left form-inline" role="toolbar">
                <button id="delete" class="btn btn-default btn-sm print_hide">
                    <span class="glyphicon glyphicon-trash">&nbsp;</span><?= lang('Common.delete') ?>
                </button>

                <?= form_input(['name' => 'daterangepicker', 'class' => 'form-control input-sm', 'id' => 'daterangepicker', 'style' => 'margin-left: 10px;']) ?>
                <?= form_multiselect('filters[]', $filters, $selected_filters, [
                    'id'                        => 'filters',
                    'data-none-selected-text'   => lang('Common.none_selected_text'),
                    'class'                     => 'selectpicker show-menu-arrow',
                    'data-selected-text-format' => 'count > 1',
                    'data-style'                => 'btn-default btn-sm',
                    'data-width'                => 'fit'
                ]) ?>
            </div>
        </div>
    </div>

    <div class="sales-table-card">
        <div id="table_holder">
            <table id="table"></table>
        </div>
    </div>

    <div id="payment_summary">
    </div>
</div>

<?= view('partial/footer') ?>
