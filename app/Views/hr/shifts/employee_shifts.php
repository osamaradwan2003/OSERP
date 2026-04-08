<?php
/**
 * @var array $employeeId
 * @var array $shift_options
 */
?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?= lang('Hr.shift_name') ?></th>
                <th><?= lang('Hr.start_time') ?></th>
                <th><?= lang('Hr.end_time') ?></th>
                <th><?= lang('Hr.working_hours') ?></th>
                <th><?= lang('Hr.effective_from') ?></th>
                <th><?= lang('Hr.effective_to') ?></th>
            </tr>
        </thead>
        <tbody id="shift_history">
            <!-- Filled dynamically -->
        </tbody>
    </table>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('Hr.assign_shift') ?></h3>
    </div>
    <div class="panel-body">
        <?= form_open('hr/assign_shift', ['id' => 'assign_shift_form', 'class' => 'form-horizontal']) ?>
        
        <div class="form-group">
            <?= form_label(lang('Hr.shift'), 'shift_id', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_dropdown('shift_id', $shift_options, '', ['class' => 'form-control', 'id' => 'shift_id']) ?>
            </div>
        </div>
        
        <div class="form-group">
            <?= form_label(lang('Hr.effective_from'), 'effective_from', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input([
                    'name' => 'effective_from',
                    'id' => 'effective_from',
                    'class' => 'form-control',
                    'type' => 'date',
                    'value' => date('Y-m-d')
                ]) ?>
            </div>
        </div>
        
        <div class="form-group">
            <?= form_label(lang('Hr.effective_to'), 'effective_to', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input([
                    'name' => 'effective_to',
                    'id' => 'effective_to',
                    'class' => 'form-control',
                    'type' => 'date'
                ]) ?>
            </div>
        </div>
        
        <?= form_hidden('employee_id', $employeeId) ?>
        
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-8">
                <button type="submit" class="btn btn-primary"><?= lang('Common.submit') ?></button>
            </div>
        </div>
        
        <?= form_close() ?>
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
