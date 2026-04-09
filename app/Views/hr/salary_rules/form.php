<?php
/**
 * @var array|null $rule
 * @var array $group_options
 * @var array $department_options
 * @var array $position_options
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open('hr/salary_rule/save', ['id' => 'salary_rule_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.rule_name'), 'name', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'name',
            'id' => 'name',
            'class' => 'form-control',
            'value' => $rule['name'] ?? '',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.rule_code'), 'code', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'code',
            'id' => 'code',
            'class' => 'form-control',
            'value' => $rule['code'] ?? '',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.rule_group'), 'group_id', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('group_id', $group_options, $rule['group_id'] ?? '', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.rule_type'), 'rule_type', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('rule_type', [
            'fixed' => lang('Hr.fixed'),
            'percentage' => lang('Hr.percentage'),
            'formula' => lang('Hr.formula'),
            'conditional' => lang('Hr.conditional'),
        ], $rule['rule_type'] ?? 'fixed', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.value'), 'value', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'value',
            'id' => 'value',
            'class' => 'form-control',
            'value' => $rule['value'] ?? 0,
            'type' => 'number',
            'step' => '0.01',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.based_on'), 'based_on', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('based_on', [
            'none' => lang('Hr.none'),
            'basic' => lang('Hr.basic_salary'),
            'gross' => lang('Hr.gross_salary'),
            'attendance' => lang('Hr.attendance'),
        ], $rule['based_on'] ?? 'none', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.scope'), 'scope', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('scope', [
            'global' => lang('Hr.global'),
            'department' => lang('Hr.department'),
            'position' => lang('Hr.position'),
            'employee' => lang('Hr.employee'),
        ], $rule['scope'] ?? 'global', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.priority'), 'priority', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'priority',
            'id' => 'priority',
            'class' => 'form-control',
            'value' => $rule['priority'] ?? 0,
            'type' => 'number',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.description'), 'description', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_textarea([
            'name' => 'description',
            'id' => 'description',
            'class' => 'form-control',
            'value' => $rule['description'] ?? '',
            'rows' => 3,
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-xs-9 col-xs-offset-3">
        <label class="checkbox-inline">
            <?= form_checkbox('is_active', 1, ($rule['is_active'] ?? true) ? true : false) ?>
            <?= lang('Common.active') ?>
        </label>
        <label class="checkbox-inline">
            <?= form_checkbox('is_recurring', 1, ($rule['is_recurring'] ?? true) ? true : false) ?>
            <?= lang('Hr.recurring') ?>
        </label>
    </div>
</div>

<?= form_hidden('id', $rule['id'] ?? '') ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#salary_rule_form').validate({
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
            name: 'required',
            code: 'required',
            group_id: 'required',
            rule_type: 'required'
        }
    });
});
</script>
