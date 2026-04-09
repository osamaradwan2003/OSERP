<?php
/**
 * @var array|null $request
 * @var array $leave_type_options
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open('hr/leave_request/save', ['id' => 'leave_request_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.leave_type'), 'leave_type_id', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('leave_type_id', $leave_type_options, $request['leave_type_id'] ?? '', ['class' => 'form-control']) ?>
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
    <?= form_label(lang('Hr.reason'), 'reason', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_textarea([
            'name' => 'reason',
            'id' => 'reason',
            'class' => 'form-control',
            'value' => $request['reason'] ?? '',
            'rows' => 4,
        ]) ?>
    </div>
</div>

<?= form_hidden('id', $request['id'] ?? '') ?>

<script type="text/javascript">
$(document).ready(function() {
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
            end_date: 'required'
        }
    });
});
</script>
