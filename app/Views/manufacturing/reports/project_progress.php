<?php
/**
 * @var int|null $project_id
 * @var array $projects
 * @var array|null $progress
 */
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-stats"></i>
                    <?= lang('Manufacturing.project_progress_report') ?>
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
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-search"></span> <?= lang('Common.search') ?>
                    </button>
                </form>

                <?php if (!empty($progress)): ?>
                <hr>
                <div class="row">
                    <?php foreach ($progress as $stage): ?>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><?= esc($stage['stage_name']) ?></h4>
                            </div>
                            <div class="panel-body">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: <?= min(100, $stage['completion_percentage']) ?>%"
                                         aria-valuenow="<?= $stage['completion_percentage'] ?>"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <?= round($stage['completion_percentage']) ?>%
                                    </div>
                                </div>
                                <p><strong><?= lang('Manufacturing.status') ?>:</strong> <?= esc($stage['status']) ?></p>
                                <p><strong><?= lang('Manufacturing.start_date') ?>:</strong> <?= esc($stage['start_date'] ?? '-') ?></p>
                                <p><strong><?= lang('Manufacturing.end_date') ?>:</strong> <?= esc($stage['end_date'] ?? '-') ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php elseif ($project_id): ?>
                <div class="alert alert-info">
                    <?= lang('Manufacturing.no_progress_found') ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= view('partial/footer') ?>
