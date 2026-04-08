<?php
/**
 * @var int|null $project_id
 * @var array $projects
 * @var array|null $variance
 */
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-warning-sign"></i>
                    <?= lang('Manufacturing.cost_variance_report') ?>
                </h3>
            </div>
            <div class="panel-body">
                <form class="form-inline" method="get">
                    <div class="form-group">
                        <label for="project_id"><?= lang('Manufacturing.project') ?>:</label>
                        <?= form_dropdown('project_id',
                            ['' => lang('Common.select')] + array_column($projects, 'project_name', 'project_id'),
                            $project_id,
                            ['class' => 'form-control']
                        ) ?>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-search"></span> <?= lang('Common.search') ?>
                    </button>
                </form>

                <?php if (!empty($variance)): ?>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><?= lang('Manufacturing.cost_summary') ?></h4>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <td><strong><?= lang('Manufacturing.budgeted_cost') ?></strong></td>
                                        <td class="text-right"><?= to_currency($variance['budgeted_cost']) ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong><?= lang('Manufacturing.actual_cost') ?></strong></td>
                                        <td class="text-right"><?= to_currency($variance['actual_cost']) ?></td>
                                    </tr>
                                    <tr class="<?= $variance['variance'] > 0 ? 'danger' : 'success' ?>">
                                        <td><strong><?= lang('Manufacturing.variance') ?></strong></td>
                                        <td class="text-right">
                                            <strong><?= to_currency($variance['variance']) ?></strong>
                                            <?php if ($variance['variance'] > 0): ?>
                                            <span class="label label-danger"><?= lang('Manufacturing.over_budget') ?></span>
                                            <?php else: ?>
                                            <span class="label label-success"><?= lang('Manufacturing.under_budget') ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><?= lang('Manufacturing.cost_breakdown') ?></h4>
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><?= lang('Manufacturing.cost_type') ?></th>
                                            <th><?= lang('Manufacturing.budgeted') ?></th>
                                            <th><?= lang('Manufacturing.actual') ?></th>
                                            <th><?= lang('Manufacturing.variance') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($variance['breakdown'] as $item): ?>
                                        <tr class="<?= $item['variance'] > 0 ? 'warning' : '' ?>">
                                            <td><?= esc($item['cost_type']) ?></td>
                                            <td class="text-right"><?= to_currency($item['budgeted']) ?></td>
                                            <td class="text-right"><?= to_currency($item['actual']) ?></td>
                                            <td class="text-right"><?= to_currency($item['variance']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php elseif ($project_id): ?>
                <div class="alert alert-info">
                    <?= lang('Manufacturing.no_variance_data') ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
