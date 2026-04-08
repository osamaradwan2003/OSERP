<?php
/**
 * @var array $profiles
 */
?>

<?= view('partial/header') ?>

<div id="title_bar" class="btn-toolbar">
    <h2 class="pull-left"><?= lang('Hr.employee_profiles') ?></h2>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?= lang('Common.id') ?></th>
                <th><?= lang('Hr.employee') ?></th>
                <th><?= lang('Hr.employee_number') ?></th>
                <th><?= lang('Hr.department') ?></th>
                <th><?= lang('Hr.position') ?></th>
                <th><?= lang('Hr.basic_salary') ?></th>
                <th><?= lang('Hr.hire_date') ?></th>
                <th><?= lang('Hr.status') ?></th>
                <th><?= lang('Common.actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profiles as $profile): ?>
                <tr>
                    <td><?= $profile['employee_id'] ?></td>
                    <td><?= esc($profile['first_name'] . ' ' . $profile['last_name']) ?></td>
                    <td><?= esc($profile['employee_number'] ?? '-') ?></td>
                    <td><?= esc($profile['department_name'] ?? '-') ?></td>
                    <td><?= esc($profile['position_name'] ?? '-') ?></td>
                    <td><?= to_currency($profile['basic_salary'] ?? 0) ?></td>
                    <td><?= $profile['hire_date'] ? date('d/m/Y', strtotime($profile['hire_date'])) : '-' ?></td>
                    <td>
                        <?php
                        $status = $profile['employment_status'] ?? 'active';
                        $statusClass = match($status) {
                            'active' => 'success',
                            'on_leave' => 'warning',
                            'suspended' => 'danger',
                            'terminated' => 'default',
                            default => 'default'
                        };
                        ?>
                        <span class="label label-<?= $statusClass ?>"><?= lang("Hr.status_{$status}") ?></span>
                    </td>
                    <td>
                        <a href="<?= site_url("hr/profile/{$profile['employee_id']}") ?>" class="btn btn-xs btn-primary">
                            <span class="glyphicon glyphicon-edit"></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($profiles)): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted"><?= lang('Common.no_data') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= view('partial/footer') ?>
