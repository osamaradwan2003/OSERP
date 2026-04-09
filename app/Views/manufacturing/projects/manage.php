<?php
/**
 * Manufacturing Projects Manage View
 */
?>
<?= view('partial/header') ?>

<style>
.mfg-projects-page { padding: 20px 0; }
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
.mfg-badge-low { background: #e2e3e5; color: #383d41; }
.mfg-badge-medium { background: #fff3cd; color: #856404; }
.mfg-badge-high { background: #f8d7da; color: #721c24; }
.mfg-badge-urgent { background: #d4edda; color: #155724; }
</style>

<div class="mfg-projects-page">
    <div class="mfg-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('manufacturing') ?>"><?= lang('Manufacturing.module') ?></a></li>
            <li class="active"><?= lang('Manufacturing.projects') ?></li>
        </ol>
    </div>

    <div class="mfg-page-header">
        <h1><span class="glyphicon glyphicon-briefcase" style="color: #667eea; margin-right: 10px;"></span><?= lang('Manufacturing.projects') ?></h1>
        <a href="<?= site_url('manufacturing/projects/view') ?>" class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" title="<?= lang('Manufacturing.add_project') ?>">
            <span class="glyphicon glyphicon-plus"></span> <?= lang('Manufacturing.add_project') ?>
        </a>
    </div>

    <div class="mfg-card panel panel-default">
        <div class="panel-body">
            <table id="table" class="table table-bordered table-striped table-hover" data-order='[[0, "desc"]]'>
                <thead>
                    <tr>
                        <th data-field="project_id" data-sortable="true"><?= lang('Manufacturing.project_code') ?></th>
                        <th data-field="project_name" data-sortable="true"><?= lang('Manufacturing.project_name') ?></th>
                        <th data-field="customer_name" data-sortable="false"><?= lang('Manufacturing.customer') ?></th>
                        <th data-field="project_status" data-sortable="true"><?= lang('Manufacturing.project_status') ?></th>
                        <th data-field="priority" data-sortable="true"><?= lang('Manufacturing.priority') ?></th>
                        <th data-field="start_date" data-sortable="true"><?= lang('Manufacturing.start_date') ?></th>
                        <th data-field="target_completion_date" data-sortable="true"><?= lang('Manufacturing.target_completion_date') ?></th>
                        <th data-field="manager_name" data-sortable="false"><?= lang('Manufacturing.project_manager') ?></th>
                        <th data-field="edit" data-sortable="false" data-print-ignore="true"></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#table').bootstrapTable({
            url: '<?= site_url('manufacturing/projects/search') ?>',
            queryParams: function(params) {
                return $.extend({}, params, {
                    search: $('#search').val()
                });
            },
            pagination: true,
            sidePagination: 'server',
            pageSize: 20,
            pageList: [10, 20, 50, 100],
            showRefresh: true,
            showColumns: true,
            showToggle: true,
            showPrint: true,
            showExport: true,
            exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel'],
            search: true,
            searchAlign: 'right',
            toolbar: '#toolbar',
            responseHandler: function(res) {
                return {
                    total: res.total,
                    rows: res.rows
                };
            }
        });

        $(document).on('click', '.delete-project', function(e) {
            e.preventDefault();
            var project_id = $(this).data('id');
            if (confirm('<?= lang('Manufacturing.confirm_delete') ?>')) {
                $.post('<?= site_url('manufacturing/projects/delete') ?>', { ids: [project_id] }, function(response) {
                    if (response.success) {
                        table.bootstrapTable('refresh');
                        $.notify(response.message, 'success');
                    } else {
                        $.notify(response.message, 'error');
                    }
                }, 'json');
            }
        });
    });
</script>

<?= view('partial/footer') ?>
