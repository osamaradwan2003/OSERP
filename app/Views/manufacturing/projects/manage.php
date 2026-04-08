<?php
/**
 * Manufacturing Projects Manage View
 */
?>
<?= view('partial/header') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="glyphicon glyphicon-list-alt"></i>
                        <?= lang('Manufacturing.projects') ?>
                        <span class="pull-right">
                            <a href="<?= site_url('manufacturing/projects/view') ?>" class="btn btn-primary btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" title="<?= lang('Manufacturing.add_project') ?>">
                                <span class="glyphicon glyphicon-plus"></span> <?= lang('Manufacturing.add_project') ?>
                            </a>
                        </span>
                    </h3>
                </div>
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

        // Delete button handler
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
