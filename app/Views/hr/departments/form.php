<?php
/**
 * @var array|null $department
 * @var array $parent_options
 */
?>

<?= form_open('hr/save_department', ['id' => 'department_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.name'), 'name', ['class' => 'control-label col-xs-3 required']) ?>
    <div class="col-xs-8">
        <?= form_input([
            'name' => 'name',
            'id' => 'name',
            'class' => 'form-control',
            'value' => $department['name'] ?? ''
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
            'value' => $department['description'] ?? '',
            'rows' => 3
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.parent_department'), 'parent_id', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <?= form_dropdown('parent_id', $parent_options, $department['parent_id'] ?? '', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Common.active'), 'is_active', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-8">
        <div class="checkbox">
            <label>
                <?= form_checkbox('is_active', 1, ($department['is_active'] ?? true) ? true : false) ?>
            </label>
        </div>
    </div>
</div>

<?= form_hidden('id', $department['id'] ?? '') ?>

<?= form_close() ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#department_form').validate({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                success: function(response) {
                    dialog_support.hide();
                    table_support.handle_submit("hr/departments", response);
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
