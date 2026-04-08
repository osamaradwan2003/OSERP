<?php
/**
 * @var string $controller_name
 * @var array $account
 */
?>

<div id="required_fields_message"><?= lang('Common.fields_required_message') ?></div>
<ul id="error_message_box" class="error_message_box"></ul>

<?= form_open("$controller_name/save/{$account['account_id']}", ['id' => 'cashflow_account_form', 'class' => 'form-horizontal']) ?>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.account_name'), 'name', ['class' => 'required control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_input([
                'name' => 'name',
                'id' => 'name',
                'class' => 'form-control input-sm',
                'value' => $account['name']
            ]) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.account_type'), 'type', ['class' => 'required control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_dropdown('type', [
                'bank' => lang('Cashflow.account_type_bank'),
                'cash' => lang('Cashflow.account_type_cash'),
                'other' => lang('Cashflow.account_type_other')
            ], $account['type'], ['id' => 'type', 'class' => 'form-control input-sm']) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.opening_balance'), 'opening_balance', ['class' => 'required control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_input([
                'name' => 'opening_balance',
                'id' => 'opening_balance',
                'class' => 'form-control input-sm',
                'value' => to_currency_no_money($account['opening_balance'])
            ]) ?>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <?= form_label(lang('Cashflow.is_active'), 'is_active', ['class' => 'control-label col-xs-3']) ?>
        <div class="col-xs-8">
            <?= form_checkbox('is_active', 1, (bool) $account['is_active']) ?>
        </div>
    </div>

<?= form_close() ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('#cashflow_account_form').validate($.extend({
            submitHandler: function(form) {
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function(response) {
                        dialog_support.hide();
                        table_support.handle_submit('cashflow_accounts', response);
                    }
                });
            },
            rules: {
                name: 'required',
                opening_balance: {
                    required: true,
                    number: true
                }
            },
            messages: {
                name: "<?= lang('Cashflow.account_name_required') ?>",
                opening_balance: "<?= lang('Cashflow.opening_balance_invalid') ?>"
            }
        }, form_support.error));
    });
</script>
