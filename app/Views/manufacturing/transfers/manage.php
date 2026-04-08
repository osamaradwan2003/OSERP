<?php
/**
 * @var string $table_headers
 * @var string $controller_name
 */
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-transfer"></i>
                    <?= lang('Manufacturing.transfers') ?>
                </h3>
            </div>
            <div class="panel-body">
                <div id="toolbar">
                    <a href="<?= site_url('manufacturing/transfers/create') ?>" class="btn btn-primary btn-sm">
                        <span class="glyphicon glyphicon-plus"></span> <?= lang('Manufacturing.add_transfer') ?>
                    </a>
                </div>
                <table id="table" class="table table-striped table-bordered" data-toolbar="#toolbar">
                </table>
            </div>
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
