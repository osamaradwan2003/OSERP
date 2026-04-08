<?php
/**
 * @var int|null $project_id
 * @var string $start_date
 * @var string $end_date
 * @var array $projects
 * @var array|null $costs
 */
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-usd"></i>
                    <?= lang('Manufacturing.project_costs_report') ?>
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

                <?php if (!empty($costs)): ?>
                <hr>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?= lang('Manufacturing.cost_type') ?></th>
                            <th><?= lang('Manufacturing.description') ?></th>
                            <th><?= lang('Manufacturing.amount') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($costs as $cost): ?>
                        <tr>
                            <td><?= esc($cost['cost_type']) ?></td>
                            <td><?= esc($cost['description']) ?></td>
                            <td class="text-right"><?= to_currency($cost['amount']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="info">
                            <td colspan="2"><strong><?= lang('Manufacturing.total') ?></strong></td>
                            <td class="text-right"><strong><?= to_currency(array_sum(array_column($costs, 'amount'))) ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                <?php elseif ($project_id): ?>
                <div class="alert alert-info">
                    <?= lang('Manufacturing.no_costs_found') ?>
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
