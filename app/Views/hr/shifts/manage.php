<?php
/**
 * @var array $shifts
 */
?>

<?= view('partial/header') ?>

<style>
.hr-page { padding: 20px 0; }
.page-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}
.page-header-bar h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    color: #2c3e50;
}
.page-header-bar h1 .glyphicon { margin-right: 12px; color: var(--primary); }
.breadcrumb-bar { margin-bottom: 20px; }
.breadcrumb-bar .breadcrumb { margin: 0; padding: 10px 0; background: transparent; }
</style>

<div class="hr-page">
    <div class="breadcrumb-bar">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><?= lang('Module.home') ?></a></li>
            <li><a href="<?= site_url('hr') ?>"><?= lang('Hr.hr_dashboard') ?></a></li>
            <li class="active"><?= lang('Hr.shifts') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1><span class="glyphicon glyphicon-time"></span><?= lang('Hr.shifts') ?></h1>
        <div>
            <button class="btn btn-primary btn-lg modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" 
                    data-href="<?= site_url('hr/shift') ?>" title="<?= lang('Hr.new_shift') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.new_shift') ?>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover" id="shift_table">
            <thead>
                <tr>
                    <th style="width: 60px;"><?= lang('Common.id') ?></th>
                    <th><?= lang('Hr.shift_name') ?></th>
                    <th><?= lang('Hr.shift_code') ?></th>
                    <th><?= lang('Hr.start_time') ?></th>
                    <th><?= lang('Hr.end_time') ?></th>
                    <th><?= lang('Hr.working_hours') ?></th>
                    <th style="width: 100px;"><?= lang('Hr.night_shift') ?></th>
                    <th style="width: 100px;"><?= lang('Common.status') ?></th>
                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shifts as $shift): ?>
                    <tr>
                        <td><?= str_pad($shift['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td><strong><?= esc($shift['name']) ?></strong></td>
                        <td><span class="label label-info"><?= esc($shift['code']) ?></span></td>
                        <td><?= esc($shift['start_time']) ?></td>
                        <td><?= esc($shift['end_time']) ?></td>
                        <td><?= esc($shift['working_hours']) ?></td>
                        <td>
                            <?php if ($shift['is_night_shift']): ?>
                                <span class="label label-warning"><?= lang('Common.yes') ?></span>
                            <?php else: ?>
                                <span class="label label-default"><?= lang('Common.no') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($shift['is_active']): ?>
                                <span class="label label-success"><?= lang('Common.active') ?></span>
                            <?php else: ?>
                                <span class="label label-default"><?= lang('Common.inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-xs modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
                                    data-href="<?= site_url("hr/shift/{$shift['id']}") ?>" title="<?= lang('Common.edit') ?>">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    var buttons = document.querySelectorAll('button.modal-dlg');
    buttons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.getAttribute('data-href') || this.getAttribute('href');
            var title = this.getAttribute('title') || 'Form';
            var btnSubmit = this.getAttribute('data-btn-submit') || 'Submit';
            
            BootstrapDialog.show({
                title: title,
                message: function(dialog) {
                    var $content = $('<div style="padding:10px;"><div class="text-center"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</div></div>');
                    $.get(href, function(data) {
                        $content.html(data);
                    });
                    return $content;
                },
                size: BootstrapDialog.SIZE_NORMAL,
                buttons: [{
                    label: btnSubmit,
                    cssClass: 'btn-primary',
                    action: function(dialogRef) {
                        var form = dialogRef.$modalBody.find('form');
                        if (form.length && form[0].checkValidity()) {
                            form.submit();
                            dialogRef.close();
                        } else if (form.length) {
                            form[0].reportValidity();
                        }
                    }
                }, {
                    label: 'Close',
                    action: function(dialogRef) {
                        dialogRef.close();
                    }
                }]
            });
        });
    });
});
</script>

<?= view('partial/footer') ?>
