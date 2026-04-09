<?php
/**
 * @var array|null $department
 * @var array $parent_options
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open('hr/department/save', ['id' => 'department_form', 'class' => 'form-horizontal']) ?>

<div class="form-group">
    <?= form_label(lang('Hr.name'), 'name', ['class' => 'required control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_input([
            'name' => 'name',
            'id' => 'name',
            'class' => 'form-control',
            'value' => $department['name'] ?? '',
        ]) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.parent_department'), 'parent_id', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_dropdown('parent_id', $parent_options, $department['parent_id'] ?? '', ['class' => 'form-control']) ?>
    </div>
</div>

<div class="form-group">
    <?= form_label(lang('Hr.description'), 'description', ['class' => 'control-label col-xs-3']) ?>
    <div class="col-xs-9">
        <?= form_textarea([
            'name' => 'description',
            'id' => 'description',
            'class' => 'form-control',
            'value' => $department['description'] ?? '',
            'rows' => 3,
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-xs-9 col-xs-offset-3">
        <label class="checkbox">
            <?= form_checkbox('is_active', 1, ($department['is_active'] ?? true) ? true : false) ?>
            <?= lang('Common.active') ?>
        </label>
    </div>
</div>

<?= form_hidden('id', $department['id'] ?? '') ?>

<script type="text/javascript">
$(document).ready(function() {
    $('#department_form').validate({
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
            name: 'required'
        }
    });
});
</script>
