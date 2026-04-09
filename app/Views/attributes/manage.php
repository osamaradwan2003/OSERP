<?php
/**
 * @var string $controller_name
 * @var string $table_headers
 * @var array $config
 */
?>

<?= view('partial/header') ?>

<style>
.attributes-page { padding: 20px 0; }
.attributes-breadcrumb { padding: 15px 0; }
.attributes-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.attributes-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.attributes-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.attributes-toolbar-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.attributes-toolbar-card .btn { border-radius: 8px; font-weight: 500; transition: all 0.2s; }
.attributes-toolbar-card .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.attributes-table-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.attributes-table-card .table { margin-bottom: 0; }
.attributes-table-card .table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 15px 12px;
}
.attributes-table-card .table tbody tr:hover { background-color: #f8f9fa; }
.attributes-table-card .table tbody td { vertical-align: middle; padding: 12px; border-color: #e9ecef; }
#table_holder { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
#table_holder .bootstrap-table .table { border-radius: 0; }
.bootstrap-table .table thead th { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; color: #fff; }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        <?= view('partial/bootstrap_tables_locale') ?>

        table_support.init({
            resource: '<?= esc($controller_name) ?>',
            headers: <?= $table_headers ?>,
            pageSize: <?= $config['lines_per_page'] ?>,
            uniqueId: 'definition_id'
        });
    });
</script>

<div class="attributes-page">
    <div class="attributes-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li class="active"><?= lang('Attributes.attributes') ?></li>
        </ol>
    </div>

    <div class="attributes-page-header">
        <h1><span class="glyphicon glyphicon-star" style="color: #667eea; margin-right: 10px;"></span><?= lang('Attributes.attributes') ?></h1>
        <button class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" data-href="<?= esc("$controller_name/view") ?>" title="<?= lang(ucfirst($controller_name) . ".new") ?>">
            <span class="glyphicon glyphicon-plus">&nbsp;</span><?= lang(ucfirst($controller_name) . ".new") ?>
        </button>
    </div>

    <div class="attributes-toolbar-card">
        <div class="btn-toolbar">
            <div class="pull-left btn-group">
                <button id="delete" class="btn btn-default btn-sm print_hide">
                    <span class="glyphicon glyphicon-trash">&nbsp;</span><?= lang('Common.delete') ?>
                </button>
            </div>
        </div>
    </div>

    <div class="attributes-table-card">
        <div id="table_holder">
            <table id="table"></table>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
