<?php
/**
 * @var array $positions
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
            <li class="active"><?= lang('Hr.positions') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1><span class="glyphicon glyphicon-briefcase"></span><?= lang('Hr.positions') ?></h1>
        <div>
            <button class="btn btn-primary btn-lg modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" 
                    data-href="<?= site_url('hr/position') ?>" title="<?= lang('Hr.new_position') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.new_position') ?>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover" id="position_table">
            <thead>
                <tr>
                    <th style="width: 60px;"><?= lang('Common.id') ?></th>
                    <th><?= lang('Hr.name') ?></th>
                    <th><?= lang('Hr.department') ?></th>
                    <th style="width: 80px;"><?= lang('Hr.level') ?></th>
                    <th><?= lang('Hr.description') ?></th>
                    <th style="width: 100px;"><?= lang('Common.status') ?></th>
                    <th style="width: 80px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($positions as $pos): ?>
                    <tr>
                        <td><?= str_pad($pos['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td><strong><?= esc($pos['name']) ?></strong></td>
                        <td><?= esc($pos['department_name'] ?? '—') ?></td>
                        <td><?= esc($pos['level']) ?></td>
                        <td class="text-muted"><?= esc($pos['description'] ?? '—') ?></td>
                        <td>
                            <?php if ($pos['is_active']): ?>
                                <span class="label label-success"><?= lang('Common.active') ?></span>
                            <?php else: ?>
                                <span class="label label-default"><?= lang('Common.inactive') ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-xs modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>"
                                    data-href="<?= site_url("hr/position/{$pos['id']}") ?>" title="<?= lang('Common.edit') ?>">
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
