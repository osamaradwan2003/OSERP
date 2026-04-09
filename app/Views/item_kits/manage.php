<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<style>
.itemkits-page { padding: 20px 0; }
.itemkits-breadcrumb { padding: 15px 0; }
.itemkits-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.itemkits-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.itemkits-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.itemkits-toolbar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.itemkits-toolbar-card .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s; }
.itemkits-toolbar-card .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.itemkits-table-card {
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
</style>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/bootstrap_tables_locale') ?>

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'item_kit_id'
        });

        $('#generate_barcodes').click(function() {
            window.open(
                'index.php/item_kits/generateBarcodes/' + table_support.selected_ids().join(':'),
                '_blank'
            );
        });
    });
</script>

<div class="itemkits-page">
    <div class="itemkits-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active"><?= lang('Module.item_kits') ?></li>
        </ol>
    </div>

    <div class="itemkits-page-header">
        <h1><span class="glyphicon glyphicon-tags" style="color: #667eea; margin-right: 10px;"></span><?= lang('Module.item_kits') ?></h1>
        <button class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= esc("$controller_name/view") ?>" title="<?= lang(ucfirst($controller_name) . '.new') ?>">
            <span class="glyphicon glyphicon-plus">&nbsp;</span><?= lang(ucfirst($controller_name) . '.new') ?>
        </button>
    </div>

    <div class="itemkits-toolbar-card">
        <div class="btn-toolbar">
            <div class="pull-left btn-group">
                <button id="delete" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-trash">&nbsp;</span><?= lang('Common.delete') ?>
                </button>
                <button id="generate_barcodes" class="btn btn-default btn-sm" data-href="<?= esc("$controller_name/generateBarcodes") ?>">
                    <span class="glyphicon glyphicon-barcode">&nbsp;</span><?= lang('Items.generate_barcodes') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="itemkits-table-card">
        <div id="table_holder">
            <table id="table"></table>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
