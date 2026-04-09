<?php
/**
 * @var array|null $request
 * @var array $leave_type_options
 * @var array $leave_balances
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open('hr/leave_request/save', ['id' => 'leave_request_form', 'class' => 'form-horizontal']) ?>

<?php if (!empty($leave_balances)): ?>
<div class="alert alert-info" style="margin-bottom: 15px;">
    <strong><?= lang('Hr.leave_balance') ?>:</strong>
    <div style="margin-top: 10px;">
        <?php foreach ($leave_balances as $balance): ?>
            <span class="label label-<?= $balance['remaining'] > 0 ? 'success' : 'default' ?>" style="margin-right: 10px; font-size: 12px; padding: 5px 10px;">
                <?= esc($balance['type_name']) ?>: <?= $balance['remaining'] ?> / <?= $balance['total_days'] ?> <?= lang('Hr.days') ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="form-group">
    <?= form_label(lang('Hr.leave_type'), 'leave_type_id', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('leave_type_id', $leave_type_options, (string)($request['leave_type_id'] ?? ''), ['class' => 'form-control', 'id' => 'leave_type_id']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.start_date'), 'start_date', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'start_date',
            'id' => 'start_date',
            'class' => 'form-control',
            'value' => $request['start_date'] ?? '',
            'type' => 'date',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.end_date'), 'end_date', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'end_date',
            'id' => 'end_date',
            'class' => 'form-control',
            'value' => $request['end_date'] ?? '',
            'type' => 'date',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-xs-9 col-xs-offset-3">
        <div class="alert alert-warning" id="days_preview" style="display: none; margin: 0;">
            <strong><?= lang('Hr.total_days') ?>:</strong> <span id="calculated_days">0</span> <?= lang('Hr.days') ?>
        </div>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.reason'), 'reason', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_textarea([
            'name' => 'reason',
            'id' => 'reason',
            'class' => 'form-control',
            'rows' => 4,
        ], $request['reason'] ?? '') ?>
    </div>
</div>

<?= form_hidden('id', (string)($request['id'] ?? '')) ?>

<script type="text/javascript">
$(document).ready(function() {
    function calculateDays() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        
        if (startDate && endDate) {
            var start = new Date(startDate);
            var end = new Date(endDate);
            
            if (end >= start) {
                var diffTime = Math.abs(end - start);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                $('#calculated_days').text(diffDays);
                $('#days_preview').show();
            } else {
                $('#days_preview').hide();
            }
        } else {
            $('#days_preview').hide();
        }
    }
    
    $('#start_date, #end_date').change(calculateDays);
    
    $('#leave_request_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    if (response.success) {
                        window.location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                dataType: 'json'
            });
        },
        rules: {
            leave_type_id: 'required',
            start_date: 'required',
            end_date: {
                required: true,
                greaterThanEqual: '#start_date'
            }
        },
        messages: {
            end_date: {
                greaterThanEqual: 'End date must be after or equal to start date'
            }
        }
    });
    
    $.validator.addMethod('greaterThanEqual', function(value, element, param) {
        if (!value || !$(param).val()) return true;
        return new Date(value) >= new Date($(param).val());
    });
});
</script>
