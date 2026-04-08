<?php
/**
 * @var int|null $labor_id
 * @var int|null $project_id
 * @var int|null $employee_id
 * @var string|null $work_date
 * @var float|null $hours
 * @var float|null $hourly_rate
 * @var string|null $description
 * @var array $projects
 * @var array $employees
 */
// Set default values for null variables
$work_date = $work_date ?? date('Y-m-d');
$hours = $hours ?? 0;
$hourly_rate = $hourly_rate ?? 0;
$description = $description ?? '';
?>
<?= view('partial/header') ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?= $labor_id ? lang('Manufacturing.edit_labor_entry') : lang('Manufacturing.add_labor_entry') ?>
                </h3>
            </div>
            <div class="panel-body">
                <?= form_open('manufacturing/labor/save', ['id' => 'labor_form']) ?>
                <?php if ($labor_id): ?>
                <?= form_hidden('labor_id', $labor_id) ?>
                <?php endif; ?>

                    <div class="form-group">
                        <label for="project_id"><?= lang('Manufacturing.project') ?> *</label>
                        <?= form_dropdown('project_id',
                            array_column($projects, 'project_name', 'project_id'),
                            $project_id,
                            ['class' => 'form-control', 'required' => 'required']
                        ) ?>
                    </div>

                    <div class="form-group">
                        <label for="employee_id"><?= lang('Manufacturing.employee') ?> *</label>
                        <?= form_dropdown('employee_id',
                            array_column($employees, 'first_name', 'person_id'),
                            $employee_id,
                            ['class' => 'form-control', 'required' => 'required']
                        ) ?>
                    </div>

                    <div class="form-group">
                        <label for="work_date"><?= lang('Manufacturing.work_date') ?> *</label>
                        <?= form_input('work_date', $work_date,
                            ['class' => 'form-control date-picker', 'required' => 'required']
                        ) ?>
                    </div>

                    <div class="form-group">
                        <label for="hours"><?= lang('Manufacturing.hours') ?> *</label>
                        <?= form_input('hours', $hours,
                            ['class' => 'form-control', 'type' => 'number', 'step' => '0.25', 'min' => '0', 'required' => 'required']
                        ) ?>
                    </div>

                    <div class="form-group">
                        <label for="hourly_rate"><?= lang('Manufacturing.hourly_rate') ?> *</label>
                        <?= form_input('hourly_rate', $hourly_rate,
                            ['class' => 'form-control', 'type' => 'number', 'step' => '0.01', 'min' => '0', 'required' => 'required']
                        ) ?>
                    </div>

                    <div class="form-group">
                        <label for="description"><?= lang('Manufacturing.description') ?></label>
                        <?= form_textarea('description', $description,
                            ['class' => 'form-control', 'rows' => '3']
                        ) ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <span class="glyphicon glyphicon-ok"></span> <?= lang('Common.save') ?>
                        </button>
                        <a href="<?= site_url('manufacturing/labor') ?>" class="btn btn-default">
                            <span class="glyphicon glyphicon-arrow-left"></span> <?= lang('Common.cancel') ?>
                        </a>
                    </div>
                <?= form_close() ?>
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

    $('#labor_form').on('submit', function(e) {
        e.preventDefault();
        $.post('<?= site_url("manufacturing/labor/save") ?>', $(this).serialize())
            .done(function(response) {
                if (response.success) {
                    $.notify(response.message, 'success');
                    setTimeout(function() {
                        window.location.href = '<?= site_url("manufacturing/labor") ?>';
                    }, 1000);
                } else {
                    $.notify(response.message, 'error');
                }
            });
    });
});
</script>

<?= view('partial/footer') ?>
