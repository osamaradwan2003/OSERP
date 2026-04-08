<?php
/**
 * @var array $rules
 * @var array $groups
 */
?>

<?= view('partial/header') ?>

<div id="title_bar" class="btn-toolbar">
    <h2 class="pull-left"><?= lang('Hr.salary_rules') ?></h2>
    <div class="pull-right">
        <button class="btn btn-default btn-sm" onclick="showGroupsModal()">
            <span class="glyphicon glyphicon-folder-open"></span> <?= lang('Hr.manage_groups') ?>
        </button>
        <button class="btn btn-info btn-sm modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" 
                data-href="<?= site_url('hr/salary_rule') ?>" title="<?= lang('Hr.new_rule') ?>">
            <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.new_rule') ?>
        </button>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?= lang('Common.id') ?></th>
                <th><?= lang('Hr.rule_code') ?></th>
                <th><?= lang('Hr.rule_name') ?></th>
                <th><?= lang('Hr.rule_group') ?></th>
                <th><?= lang('Hr.rule_type') ?></th>
                <th><?= lang('Hr.value') ?></th>
                <th><?= lang('Hr.scope') ?></th>
                <th><?= lang('Common.status') ?></th>
                <th><?= lang('Common.actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rules as $rule): ?>
                <tr>
                    <td><?= $rule['id'] ?></td>
                    <td><?= esc($rule['code']) ?></td>
                    <td><?= esc($rule['name']) ?></td>
                    <td><?= esc($rule['group_name'] ?? '-') ?></td>
                    <td>
                        <?php
                        $typeClass = match($rule['rule_type']) {
                            'fixed' => 'default',
                            'percentage' => 'info',
                            'formula' => 'warning',
                            'conditional' => 'primary',
                            default => 'default'
                        };
                        ?>
                        <span class="label label-<?= $typeClass ?>"><?= lang("Hr.type_{$rule['rule_type']}") ?></span>
                    </td>
                    <td>
                        <?php
                        if ($rule['rule_type'] === 'percentage') {
                            echo $rule['value'] . '%';
                        } elseif ($rule['rule_type'] === 'fixed') {
                            echo to_currency($rule['value']);
                        } elseif ($rule['rule_type'] === 'formula') {
                            echo '-';
                        } else {
                            echo to_currency($rule['value']);
                        }
                        ?>
                    </td>
                    <td><?= lang("Hr.scope_{$rule['scope']}") ?></td>
                    <td>
                        <?php if ($rule['is_active']): ?>
                            <span class="label label-success"><?= lang('Common.active') ?></span>
                        <?php else: ?>
                            <span class="label label-default"><?= lang('Common.inactive') ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-xs btn-warning modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
                                data-href="<?= site_url("hr/salary_rule/{$rule['id']}") ?>" title="<?= lang('Common.edit') ?>">
                            <span class="glyphicon glyphicon-edit"></span>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($rules)): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted"><?= lang('Common.no_data') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
function showGroupsModal() {
    window.location.href = '<?= site_url('hr/salary_rule_groups') ?>';
}
</script>

<?= view('partial/footer') ?>
