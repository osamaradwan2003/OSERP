<?php
/**
 * @var array $table_headers
 * @var array $projects
 * @var array $employees
 */
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-time"></i>
                    <?= lang('Manufacturing.labor_entries') ?>
                </h3>
            </div>
            <div class="panel-body">
                <div id="toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?= site_url('manufacturing/labor/create') ?>" class="btn btn-primary btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" title="<?= lang('Manufacturing.add_labor_entry') ?>">
                                <span class="glyphicon glyphicon-plus"></span> <?= lang('Manufacturing.add_labor_entry') ?>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <form class="form-inline pull-right">
                                <select name="project_id" id="project_filter" class="form-control input-sm">
                                    <option value=""><?= lang('Manufacturing.all_projects') ?></option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= $project['project_id'] ?>"><?= esc($project['project_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_input(['name' => 'start_date', 'id' => 'start_date', 'class' => 'form-control input-sm date-picker', 'placeholder' => lang('Common.start_date')]) ?>
                                <?= form_input(['name' => 'end_date', 'id' => 'end_date', 'class' => 'form-control input-sm date-picker', 'placeholder' => lang('Common.end_date')]) ?>
                                <button type="button" id="search_btn" class="btn btn-default btn-sm">
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <table id="table" class="table table-striped table-bordered" data-toolbar="#toolbar">
                    <thead>
                        <tr>
                            <th data-field="labor_id" data-checkbox="true"></th>
                            <th data-field="project_name"><?= lang('Manufacturing.project') ?></th>
                            <th data-field="employee_name"><?= lang('Manufacturing.employee') ?></th>
                            <th data-field="work_date"><?= lang('Manufacturing.work_date') ?></th>
                            <th data-field="hours"><?= lang('Manufacturing.hours') ?></th>
                            <th data-field="hourly_rate"><?= lang('Manufacturing.hourly_rate') ?></th>
                            <th data-field="total_cost"><?= lang('Manufacturing.total_cost') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#table').bootstrapTable({
        url: '<?= site_url("manufacturing/labor/search") ?>',
        queryParams: function(params) {
            params.project_id = $('#project_filter').val();
            params.start_date = $('#start_date').val();
            params.end_date = $('#end_date').val();
            return params;
        },
        pagination: true,
        sidePagination: 'server',
        pageSize: 20,
        columns: [
            { field: 'labor_id', checkbox: true },
            { field: 'project_name', title: '<?= lang("Manufacturing.project") ?>' },
            { field: 'employee_name', title: '<?= lang("Manufacturing.employee") ?>' },
            { field: 'work_date', title: '<?= lang("Manufacturing.work_date") ?>' },
            { field: 'hours', title: '<?= lang("Manufacturing.hours") ?>' },
            { field: 'hourly_rate', title: '<?= lang("Manufacturing.hourly_rate") ?>' },
            { field: 'total_cost', title: '<?= lang("Manufacturing.total_cost") ?>' }
        ]
    });

    $('#search_btn').click(function() {
        $('#table').bootstrapTable('refresh');
    });

    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
});
</script>

<?= view('partial/footer') ?>
