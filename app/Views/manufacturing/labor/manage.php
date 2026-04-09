<?php
/**
 * @var array $table_headers
 * @var array $projects
 * @var array $employees
 */
?>
<?= view('partial/header') ?>

<style>
.mfg-labor-page { padding: 20px 0; }
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
.mfg-card .panel-body { padding: 20px; }
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
.mfg-filter-bar {
    margin-bottom: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}
.mfg-filter-bar .form-control { border-radius: 6px; }
.mfg-filter-bar .btn { border-radius: 6px; }
</style>

<div class="mfg-labor-page">
    <div class="mfg-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('manufacturing') ?>"><?= lang('Manufacturing.module') ?></a></li>
            <li class="active"><?= lang('Manufacturing.labor_entries') ?></li>
        </ol>
    </div>

    <div class="mfg-page-header">
        <h1><span class="glyphicon glyphicon-time" style="color: #667eea; margin-right: 10px;"></span><?= lang('Manufacturing.labor_entries') ?></h1>
        <a href="<?= site_url('manufacturing/labor/create') ?>" class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" title="<?= lang('Manufacturing.add_labor_entry') ?>">
            <span class="glyphicon glyphicon-plus"></span> <?= lang('Manufacturing.add_labor_entry') ?>
        </a>
    </div>

    <div class="mfg-card panel panel-default">
        <div class="panel-body">
            <div class="mfg-filter-bar">
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-inline pull-right">
                            <div class="form-group" style="margin-right: 10px;">
                                <select name="project_id" id="project_filter" class="form-control input-sm">
                                    <option value=""><?= lang('Manufacturing.all_projects') ?></option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?= $project['project_id'] ?>"><?= esc($project['project_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group" style="margin-right: 10px;">
                                <?= form_input(['name' => 'start_date', 'id' => 'start_date', 'class' => 'form-control input-sm date-picker', 'placeholder' => lang('Common.start_date')]) ?>
                            </div>
                            <div class="form-group" style="margin-right: 10px;">
                                <?= form_input(['name' => 'end_date', 'id' => 'end_date', 'class' => 'form-control input-sm date-picker', 'placeholder' => lang('Common.end_date')]) ?>
                            </div>
                            <button type="button" id="search_btn" class="btn btn-primary btn-sm">
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
