<?php
/**
 * @var array $requests
 * @var string|null $status_filter
 */
?>

<?= view('partial/header') ?>

<style>
.hr-page {
    padding: 20px 0;
}
.page-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
    flex-wrap: wrap;
    gap: 15px;
}
.page-header-bar h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    color: #2c3e50;
}
.page-header-bar h1 .glyphicon {
    margin-right: 12px;
    color: var(--primary);
}
.filter-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.filter-btn {
    padding: 8px 16px;
    border-radius: 20px;
    border: 2px solid #e9ecef;
    background: #fff;
    color: #6c757d;
    font-weight: 600;
    transition: all 0.2s;
}
.filter-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
}
.filter-btn.active {
    background: var(--primary);
    border-color: var(--primary);
    color: #fff;
}
.filter-btn.pending.active {
    background: #ffc107;
    border-color: #ffc107;
    color: #000;
}
.filter-btn.approved.active {
    background: #28a745;
    border-color: #28a745;
}
.filter-btn.rejected.active {
    background: #dc3545;
    border-color: #dc3545;
}
.hr-table {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
.hr-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}
.hr-table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border: none;
    padding: 15px;
}
.hr-table tbody td {
    vertical-align: middle;
    padding: 12px 15px;
    border-color: #f0f0f0;
}
.hr-table tbody tr:hover {
    background-color: #f8f9ff;
}
.hr-table .id-cell {
    font-weight: 600;
    color: #6c757d;
    min-width: 50px;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}
.status-badge.pending { background: #fff3cd; color: #856404; }
.status-badge.approved { background: #d4edda; color: #155724; }
.status-badge.rejected { background: #f8d7da; color: #721c24; }
.status-badge.cancelled { background: #e2e3e5; color: #383d41; }
.days-badge {
    background: #e3f2fd;
    color: #1565c0;
    padding: 4px 10px;
    border-radius: 4px;
    font-weight: 600;
}
.date-display {
    font-family: monospace;
    font-size: 13px;
    color: #495057;
}
.breadcrumb-bar {
    margin-bottom: 20px;
}
.breadcrumb-bar .breadcrumb {
    margin: 0;
    padding: 10px 0;
    background: transparent;
}
.action-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
</style>

<div class="hr-page">
    <div class="breadcrumb-bar">
        <ol class="breadcrumb">
            <li><a href="<?= site_url() ?>"><?= lang('Module.home') ?></a></li>
            <li><a href="<?= site_url('hr') ?>"><?= lang('Hr.hr_dashboard') ?></a></li>
            <li class="active"><?= lang('Hr.leave_requests') ?></li>
        </ol>
    </div>

    <div class="page-header-bar">
        <h1>
            <span class="glyphicon glyphicon-calendar"></span>
            <?= lang('Hr.leave_requests') ?>
        </h1>
        <div>
            <button class="btn btn-primary btn-lg modal-dlg" data-btn-submit="<?= lang('Common.submit') ?>" 
                    data-href="<?= site_url('hr/leave_request') ?>" title="<?= lang('Hr.new_request') ?>">
                <span class="glyphicon glyphicon-plus"></span> <?= lang('Hr.new_request') ?>
            </button>
        </div>
    </div>

    <div class="filter-bar">
        <a href="<?= site_url('hr/leave_requests') ?>" class="filter-btn <?= !$status_filter ? 'active' : '' ?>">
            <span class="glyphicon glyphicon-list"></span> <?= lang('Common.all') ?>
        </a>
        <a href="<?= site_url('hr/leave_requests?status=pending') ?>" class="filter-btn pending <?= $status_filter === 'pending' ? 'active' : '' ?>">
            <span class="glyphicon glyphicon-time"></span> <?= lang('Common.pending') ?>
        </a>
        <a href="<?= site_url('hr/leave_requests?status=approved') ?>" class="filter-btn approved <?= $status_filter === 'approved' ? 'active' : '' ?>">
            <span class="glyphicon glyphicon-ok"></span> <?= lang('Common.approved') ?>
        </a>
        <a href="<?= site_url('hr/leave_requests?status=rejected') ?>" class="filter-btn rejected <?= $status_filter === 'rejected' ? 'active' : '' ?>">
            <span class="glyphicon glyphicon-remove"></span> <?= lang('Common.rejected') ?>
        </a>
    </div>

    <div class="hr-table">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;"><?= lang('Common.id') ?></th>
                    <th><?= lang('Hr.employee') ?></th>
                    <th style="width: 130px;"><?= lang('Hr.leave_type') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.start_date') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.end_date') ?></th>
                    <th style="width: 90px;"><?= lang('Hr.total_days') ?></th>
                    <th style="width: 110px;"><?= lang('Hr.status') ?></th>
                    <th style="width: 100px;"><?= lang('Common.actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td class="id-cell"><?= str_pad($request['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <strong><?= esc(($request['first_name'] ?? '') . ' ' . ($request['last_name'] ?? '')) ?: '<span class="text-muted">—</span>' ?></strong>
                        </td>
                        <td>
                            <span class="text-primary"><?= esc($request['leave_type_name'] ?? '—') ?></span>
                        </td>
                        <td>
                            <span class="date-display"><?= date('d M Y', strtotime($request['start_date'])) ?></span>
                        </td>
                        <td>
                            <span class="date-display"><?= date('d M Y', strtotime($request['end_date'])) ?></span>
                        </td>
                        <td>
                            <span class="days-badge"><?= $request['total_days'] ?> day(s)</span>
                        </td>
                        <td>
                            <span class="status-badge <?= $request['status'] ?>"><?= lang("Common.{$request['status']}") ?></span>
                        </td>
                        <td>
                            <?php if ($request['status'] === 'pending'): ?>
                                <button class="btn btn-success btn-xs action-btn btn-approve" data-id="<?= $request['id'] ?>" title="<?= lang('Common.approve') ?>">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </button>
                                <button class="btn btn-danger btn-xs action-btn btn-reject" data-id="<?= $request['id'] ?>" title="<?= lang('Common.reject') ?>">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($requests)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted" style="padding: 40px;">
                            <span class="glyphicon glyphicon-folder-open" style="font-size: 48px; opacity: 0.3;"></span>
                            <p style="margin-top: 15px;"><?= lang('Common.no_data') ?></p>
                        </td>
                    </tr>
                <?php endif; ?>
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
    
    $('.btn-approve').on('click', function() {
        if (!confirm('<?= lang('Hr.confirm_approve') ?>')) return;
        
        $.ajax({
            url: '<?= site_url('hr/approve_leave') ?>',
            type: 'POST',
            data: { id: $(this).data('id') },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) location.reload();
            }
        });
    });
    
    $('.btn-reject').on('click', function() {
        var reason = prompt('<?= lang('Hr.rejection_reason') ?>:');
        if (reason === null) return;
        
        $.ajax({
            url: '<?= site_url('hr/reject_leave') ?>',
            type: 'POST',
            data: { id: $(this).data('id'), reason: reason },
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if (response.success) location.reload();
            }
        });
    });
});
</script>

<?= view('partial/footer') ?>
