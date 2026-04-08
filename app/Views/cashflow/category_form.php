<?php
/**
 * @var string $controller_name
 * @var array $category
 * @var array $type_options
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open("$controller_name/save/{$category['category_id']}", ['id' => 'cashflow_category_form', 'class' => 'form-horizontal']) ?>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.category_name'), 'name', ['class' => 'required control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_input([
                'name' => 'name',
                'id' => 'name',
                'class' => 'form-control input-sm',
                'value' => $category['name']
            ]) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.category_type'), 'entry_type', ['class' => 'required control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_dropdown('entry_type', $type_options, $category['entry_type'], ['id' => 'entry_type', 'class' => 'form-control input-sm']) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.is_active'), 'is_active', ['class' => 'control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_checkbox('is_active', 1, (bool) $category['is_active']) ?>
        </div>
    </div>

<?= form_close() ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#cashflow_category_form').validate($.extend({
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function(response) {
                        dialog_support.hide();
                        table_support.handle_submit('cashflow_categories', response);
                    }
                });
            },
            rules: {
                name: 'required'
            },
            messages: {
                name: "<?= lang('Cashflow.category_name_required') ?>"
            }
        }, form_support.error));
    });
</script>

