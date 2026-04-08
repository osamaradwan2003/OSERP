<?php
/**
 * @var array|null $position
 * @var array $department_options
 */
?>

<?= form_open('hr/save_position', ['id' => 'position_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.name'), 'name', ['class' => 'control-label col-xs-3 required']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'name',
            'id' => 'name',
            'class' => 'form-control',
            'value' => $position['name'] ?? ''
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.description'), 'description', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <?= form_textarea([
            'name' => 'description',
            'id' => 'description',
            'class' => 'form-control',
            'value' => $position['description'] ?? '',
            'rows' => 3
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.department'), 'department_id', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <?= form_dropdown('department_id', $department_options, $position['department_id'] ?? '', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.level'), 'level', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'level',
            'id' => 'level',
            'class' => 'form-control',
            'type' => 'number',
            'value' => $position['level'] ?? 1
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Common.active'), 'is_active', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <div class="checkbox">
            <label>
                <?= form_checkbox('is_active', 1, ($position['is_active'] ?? true) ? true : false) ?>
            </label>
        </div>
    </div>
</div>

<?= form_hidden('id', $position['id'] ?? '') ?>

<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#position_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    table_support.handle_submit("hr/positions", response);
                },
                dataType: 'json'
            });
        },
        rules: {
            name: 'required'
        },
        messages: {
            name: "<?= lang('Common.required') ?>"
        }
    });
});
</script>
