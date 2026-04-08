<?php
/**
 * Manufacturing Project Form View
 */

// Set default values for null fields
$project['project_id'] = $project['project_id'] ?? NEW_ENTRY;
$project['project_code'] = $project['project_code'] ?? '';
$project['project_name'] = $project['project_name'] ?? '';
$project['customer_id'] = $project['customer_id'] ?? '';
$project['project_status'] = $project['project_status'] ?? 'planned';
$project['priority'] = $project['priority'] ?? 'normal';
$project['start_date'] = $project['start_date'] ?? '';
$project['target_completion_date'] = $project['target_completion_date'] ?? '';
$project['estimated_hours'] = $project['estimated_hours'] ?? '';
$project['budgeted_material_cost'] = $project['budgeted_material_cost'] ?? '';
$project['budgeted_labor_cost'] = $project['budgeted_labor_cost'] ?? '';
$project['budgeted_overhead_cost'] = $project['budgeted_overhead_cost'] ?? '';
$project['project_manager_id'] = $project['project_manager_id'] ?? '';
$project['notes'] = $project['notes'] ?? '';
?>
<?= view('partial/header') ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="modal-title">
        <?= $project['project_id'] === NEW_ENTRY ? lang('Manufacturing.new_project') : lang('Manufacturing.edit_project') ?>
    </h4>
</div>

<?= form_open('manufacturing/projects/save/' . $project['project_id'], ['id' => 'project_form', 'class' => 'form-horizontal']) ?>
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.project_code') ?></label>
        <div class="col-sm-9">
            <p class="form-control-static"><?= esc($project['project_code']) ?></p>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label required"><?= lang('Manufacturing.project_name') ?></label>
        <div class="col-sm-9">
            <input type="text" name="project_name" class="form-control" value="<?= esc($project['project_name']) ?>" required>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.customer') ?></label>
        <div class="col-sm-9">
            <select name="customer_id" class="form-control select2">
                <?php foreach ($customers as $id => $name): ?>
                    <option value="<?= $id ?>" <?= $project['customer_id'] == $id ? 'selected' : '' ?>>
                        <?= esc($name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.project_status') ?></label>
        <div class="col-sm-9">
            <select name="project_status" class="form-control">
                <option value="planned" <?= $project['project_status'] === 'planned' ? 'selected' : '' ?>>
                    <?= lang('Manufacturing.status_planned') ?>
                </option>
                <option value="in_progress" <?= $project['project_status'] === 'in_progress' ? 'selected' : '' ?>>
                    <?= lang('Manufacturing.status_in_progress') ?>
                </option>
                <option value="on_hold" <?= $project['project_status'] === 'on_hold' ? 'selected' : '' ?>>
                    <?= lang('Manufacturing.status_on_hold') ?>
                </option>
                <option value="completed" <?= $project['project_status'] === 'completed' ? 'selected' : '' ?>>
                    <?= lang('Manufacturing.status_completed') ?>
                </option>
                <option value="delivered" <?= $project['project_status'] === 'delivered' ? 'selected' : '' ?>>
                    <?= lang('Manufacturing.status_delivered') ?>
                </option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.priority') ?></label>
        <div class="col-sm-9">
            <select name="priority" class="form-control">
                <option value="low" <?= $project['priority'] === 'low' ? 'selected' : '' ?>>
                    <?= lang('Manufacturing.priority_low') ?>
                </option>
                <option value="normal" <?= $project['priority'] === 'normal' ? 'selected' : '' ?>>
                    <?= lang('Manufacturing.priority_normal') ?>
                </option>
                <option value="high" <?= $project['priority'] === 'high' ? 'selected' : '' ?>>
                    <?= lang('Manufacturing.priority_high') ?>
                </option>
                <option value="urgent" <?= $project['priority'] === 'urgent' ? 'selected' : '' ?>>
                    <?= lang('Manufacturing.priority_urgent') ?>
                </option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.project_manager') ?></label>
        <div class="col-sm-9">
            <select name="project_manager_id" class="form-control select2">
                <?php foreach ($employees as $id => $name): ?>
                    <option value="<?= $id ?>" <?= $project['project_manager_id'] == $id ? 'selected' : '' ?>>
                        <?= esc($name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.start_date') ?></label>
        <div class="col-sm-9">
            <input type="date" name="start_date" class="form-control" value="<?= esc($project['start_date']) ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.target_completion_date') ?></label>
        <div class="col-sm-9">
            <input type="date" name="target_completion_date" class="form-control" value="<?= esc($project['target_completion_date']) ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.estimated_hours') ?></label>
        <div class="col-sm-9">
            <input type="number" name="estimated_hours" class="form-control" step="0.5" value="<?= esc($project['estimated_hours']) ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.budgeted_material_cost') ?></label>
        <div class="col-sm-9">
            <input type="number" name="budgeted_material_cost" class="form-control" step="0.01" value="<?= esc($project['budgeted_material_cost']) ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.budgeted_labor_cost') ?></label>
        <div class="col-sm-9">
            <input type="number" name="budgeted_labor_cost" class="form-control" step="0.01" value="<?= esc($project['budgeted_labor_cost']) ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.budgeted_overhead_cost') ?></label>
        <div class="col-sm-9">
            <input type="number" name="budgeted_overhead_cost" class="form-control" step="0.01" value="<?= esc($project['budgeted_overhead_cost']) ?>">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.notes') ?></label>
        <div class="col-sm-9">
            <textarea name="notes" class="form-control" rows="3"><?= esc($project['notes']) ?></textarea>
        </div>
    </div>

    <?php if ($project['project_id'] !== NEW_ENTRY && !empty($stages)): ?>
    <div class="form-group">
        <label class="col-sm-3 control-label"><?= lang('Manufacturing.stages') ?></label>
        <div class="col-sm-9">
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th><?= lang('Manufacturing.stage_name') ?></th>
                        <th><?= lang('Manufacturing.stage_status') ?></th>
                        <th><?= lang('Manufacturing.assigned_to') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stages as $stage): ?>
                        <tr>
                            <td><?= esc($stage['stage_name']) ?></td>
                            <td>
                                <span class="label label-<?= $stage['stage_status'] === 'completed' ? 'success' : ($stage['stage_status'] === 'in_progress' ? 'primary' : 'default') ?>">
                                    <?= lang('Manufacturing.stage_' . $stage['stage_status']) ?>
                                </span>
                            </td>
                            <td><?= esc($stage['assigned_to_name'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('Common.close') ?></button>
    <button type="submit" class="btn btn-primary"><?= lang('Common.submit') ?></button>
</div>
<?= form_close() ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });

        $('#project_form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            $.post(form.attr('action'), form.serialize(), function(response) {
                if (response.success) {
                    $.notify(response.message, 'success');
                    $('#table').bootstrapTable('refresh');
                    $('.modal').modal('hide');
                } else {
                    $.notify(response.message, 'error');
                }
            }, 'json');
        });
    });
</script>

<?= view('partial/footer') ?>
