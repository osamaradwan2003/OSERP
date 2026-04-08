<?php
/**
 * @var string $controller_name
 * @var array $type
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open("$controller_name/save/{$type['type_code']}", ['id' => 'cashflow_category_type_form', 'class' => 'form-horizontal']) ?>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.category_type_code'), 'type_code', ['class' => 'required control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_input([
                'name' => 'type_code',
                'id' => 'type_code',
                'class' => 'form-control input-sm',
                'value' => $type['type_code']
            ]) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.category_type_label'), 'type_label', ['class' => 'required control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_input([
                'name' => 'type_label',
                'id' => 'type_label',
                'class' => 'form-control input-sm',
                'value' => $type['type_label']
            ]) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.category_calc_method'), 'calc_method', ['class' => 'required control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_dropdown('calc_method', [
                'add' => lang('Cashflow.calc_add'),
                'subtract' => lang('Cashflow.calc_subtract'),
                'none' => lang('Cashflow.calc_none'),
                'transfer' => lang('Cashflow.calc_transfer')
            ], $type['calc_method'], ['id' => 'calc_method', 'class' => 'form-control input-sm']) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.is_active'), 'is_active', ['class' => 'control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_checkbox('is_active', 1, (bool) $type['is_active']) ?>
        </div>
    </div>

<?= form_close() ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#cashflow_category_type_form').validate($.extend({
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function(response) {
                        dialog_support.hide();
                        table_support.handle_submit('cashflow_category_types', response);
                    }
                });
            },
            rules: {
                type_code: 'required',
                type_label: 'required'
            },
            messages: {
                type_code: "<?= lang('Cashflow.category_type_code_required') ?>",
                type_label: "<?= lang('Cashflow.category_type_label_required') ?>"
            }
        }, form_support.error));
    });
</script>
