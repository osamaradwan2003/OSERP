<?php
/**
 * @var array $employeeId
 * @var array $shift_options
 */
?>

<?= view('partial/header') ?>

<style>
.hr-page { padding: 20px 0; }
.page-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.page-header-bar h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    color: #2c3e50;
}
.page-header-bar h1 .glyphicon { margin-right: 12px; color: var(--primary); }
.hr-table {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}
.hr-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.hr-table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border: none;
    padding: 15px;
}
.hr-table tbody td {
    vertical-align: middle;
    padding: 12px 15px;
    border-color: #f0f0f0;
}
.hr-table tbody tr:hover { background-color: #f8f9ff; }
.time-display {
    font-family: monospace;
    background: #f8f9fa;
    padding: 5px 10px;
    border-radius: 5px;
}
.hours-badge {
    background: #e3f2fd;
    color: #1565c0;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
}
.form-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    overflow: hidden;
}
.form-card .panel-heading {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: #fff;
    padding: 20px 25px;
    font-size: 18px;
    font-weight: 600;
}
.form-card .panel-body { padding: 25px; }
.breadcrumb-bar { margin-bottom: 20px; }
.breadcrumb-bar .breadcrumb { margin: 0; padding: 10px 0; background: transparent; }
</style>

<div class="hr-page">
    <div class="breadcrumb-bar">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><?= lang('Module.home') ?></a></li>
            <li><a href="<?= site_url('hr') ?>"><?= lang('Hr.hr_dashboard') ?></a></li>
            <li class="active"><?= lang('Hr.shifts') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1><span class="glyphicon glyphicon-time"></span> <?= lang('Hr.assign_shift') ?></h1>
    </div>

    <div class="hr-table">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th><?= lang('Hr.shift_name') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.start_time') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.end_time') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.working_hours') ?></th>
                    <th style="width: 120px;"><?= lang('Hr.effective_from') ?></th>
                    <th style="width: 120px;"><?= lang('Hr.effective_to') ?></th>
                </tr>
            </thead>
            <tbody id="shift_history">
            </tbody>
        </table>
    </div>

    <div class="form-card">
        <div class="panel-heading">
            <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.assign_shift') ?>
        </div>
        <div class="panel-body">
            <?= form_open('hr/assign_shift', ['id' => 'assign_shift_form']) ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(lang('Hr.shift'), 'shift_id', ['class' => 'control-label']) ?>
                        <?= form_dropdown('shift_id', $shift_options, '', ['class' => 'form-control input-lg', 'id' => 'shift_id']) ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(lang('Hr.effective_from'), 'effective_from', ['class' => 'control-label']) ?>
                        <?= form_input([
                            'name' => 'effective_from',
                            'id' => 'effective_from',
                            'class' => 'form-control input-lg',
                            'type' => 'date',
                            'value' => date('Y-m-d')
                        ]) ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?= form_label(lang('Hr.effective_to'), 'effective_to', ['class' => 'control-label']) ?>
                        <?= form_input([
                            'name' => 'effective_to',
                            'id' => 'effective_to',
                            'class' => 'form-control input-lg',
                            'type' => 'date'
                        ]) ?>
                        <span class="help-block text-muted">Leave empty for indefinite</span>
                    </div>
                </div>
            </div>
            
            <?= form_hidden('employee_id', $employeeId) ?>
            
            <div class="form-group" style="margin-top: 20px;">
                <button type="submit" class="btn btn-success btn-lg">
                    <span class="glyphicon glyphicon-save"></span> <?= lang('Common.submit') ?>
                </button>
            </div>
            
            <?= form_close() ?>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    loadShiftHistory();
    
    $('#assign_shift_form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= site_url('hr/assign_shift') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    loadShiftHistory();
                    $('#assign_shift_form')[0].reset();
                    $('#assign_shift_form #effective_from').val('<?= date('Y-m-d') ?>');
                }
                alert(response.message);
            }
        });
    });
    
    function loadShiftHistory() {
        $.ajax({
            url: '<?= site_url('hr/employee_shifts/' . $employeeId) ?>',
            type: 'GET',
            dataType: 'html',
            success: function(html) {
                $('#shift_history').html(html);
            }
        });
    }
});
</script>

<?= view('partial/footer') ?>
