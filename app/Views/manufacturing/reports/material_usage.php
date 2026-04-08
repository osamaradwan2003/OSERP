<?php
/**
 * @var int|null $project_id
 * @var string $start_date
 * @var string $end_date
 * @var array $projects
 * @var array|null $materials
 */
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-tasks"></i>
                    <?= lang('Manufacturing.material_usage_report') ?>
                </h3>
            </div>
            <div class="panel-body">
                <form class="form-inline" method="get">
                    <div class="form-group">
                        <label for="project_id"><?= lang('Manufacturing.project') ?>:</label>
                        <?= form_dropdown('project_id',
                            ['' => lang('Common.all')] + array_column($projects, 'project_name', 'project_id'),
                            $project_id,
                            ['class' => 'form-control']
                        ) ?>
                    </div>
                    <div class="form-group">
                        <label for="start_date"><?= lang('Common.start_date') ?>:</label>
                        <input type="text" name="start_date" value="<?= esc($start_date) ?>" class="form-control date-picker">
                    </div>
                    <div class="form-group">
                        <label for="end_date"><?= lang('Common.end_date') ?>:</label>
                        <input type="text" name="end_date" value="<?= esc($end_date) ?>" class="form-control date-picker">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-search"></span> <?= lang('Common.search') ?>
                    </button>
                </form>

                <?php if (!empty($materials)): ?>
                <hr>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?= lang('Manufacturing.item') ?></th>
                            <th><?= lang('Manufacturing.quantity_used') ?></th>
                            <th><?= lang('Manufacturing.unit_cost') ?></th>
                            <th><?= lang('Manufacturing.total_cost') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materials as $material): ?>
                        <tr>
                            <td><?= esc($material['item_name']) ?></td>
                            <td class="text-right"><?= to_quantity_decimals($material['quantity']) ?></td>
                            <td class="text-right"><?= to_currency($material['unit_cost']) ?></td>
                            <td class="text-right"><?= to_currency($material['total_cost']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php elseif ($project_id): ?>
                <div class="alert alert-info">
                    <?= lang('Manufacturing.no_materials_found') ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.date-picker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
});
</script>

<?= view('partial/footer') ?>
