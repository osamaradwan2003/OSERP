<?php
/**
 * @var array|null $request
 * @var array $leave_type_options
 */
?>

<?= form_open('hr/save_leave_request', ['id' => 'leave_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.leave_type'), 'leave_type_id', ['class' => 'control-label col-xs-3 required']) ?>
    <div class="col-xs-8">
        <?= form_dropdown('leave_type_id', $leave_type_options, $request['leave_type_id'] ?? '', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.start_date'), 'start_date', ['class' => 'control-label col-xs-3 required']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'start_date',
            'id' => 'start_date',
            'class' => 'form-control',
            'type' => 'date',
            'value' => $request['start_date'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.end_date'), 'end_date', ['class' => 'control-label col-xs-3 required']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'end_date',
            'id' => 'end_date',
            'class' => 'form-control',
            'type' => 'date',
            'value' => $request['end_date'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.reason'), 'reason', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <?= form_textarea([
            'name' => 'reason',
            'id' => 'reason',
            'class' => 'form-control',
            'value' => $request['reason'] ?? '',
            'rows' => 4
        ]) ?>
    </div>
</div>

<?= form_hidden('id', $request['id'] ?? '') ?>

<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#leave_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    table_support.handle_submit("hr/leave_requests", response);
                },
                dataType: 'json'
            });
        },
        rules: {
            leave_type_id: 'required',
            start_date: 'required',
            end_date: 'required'
        },
        messages: {
            leave_type_id: "<?= lang('Common.required') ?>",
            start_date: "<?= lang('Common.required') ?>",
            end_date: "<?= lang('Common.required') ?>"
        }
    });
});
</script>
