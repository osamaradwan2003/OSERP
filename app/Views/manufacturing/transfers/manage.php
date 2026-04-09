<?php
/**
 * @var string $table_headers
 * @var string $controller_name
 */
?>
<?= view('partial/header') ?>

<style>
.mfg-transfers-page { padding: 20px 0; }
.mfg-breadcrumb { padding: 15px 0; }
.mfg-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.mfg-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.mfg-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.mfg-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.mfg-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
    padding: 15px 20px;
}
.mfg-card .panel-heading .btn { border-radius: 8px; }
.mfg-card .panel-body { padding: 0; }
.mfg-table { margin-bottom: 0; }
.mfg-table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 15px 12px;
}
.mfg-table tbody tr:hover { background-color: #f8f9fa; }
.mfg-table tbody td { vertical-align: middle; padding: 12px; border-color: #e9ecef; }
.mfg-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.mfg-badge-pending { background: #fff3cd; color: #856404; }
.mfg-badge-in_progress { background: #d1ecf1; color: #0c5460; }
.mfg-badge-completed { background: #d4edda; color: #155724; }
.mfg-badge-cancelled { background: #f8d7da; color: #721c24; }
</style>

<div class="mfg-transfers-page">
    <div class="mfg-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('manufacturing') ?>"><?= lang('Manufacturing.module') ?></a></li>
            <li class="active"><?= lang('Manufacturing.transfers') ?></li>
        </ol>
    </div>

    <div class="mfg-page-header">
        <h1><span class="glyphicon glyphicon-transfer" style="color: #667eea; margin-right: 10px;"></span><?= lang('Manufacturing.transfers') ?></h1>
        <a href="<?= site_url('manufacturing/transfers/create') ?>" class="btn btn-info btn-sm">
            <span class="glyphicon glyphicon-plus"></span> <?= lang('Manufacturing.add_transfer') ?>
        </a>
    </div>

    <div class="mfg-card panel panel-default">
        <div class="panel-body">
            <table id="table" class="table table-striped table-bordered" data-toolbar="#toolbar">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#table').bootstrapTable({
        url: '<?= site_url("manufacturing/transfers/search") ?>',
        pagination: true,
        sidePagination: 'server',
        pageSize: 20,
        columns: [
            { field: 'transfer_id', title: '<?= lang("Manufacturing.transfer_code") ?>', sortable: true },
            { field: 'project_name', title: '<?= lang("Manufacturing.project") ?>' },
            { field: 'transfer_type', title: '<?= lang("Manufacturing.transfer_type") ?>', sortable: true },
            { field: 'location_name', title: '<?= lang("Manufacturing.source_location") ?>' },
            { field: 'transfer_date', title: '<?= lang("Manufacturing.transfer_date") ?>', sortable: true },
            { field: 'status', title: '<?= lang("Manufacturing.transfer_status") ?>', sortable: true },
            { field: 'edit', title: '', escape: false }
        ]
    });
});
</script>

<?= view('partial/footer') ?>
