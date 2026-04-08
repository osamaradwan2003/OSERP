<?php
/**
 * @var int $employeeId
 * @var array $allRules
 * @var array $employeeRules
 */
?>

<?= view('partial/header') ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('Hr.assigned_rules') ?></h3>
    </div>
    <div class="panel-body">
        <?php if (empty($employeeRules)): ?>
            <p class="text-muted"><?= lang('Hr.no_rules_assigned') ?></p>
        <?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?= lang('Hr.rule_name') ?></th>
                        <th><?= lang('Hr.rule_type') ?></th>
                        <th><?= lang('Hr.custom_value') ?></th>
                        <th><?= lang('Common.actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employeeRules as $er): ?>
                        <tr>
                            <td><?= esc($er['name']) ?></td>
                            <td><?= lang("Hr.type_{$er['rule_type']}") ?></td>
                            <td><?= $er['custom_value'] !== null ? to_currency($er['custom_value']) : '-' ?></td>
                            <td>
                                <button class="btn btn-xs btn-danger btn-remove-rule" data-rule-id="<?= $er['rule_id'] ?>">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><?= lang('Hr.assign_rule') ?></h3>
    </div>
    <div class="panel-body">
        <?= form_open('hr/assign_salary_rule', ['id' => 'assign_rule_form', 'class' => 'form-horizontal']) ?>
        
        <div class="form-group">
            <?= form_label(lang('Hr.rule'), 'rule_id', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_dropdown('rule_id', $allRules, '', ['class' => 'form-control', 'id' => 'rule_id']) ?>
            </div>
        </div>
        
        <div class="form-group">
            <?= form_label(lang('Hr.custom_value'), 'custom_value', ['class' => 'control-label col-xs-3']) ?>
            <div class="col-xs-8">
                <?= form_input([
                    'name' => 'custom_value',
                    'id' => 'custom_value',
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => '0.01',
                    'placeholder' => lang('Hr.leave_empty_default')
                ]) ?>
            </div>
        </div>
        
        <?= form_hidden('employee_id', $employeeId) ?>
        
        <div class="form-group">
            <div class="col-xs-offset-3 col-xs-8">
                <button type="submit" class="btn btn-primary"><?= lang('Common.submit') ?></button>
            </div>
        </div>
        
        <?= form_close() ?>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#assign_rule_form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?= site_url('hr/assign_salary_rule') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
    
    $('.btn-remove-rule').on('click', function() {
        if (!confirm('<?= lang('Common.confirm_delete') ?>')) return;
        
        $.ajax({
            url: '<?= site_url('hr/remove_salary_rule') ?>',
            type: 'POST',
            data: {
                employee_id: <?= $employeeId ?>,
                rule_id: $(this).data('rule-id')
            },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
});
</script>

<?= view('partial/footer') ?>
