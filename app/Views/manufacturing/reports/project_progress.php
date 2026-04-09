<?php
/**
 * @var int|null $project_id
 * @var array $projects
 * @var array|null $progress
 */
?>
<?= view('partial/header') ?>

<style>
.mfg-report-page { padding: 20px 0; }
.mfg-breadcrumb { padding: 15px 0; }
.mfg-breadcrumb .breadcrumb { margin: 0; padding: 0; background: transparent; }
.mfg-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.mfg-page-header h1 { margin: 0; font-size: 28px; font-weight: 600; color: #2c3e50; }
.mfg-report-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.mfg-report-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
    padding: 15px 20px;
}
.mfg-report-card .panel-body { padding: 20px; }
.mfg-report-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}
.mfg-report-form .form-group { margin-bottom: 15px; }
.mfg-report-form .form-control { border-radius: 6px; }
.mfg-progress-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    transition: transform 0.2s, box-shadow 0.2s;
}
.mfg-progress-card:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
.mfg-progress-card .panel-heading {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-radius: 12px 12px 0 0;
    font-weight: 600;
    padding: 15px 20px;
}
.mfg-progress-card .panel-body { padding: 20px; }
.mfg-progress-bar {
    height: 24px;
    border-radius: 12px;
    background: #e9ecef;
    overflow: hidden;
}
.mfg-progress-bar .progress-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    height: 100%;
    border-radius: 12px;
    transition: width 0.6s ease;
}
</style>

<div class="mfg-report-page">
    <div class="mfg-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?= site_url('manufacturing') ?>"><?= lang('Manufacturing.module') ?></a></li>
            <li><a href="<?= site_url('manufacturing/reports') ?>"><?= lang('Manufacturing.reports') ?></a></li>
            <li class="active"><?= lang('Manufacturing.project_progress_report') ?></li>
        </ol>
    </div>

    <div class="mfg-page-header">
        <h1><span class="glyphicon glyphicon-stats" style="color: #667eea; margin-right: 10px;"></span><?= lang('Manufacturing.project_progress_report') ?></h1>
    </div>

    <div class="mfg-report-card panel panel-default">
        <div class="panel-body">
            <form class="form-inline" method="get">
                <div class="mfg-report-form">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_id"><?= lang('Manufacturing.project') ?>:</label>
                                <?= form_dropdown('project_id',
                                    ['' => lang('Common.all')] + array_column($projects, 'project_name', 'project_id'),
                                    $project_id,
                                    ['class' => 'form-control', 'style' => 'width: 100%;']
                                ) ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <span class="glyphicon glyphicon-search"></span> <?= lang('Common.search') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <?php if (!empty($progress)): ?>
            <div class="row">
                <?php foreach ($progress as $stage): ?>
                <div class="col-md-4">
                    <div class="mfg-progress-card panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title"><?= esc($stage['stage_name']) ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="mfg-progress-bar">
                                <div class="progress-bar" role="progressbar"
                                     style="width: <?= min(100, $stage['completion_percentage']) ?>%"
                                     aria-valuenow="<?= $stage['completion_percentage'] ?>"
                                     aria-valuemin="0" aria-valuemax="100">
                                    <?= round($stage['completion_percentage']) ?>%
                                </div>
                            </div>
                            <div style="margin-top: 15px;">
                                <p><strong><?= lang('Manufacturing.status') ?>:</strong> <?= esc($stage['status']) ?></p>
                                <p><strong><?= lang('Manufacturing.start_date') ?>:</strong> <?= esc($stage['start_date'] ?? '-') ?></p>
                                <p><strong><?= lang('Manufacturing.end_date') ?>:</strong> <?= esc($stage['end_date'] ?? '-') ?></p>
                            </div>
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

<?= view('partial/footer') ?>
