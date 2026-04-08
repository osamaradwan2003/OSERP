<?php
/**
 * @var array|null $rule
 * @var array $group_options
 * @var array $department_options
 * @var array $position_options
 */
?>

<?= form_open('hr/save_salary_rule', ['id' => 'rule_form', 'class' => 'form-horizontal']) ?>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a href="#rule_basic" aria-controls="rule_basic" role="tab" data-toggle="tab"><?= lang('Hr.basic_info') ?></a>
    </li>
    <li role="presentation">
        <a href="#rule_value" aria-controls="rule_value" role="tab" data-toggle="tab"><?= lang('Hr.value_config') ?></a>
    </li>
    <li role="presentation">
        <a href="#rule_scope" aria-controls="rule_scope" role="tab" data-toggle="tab"><?= lang('Hr.scope') ?></a>
    </li>
</ul>

<div class="tab-content" style="margin-top: 20px;">
    <div role="tabpanel" class="tab-pane active" id="rule_basic">
        <fieldset>
            <div class="form-group">
                <?= form_label(lang('Hr.rule_name'), 'name', ['class' => 'control-label col-xs-3 required']) ?>
                <div class="col-xs-8">
                    <?= form_input([
                        'name' => 'name',
                        'id' => 'name',
                        'class' => 'form-control',
                        'value' => $rule['name'] ?? ''
                    ]) ?>
                </div>
            </div>
            
            <div class="form-group">
                <?= form_label(lang('Hr.rule_code'), 'code', ['class' => 'control-label col-xs-3 required']) ?>
                <div class="col-xs-8">
                    <?= form_input([
                        'name' => 'code',
                        'id' => 'code',
                        'class' => 'form-control',
                        'value' => $rule['code'] ?? ''
                    ]) ?>
                </div>
            </div>
            
            <div class="form-group">
                <?= form_label(lang('Hr.rule_group'), 'group_id', ['class' => 'control-label col-xs-3 required']) ?>
                <div class="col-xs-8">
                    <?= form_dropdown('group_id', $group_options, $rule['group_id'] ?? '', ['class' => 'form-control']) ?>
                </div>
            </div>
            
            <div class="form-group">
                <?= form_label(lang('Hr.rule_type'), 'rule_type', ['class' => 'control-label col-xs-3 required']) ?>
                <div class="col-xs-8">
                    <?= form_dropdown('rule_type', [
                        'fixed' => lang('Hr.type_fixed'),
                        'percentage' => lang('Hr.type_percentage'),
                        'formula' => lang('Hr.type_formula'),
                        'conditional' => lang('Hr.type_conditional')
                    ], $rule['rule_type'] ?? 'fixed', ['class' => 'form-control', 'id' => 'rule_type']) ?>
                </div>
            </div>
            
            <div class="form-group">
                <?= form_label(lang('Hr.description'), 'description', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_textarea([
                        'name' => 'description',
                        'id' => 'description',
                        'class' => 'form-control',
                        'value' => $rule['description'] ?? '',
                        'rows' => 3
                    ]) ?>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-8">
                    <div class="checkbox">
                        <label>
                            <?= form_checkbox('is_active', 1, ($rule['is_active'] ?? true) ? true : false) ?>
                            <?= lang('Common.active') ?>
                        </label>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <div role="tabpanel" class="tab-pane" id="rule_value">
        <fieldset>
            <div class="form-group" id="value_group">
                <?= form_label(lang('Hr.value'), 'value', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_input([
                        'name' => 'value',
                        'id' => 'value',
                        'class' => 'form-control',
                        'type' => 'number',
                        'step' => '0.01',
                        'value' => $rule['value'] ?? 0
                    ]) ?>
                </div>
            </div>
            
            <div class="form-group" id="based_on_group">
                <?= form_label(lang('Hr.based_on'), 'based_on', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_dropdown('based_on', [
                        'none' => lang('Hr.based_on_none'),
                        'basic' => lang('Hr.based_on_basic'),
                        'gross' => lang('Hr.based_on_gross'),
                        'attendance' => lang('Hr.based_on_attendance')
                    ], $rule['based_on'] ?? 'none', ['class' => 'form-control']) ?>
                </div>
            </div>
            
            <div class="form-group" id="formula_group" style="display: none;">
                <?= form_label(lang('Hr.formula'), 'formula', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_textarea([
                        'name' => 'formula',
                        'id' => 'formula',
                        'class' => 'form-control',
                        'value' => $rule['formula'] ?? '',
                        'rows' => 4,
                        'placeholder' => 'IF(attendance>=26, basic*0.1, 0)'
                    ]) ?>
                    <p class="help-block"><?= lang('Hr.formula_help') ?></p>
                </div>
            </div>
            
            <div class="form-group">
                <?= form_label(lang('Hr.priority'), 'priority', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_input([
                        'name' => 'priority',
                        'id' => 'priority',
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $rule['priority'] ?? 0
                    ]) ?>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-xs-offset-3 col-xs-8">
                    <div class="checkbox">
                        <label>
                            <?= form_checkbox('is_recurring', 1, ($rule['is_recurring'] ?? true) ? true : false) ?>
                            <?= lang('Hr.recurring') ?>
                        </label>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>

    <div role="tabpanel" class="tab-pane" id="rule_scope">
        <fieldset>
            <div class="form-group">
                <?= form_label(lang('Hr.scope'), 'scope', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <?= form_dropdown('scope', [
                        'global' => lang('Hr.scope_global'),
                        'department' => lang('Hr.scope_department'),
                        'position' => lang('Hr.scope_position'),
                        'employee' => lang('Hr.scope_employee')
                    ], $rule['scope'] ?? 'global', ['class' => 'form-control', 'id' => 'scope']) ?>
                </div>
            </div>
            
            <div class="form-group" id="scope_id_group" style="display: none;">
                <?= form_label(lang('Hr.scope_id'), 'scope_id', ['class' => 'control-label col-xs-3']) ?>
                <div class="col-xs-8">
                    <div id="scope_id_department">
                        <?= form_dropdown('scope_id', $department_options, $rule['scope_id'] ?? '', ['class' => 'form-control', 'id' => 'scope_id_dept']) ?>
                    </div>
                    <div id="scope_id_position" style="display: none;">
                        <?= form_dropdown('scope_id', $position_options, $rule['scope_id'] ?? '', ['class' => 'form-control', 'id' => 'scope_id_pos']) ?>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<?= form_hidden('id', $rule['id'] ?? '') ?>
<?= form_hidden('conditions', '') ?>

<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
    toggleRuleTypeFields();
    toggleScopeFields();
    
    $('#rule_type').change(function() {
        toggleRuleTypeFields();
    });
    
    $('#scope').change(function() {
        toggleScopeFields();
    });
    
    function toggleRuleTypeFields() {
        var type = $('#rule_type').val();
        $('#value_group').hide();
        $('#based_on_group').hide();
        $('#formula_group').hide();
        
        if (type === 'fixed') {
            $('#value_group').show();
        } else if (type === 'percentage') {
            $('#value_group').show();
            $('#based_on_group').show();
        } else if (type === 'formula') {
            $('#formula_group').show();
        } else if (type === 'conditional') {
            $('#value_group').show();
            $('#based_on_group').show();
        }
    }
    
    function toggleScopeFields() {
        var scope = $('#scope').val();
        $('#scope_id_group').hide();
        
        if (scope === 'department') {
            $('#scope_id_group').show();
            $('#scope_id_department').show();
            $('#scope_id_position').hide();
        } else if (scope === 'position') {
            $('#scope_id_group').show();
            $('#scope_id_department').hide();
            $('#scope_id_position').show();
        } else if (scope === 'employee') {
            $('#scope_id_group').show();
        }
    }
    
    $('#rule_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    table_support.handle_submit("hr/salary_rules", response);
                },
                dataType: 'json'
            });
        },
        rules: {
            name: 'required',
            code: 'required',
            group_id: 'required'
        },
        messages: {
            name: "<?= lang('Common.required') ?>",
            code: "<?= lang('Common.required') ?>",
            group_id: "<?= lang('Common.required') ?>"
        }
    });
});
</script>
