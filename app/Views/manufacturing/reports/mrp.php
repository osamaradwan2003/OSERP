<?php
/**
 * @var int|null $project_id
 * @var array $projects
 * @var array|null $mrp
 */
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-list-alt"></i>
                    <?= lang('Manufacturing.mrp_report') ?>
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

                <?php if (!empty($mrp)): ?>
                <hr>
                <h4><?= lang('Manufacturing.material_requirements') ?></h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?= lang('Manufacturing.item') ?></th>
                            <th><?= lang('Manufacturing.required_quantity') ?></th>
                            <th><?= lang('Manufacturing.available_quantity') ?></th>
                            <th><?= lang('Manufacturing.shortage') ?></th>
                            <th><?= lang('Manufacturing.estimated_cost') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mrp as $item): ?>
                        <tr class="<?= $item['shortage'] > 0 ? 'warning' : '' ?>">
                            <td><?= esc($item['item_name']) ?></td>
                            <td class="text-right"><?= to_quantity_decimals($item['required_quantity']) ?></td>
                            <td class="text-right"><?= to_quantity_decimals($item['available_quantity']) ?></td>
                            <td class="text-right">
                                <?php if ($item['shortage'] > 0): ?>
                                <span class="label label-warning"><?= to_quantity_decimals($item['shortage']) ?></span>
                                <?php else: ?>
                                <span class="label label-success">0</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right"><?= to_currency($item['estimated_cost']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php elseif ($project_id): ?>
                <div class="alert alert-info">
                    <?= lang('Manufacturing.no_mrp_data') ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
